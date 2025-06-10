<?php

namespace App\Console\Commands;

use App\Models\Actividad;
use App\Models\EstudianteGrupo;
use App\Models\Examen;
use App\Models\Grupo;
use App\Models\Nota;
use App\Models\NotaFinal;
use App\Models\PeriodoAcademico;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerarNotasFinales extends Command
{
    protected $signature = 'app:generar-notas-finales';

    protected $description = 'Genera notas finales para todos los grupos de colegios en los periodos que terminan mañana';

    public function handle()
    {
        $hoy = now();
        $manana = $hoy->copy()->addDay();

        // Obtener todos los periodos que finalizan mañana y están activos
        $periodos = PeriodoAcademico::where('estado', 'activo')
            ->whereDate('fecha_fin', $manana->toDateString())
            ->get();

        if ($periodos->isEmpty()) {
            $this->info('No hay periodos activos que finalicen mañana.');
            return;
        }

        foreach ($periodos as $periodo) {
            $periodoId = $periodo->id;

            // Obtener todos los grupos que tienen actividades o exámenes en este periodo
            $grupoIdsActividades = Actividad::where('periodo_id', $periodoId)->distinct()->pluck('grupo_id');
            $grupoIdsExamenes = Examen::where('periodo_id', $periodoId)->distinct()->pluck('grupo_id');

            $grupoIds = $grupoIdsActividades->merge($grupoIdsExamenes)->unique();

            if ($grupoIds->isEmpty()) {
                $this->info("No hay grupos con actividades o exámenes para el periodo {$periodo->id} que finaliza mañana.");
                continue;
            }

            // Cargar los grupos con colegio y grado (con asignaturas)
            $grupos = Grupo::with('colegio', 'grado.asignaturas')->whereIn('id', $grupoIds)->get();

            foreach ($grupos as $grupo) {
                $grupoId = $grupo->id;
                $colegioId = $grupo->colegio_id;

                // Obtener asignaturas relacionadas para el grado del grupo
                $asignaturas = $grupo->grado->asignaturas;

                foreach ($asignaturas as $asignatura) {
                    $asignaturaId = $asignatura->id;

                    // Obtener actividades y evaluaciones del grupo, asignatura y periodo
                    $actividades = Actividad::where([
                        ['periodo_id', $periodoId],
                        ['grupo_id', $grupoId],
                        ['asignatura_id', $asignaturaId],
                    ])->get();

                    $evaluaciones = Examen::where([
                        ['periodo_id', $periodoId],
                        ['grupo_id', $grupoId],
                        ['asignatura_id', $asignaturaId],
                    ])->get();

                    $itemsEsperados = collect($actividades)
                        ->merge($evaluaciones)
                        ->map(fn($item) => $item->id . ':' . class_basename($item));

                    // Obtener estudiantes del grupo
                    $estudiantesGrupos = EstudianteGrupo::with('estudiante')->where('grupo_id', $grupoId)->get();
                    $estudiantes = $estudiantesGrupos->pluck('estudiante')->filter();

                    // Obtener notas registradas agrupadas por estudiante
                    $notas = Nota::where('periodo_id', $periodoId)
                        ->where('grupo_id', $grupoId)
                        ->where('asignatura_id', $asignaturaId)
                        ->get()
                        ->groupBy('estudiante_id');

                    // Procesar cada estudiante
                    foreach ($estudiantes as $estudiante) {
                        $estudianteId = $estudiante->id;

                        $notasDelEstudiante = $notas->get($estudianteId, collect());

                        $notasIds = $notasDelEstudiante->map(fn($nota) => $nota->notable_id . ':' . class_basename($nota->notable_type));

                        $faltantes = $itemsEsperados->diff($notasIds);

                        // Si hay notas faltantes se asume un valor mínimo de 1.0
                        $valores = $notasDelEstudiante->pluck('valor')->toArray();
                        $valores = array_merge($valores, array_fill(0, $faltantes->count(), 1.0));

                        if (count($valores) === 0) {
                            Log::warning("El estudiante {$estudianteId} no tiene actividades ni evaluaciones en asignatura {$asignaturaId} del grupo {$grupoId}.");
                            continue;
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
                }
            }
        }

        $this->info('Notas finales generadas para todos los periodos que finalizan mañana.');
    }
}
