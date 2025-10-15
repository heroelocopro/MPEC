<?php

namespace App\Livewire\Docente;

use App\Models\AsignaturaGrado;
use App\Models\asignaturaProfesor;
use App\Models\Grupo;
use App\Models\NotaFinal;
use App\Models\PeriodoAcademico;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocenteNotasPeriodo extends Component
{
    // ===============================
    // 🔹 VARIABLES DE ESTADO
    // ===============================
    public $periodoSeleccionado;
    public $grupoSeleccionado;

    public $notaFinalAsignaturas = [];
    public $promediosEstudiantes = [];
    public $asignaturas = [];
    public $estudiantes = [];
    public $notasPorEstudiante = [];

    // datos base
    public $profesor;
    public $colegio;
    public $periodos = [];
    public $grupos = [];

    // ===============================
    // 🔹 MÉTODOS AUXILIARES
    // ===============================

    public function cargarPeriodos()
    {
        if (!isset($this->colegio->id)) {
            $this->periodos = [];
            return;
        }

        $this->periodos = PeriodoAcademico::where('colegio_id', $this->colegio->id)
            ->where('ano', now()->year)
            ->get();
    }

    public function cargarGrupos()
    {
        if (!isset($this->profesor->id)) {
            $this->grupos = [];
            return;
        }

        $asignaturasProfesor = asignaturaProfesor::where('profesor_id', $this->profesor->id)->get();
        $asignaturas = [];

        foreach ($asignaturasProfesor as $asignaturaProfesor) {
            array_push($asignaturas, $asignaturaProfesor->asignatura);
        }

        $asignaturaGrados = [];
        foreach ($asignaturas as $asignatura) {
            foreach ($asignatura->asignaturaGrados as $asignaturaGrado) {
                array_push($asignaturaGrados, $asignaturaGrado);
            }
        }

        $this->grupos = [];
        foreach ($asignaturaGrados as $asignaturaGrado) {
            foreach ($asignaturaGrado->grado->grupos as $grupo) {
                array_push($this->grupos, $grupo);
            }
        }
    }

    public function obtenerAsignaturasDelProfesorEnGrupo($grupoId)
    {
        if (!isset($this->profesor->id) || !$grupoId) {
            return collect();
        }

        $asignaturasIdsDelProfesor = asignaturaProfesor::where('profesor_id', $this->profesor->id)
            ->pluck('asignatura_id')
            ->toArray();

        $grupo = Grupo::find($grupoId);
        if (!$grupo) {
            return collect();
        }

        return AsignaturaGrado::where('grado_id', $grupo->grado_id)
            ->whereIn('asignatura_id', $asignaturasIdsDelProfesor)
            ->with('asignatura')
            ->get()
            ->pluck('asignatura', 'asignatura_id');
    }

    // ===============================
    // 🔹 NOTAS Y PROMEDIOS
    // ===============================

    public function obtenerNotasFinales()
    {
        if (!$this->grupoSeleccionado || !$this->periodoSeleccionado || !isset($this->colegio->id)) {
            return;
        }

        $asignaturasAutorizadas = $this->obtenerAsignaturasDelProfesorEnGrupo($this->grupoSeleccionado);
        if ($asignaturasAutorizadas->isEmpty()) {
            return;
        }

        $notas = NotaFinal::with('asignatura', 'estudiante')
            ->where('colegio_id', $this->colegio->id)
            ->where('periodo_id', $this->periodoSeleccionado)
            ->where('grupo_id', $this->grupoSeleccionado)
            ->where('ano', now()->year)
            ->whereIn('asignatura_id', $asignaturasAutorizadas->keys())
            ->get();

        $this->notaFinalAsignaturas = $notas;
        $this->asignaturas = [];
        $estudiantes = [];
        $notasAgrupadas = [];

        foreach ($notas as $notaFinal) {
            $estudianteId = $notaFinal->estudiante_id;
            $asignaturaId = $notaFinal->asignatura_id;

            $this->notasPorEstudiante["$estudianteId-$asignaturaId"] = $notaFinal->nota;
            $this->asignaturas[$asignaturaId] = $notaFinal->asignatura->nombre;
            $estudiantes[$estudianteId] = $notaFinal->estudiante->nombre_completo;

            $notasAgrupadas[$estudianteId][] = $notaFinal->nota;
        }

        $promedios = [];
        foreach ($notasAgrupadas as $estudianteId => $notasEstudiante) {
            $promedio = count($notasEstudiante) > 0
                ? round(array_sum($notasEstudiante) / count($notasEstudiante), 2)
                : 0;
            $promedios[$estudianteId] = $promedio;
        }

        $this->estudiantes = $estudiantes;
        $this->promediosEstudiantes = $promedios;
    }

    public function actualizarNota($estudianteId, $asignaturaId)
    {
        if (!isset($this->profesor->id)) return;

        $permitidas = asignaturaProfesor::where('profesor_id', $this->profesor->id)
            ->pluck('asignatura_id')
            ->toArray();

        if (!in_array($asignaturaId, $permitidas)) {
            return;
        }

        $clave = "$estudianteId-$asignaturaId";
        $nota = $this->notasPorEstudiante[$clave] ?? null;

        if (is_null($nota)) return;

        try {
            NotaFinal::updateOrCreate(
                [
                    'estudiante_id' => $estudianteId,
                    'asignatura_id' => $asignaturaId,
                    'periodo_id' => $this->periodoSeleccionado,
                    'grupo_id' => $this->grupoSeleccionado,
                    'colegio_id' => $this->colegio->id,
                    'ano' => now()->year,
                ],
                ['nota' => $nota]
            );

            $this->dispatch('alerta', [
                'title' => 'Cambio de notas exitoso',
                'text' => '¡Se guardó correctamente!',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al guardar la nota',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }

    // ===============================
    // 🔹 EVENTOS LIVEWIRE
    // ===============================

    public function updatedPeriodoSeleccionado($value)
    {
        if ($value != null && $value != 0) {
            $this->obtenerNotasFinales();
        }
    }

    public function updatedGrupoSeleccionado($value)
    {
        if ($value != null && $value != 0) {
            $this->obtenerNotasFinales();
        }
    }

    public function seleccionarGrupo($grupo)
    {
        $this->grupoSeleccionado = $grupo;
        $this->updatedGrupoSeleccionado($grupo);
    }

    public function seleccionarPeriodo($periodo)
    {
        $this->periodoSeleccionado = $periodo;
        $this->updatedPeriodoSeleccionado($periodo);
    }

    // ===============================
    // 🔹 CICLO DE VIDA
    // ===============================

    public function mount()
    {
        $this->profesor = Profesor::where('user_id', Auth::user()->id)->first() ?? (object) [];
        $this->colegio = isset($this->profesor->colegio)
            ? $this->profesor->colegio
            : (object) ['id' => null];
        $this->cargarPeriodos();
        $this->cargarGrupos();
    }

    public function render()
    {
        return view('livewire.docente.docente-notas-periodo');
    }
}
