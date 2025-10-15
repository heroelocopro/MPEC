<?php

namespace App\Livewire\Docente;

use App\Models\AsignaturaGrado;
use App\Models\asignaturaProfesor;
use App\Models\Examen;
use App\Models\PeriodoAcademico;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocenteVerExamenes extends Component
{
    // ====== Variables para el modal ======
    public $examen;
    public $modal = false;

    // ====== Selectores ======
    public $asignatura_id = null;
    public $grupo_id = null;
    public $grupos = [];
    public $grados = [];

    // ====== Variables base ======
    public $profesor;
    public $colegio;
    public $periodo;
    public $asignaturas = [];
    public $examenes = [];

    // ============================================================
    // ======================== MÉTODOS ============================
    // ============================================================

    public function mostrarExamen($id)
    {
        $this->modal = true;
        $this->examen = Examen::find($id);

        if (!$this->examen) {
            session()->flash('error', 'El examen no existe o fue eliminado.');
            $this->modal = false;
        }
    }

    public function cargarExamenes($grupo_id)
    {
        $this->examenes = [];

        if (!$this->asignatura_id || !$grupo_id) {
            return;
        }

        $this->examenes = Examen::where('grupo_id', $grupo_id)
            ->where('asignatura_id', $this->asignatura_id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function cargarGrupos($asignatura_id)
    {
        $this->grupos = [];
        $this->grados = [];

        if (!$asignatura_id) {
            return;
        }

        // Traer grados y grupos relacionados
        $asignaturasGrados = AsignaturaGrado::where('asignatura_id', $asignatura_id)
            ->with('grado.grupos')
            ->get();

        foreach ($asignaturasGrados as $asignaturaGrado) {
            if ($asignaturaGrado->grado) {
                $this->grados[] = $asignaturaGrado->grado;

                foreach ($asignaturaGrado->grado->grupos ?? [] as $grupo) {
                    $this->grupos[] = $grupo;
                }
            }
        }

        // Evitar duplicados
        $this->grupos = collect($this->grupos)->unique('id')->values()->all();
        $this->grados = collect($this->grados)->unique('id')->values()->all();
    }

    public function updatedAsignaturaId($value)
    {
        $this->grupos = [];
        $this->grados = [];
        $this->grupo_id = null;
        $this->examenes = [];

        if ($value) {
            $this->cargarGrupos($value);
        }
    }

    public function updatedGrupoId($value)
    {
        $this->examenes = [];

        if ($value) {
            $this->cargarExamenes($value);
        }
    }

    public function cargarAsignaturas($asignaturas)
    {
        $this->asignaturas = [];

        foreach ($asignaturas as $asignatura) {
            if (isset($asignatura->asignatura)) {
                $this->asignaturas[] = $asignatura->asignatura;
            }
        }

        // eliminar duplicados
        $this->asignaturas = collect($this->asignaturas)->unique('id')->values()->all();
    }

    public function mount()
    {
        // ===== Obtener profesor actual =====
        $this->profesor = Profesor::where('user_id', Auth::id())->first();

        if (!$this->profesor) {
            // Caso sin profesor asignado
            $this->colegio = (object)['id' => null];
            $this->periodo = null;
            $this->asignaturas = [];
            return;
        }

        $this->colegio = $this->profesor->colegio ?? (object)['id' => null];
        $this->periodo = $this->colegio->id ? PeriodoAcademico::periodoActual($this->colegio->id) : null;

        // ===== Cargar asignaturas del profesor =====
        $asignaturas = asignaturaProfesor::where('profesor_id', $this->profesor->id)
            ->with('asignatura')
            ->get();

        $this->cargarAsignaturas($asignaturas);
    }

    public function render()
    {
        return view('livewire.docente.docente-ver-examenes');
    }
}
