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

    // modal
    public $examen;
    public $modal;

    // selectores
    public $asignatura_id;
    public $grupos = [];
    public $grados = [];
    public $grupo_id;


    // variables iniciales
    public $profesor;
    public $colegio;
    public $asignaturas = [];
    public $periodo;
    public $examenes = [];

    public function mostrarExamen($id)
    {
        $this->modal = true;
        $this->examen = Examen::findOrFail($id);
    }

    public function cargarExamenes($grupo_id)
    {
        $this->examenes = Examen::where('grupo_id',$grupo_id)->where('asignatura_id',$this->asignatura_id)->get();
    }

    public function cargarGrupos($asignatura_id)
    {
        // Limpiar variables antes de usarlas
        $this->grupos = [];
        $this->grados = [];

        if ($asignatura_id != null && $asignatura_id != 0) {
            // Obtener relaciones de grados con la asignatura
            $asignaturasGrados = AsignaturaGrado::where('asignatura_id', $asignatura_id)->with('grado.grupos')->get();

            // Agregar grados
            foreach ($asignaturasGrados as $asignaturaGrado) {
                $this->grados[] = $asignaturaGrado->grado;
            }

            // Recorrer grados y agregar grupos
            foreach ($this->grados as $grado) {
                foreach ($grado->grupos as $grupo) {
                    $this->grupos[] = $grupo;
                }
            }
        }
    }

    public function updatedAsignaturaId($value)
    {
        $this->grupos = [];
        $this->grados = [];

        if ($value != null && $value != 0) {
            $this->cargarGrupos($value);
        }else{
            $this->grupos = [];
            $this->grados = [];
        }
    }

    public function updatedGrupoId($value)
    {
        $this->examenes = [];

        if($value != null && $value != 0)
        {
            $this->cargarExamenes($value);
        }
        else{
            $this->examenes = [];
        }
    }

    public function cargarAsignaturas($asignaturas)
    {
        // limpiamos
        $this->asignaturas = [];
        // bucle array
        foreach($asignaturas as $asignatura)
        {
            array_push($this->asignaturas,$asignatura->asignatura);
        }
    }
    public function mount()
    {
        // obtenemos al profesor colegio asignaturas periodo y cargamos asignaturas
        $this->profesor = Profesor::where('user_id',Auth::user()->id)->first();
        $this->colegio = $this->profesor->colegio;
        $asignaturas = asignaturaProfesor::where('profesor_id',$this->profesor->id)->get();
        $this->periodo = PeriodoAcademico::periodoActual($this->colegio->id);
        $this->cargarAsignaturas($asignaturas);
    }
    public function render()
    {
        return view('livewire.docente.docente-ver-examenes');
    }
}
