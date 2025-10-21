<?php

namespace App\Services;

use App\Models\{
    Actividad, EstudianteGrupo, Examen, Grupo, Nota, NotaFinal, PeriodoAcademico
};
use Illuminate\Support\Facades\Log;

class NotasFinalesService
{
    /**
     * Genera las notas finales para los periodos que finalizan en una fecha dada.
     */
    public function generarParaPeriodosQueFinalizan($fecha)
    {
        $periodos = PeriodoAcademico::where('estado', 'activo')
            ->whereDate('fecha_fin', $fecha->toDateString())
            ->get();

        if ($periodos->isEmpty()) {
            return 'No hay periodos activos que finalicen en la fecha indicada.';
        }

        foreach ($periodos as $periodo) {
            $this->generarParaPeriodo($periodo);
        }

        return 'Notas finales generadas correctamente.';
    }

    /**
     * Genera notas finales para un periodo específico.
     */
    public function generarParaPeriodo($periodo)
    {
        $periodoId = $periodo->id;

        $grupoIds = Actividad::where('periodo_id', $periodoId)->pluck('grupo_id')
            ->merge(Examen::where('periodo_id', $periodoId)->pluck('grupo_id'))
            ->unique();

        if ($grupoIds->isEmpty()) {
            Log::info("No hay grupos con actividades o exámenes para el periodo {$periodo->id}.");
            return;
        }

        $grupos = Grupo::with('colegio', 'grado.asignaturas')
            ->whereIn('id', $grupoIds)
            ->get();

        foreach ($grupos as $grupo) {
            $this->procesarGrupo($grupo, $periodoId);
        }
    }

    /**
     * Procesa todas las asignaturas de un grupo en un periodo.
     */
    protected function procesarGrupo($grupo, $periodoId)
    {
        $colegioId = $grupo->colegio_id;

        foreach ($grupo->grado->asignaturas as $asignatura) {
            $this->procesarAsignatura($grupo, $asignatura->id, $periodoId, $colegioId);
        }
    }

    /**
     * Procesa las notas finales para una asignatura específica de un grupo.
     */
    protected function procesarAsignatura($grupo, $asignaturaId, $periodoId, $colegioId)
    {
        $actividades = Actividad::where([
            ['periodo_id', $periodoId],
            ['grupo_id', $grupo->id],
            ['asignatura_id', $asignaturaId],
        ])->get();

        $evaluaciones = Examen::where([
            ['periodo_id', $periodoId],
            ['grupo_id', $grupo->id],
            ['asignatura_id', $asignaturaId],
        ])->get();

        $itemsEsperados = collect($actividades)
            ->merge($evaluaciones)
            ->map(fn($item) => $item->id . ':' . class_basename($item));

        $estudiantes = EstudianteGrupo::with('estudiante')
            ->where('grupo_id', $grupo->id)
            ->get()
            ->pluck('estudiante')
            ->filter();

        $notas = Nota::where([
                ['periodo_id', $periodoId],
                ['grupo_id', $grupo->id],
                ['asignatura_id', $asignaturaId],
            ])
            ->get()
            ->groupBy('estudiante_id');

        foreach ($estudiantes as $estudiante) {
            $this->procesarNotasEstudiante(
                $estudiante->id,
                $grupo->id,
                $asignaturaId,
                $periodoId,
                $colegioId,
                $itemsEsperados,
                $notas->get($estudiante->id, collect())
            );
        }
    }

    /**
     * Calcula y guarda la nota final de un estudiante.
     */
    protected function procesarNotasEstudiante($estudianteId, $grupoId, $asignaturaId, $periodoId, $colegioId, $itemsEsperados, $notasDelEstudiante)
    {
        $notasIds = $notasDelEstudiante->map(fn($nota) => $nota->notable_id . ':' . class_basename($nota->notable_type));
        $faltantes = $itemsEsperados->diff($notasIds);

        $valores = $notasDelEstudiante->pluck('valor')->toArray();
        $valores = array_merge($valores, array_fill(0, $faltantes->count(), 1.0));

        if (count($valores) === 0) {
            Log::warning("El estudiante {$estudianteId} no tiene actividades ni evaluaciones en asignatura {$asignaturaId} del grupo {$grupoId}.");
            return;
        }

        $promedio = round(array_sum($valores) / count($valores), 2);

        NotaFinal::updateOrCreate(
            [
                'estudiante_id' => $estudianteId,
                'asignatura_id' => $asignaturaId,
                'grupo_id' => $grupoId,
                'colegio_id' => $colegioId,
                'periodo_id' => $periodoId,
                'ano' => now()->year,
            ],
            ['nota' => $promedio]
        );
    }

    /**
     * Cierra las notas finales de un periodo (opcional, según tu necesidad).
     */
public function cerrarNotas($periodoId)
{
    $periodo = PeriodoAcademico::find($periodoId);

    if (!$periodo) {
        return "No se encontró el periodo con ID {$periodoId}.";
    }

    // 🔹 1. Generar notas finales antes de cerrar el periodo
    $this->generarParaPeriodo($periodo);

    // 🔹 2. Cambiar el estado del periodo a 'inactivo'
    $periodo->estado = 'inactivo';
    $periodo->save();

    // 🔹 3. Mensaje final
    return "Notas del periodo {$periodo->nombre} generadas y cerradas correctamente.";
}

}
