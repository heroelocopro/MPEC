<?php

namespace App\Livewire\Estudiante;

use App\Models\asignatura;
use App\Models\AsignaturaGrado;
use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EstudianteAsignaturas extends Component
{
    public $asignaturas = [];
    // normales
    public $colegio;
    public $estudiante;
    public $matricula;
    public $grado;
    public $grupo;
    public function cargarAsignaturas()
    {
        $asignaturasGrados = AsignaturaGrado::with('grado')->where('grado_id',$this->grado->id)->get();
        foreach($asignaturasGrados as $asignatura)
        {
            array_push($this->asignaturas,$asignatura->asignatura);
        }

    }
    public function mount()
    {
        // datos basicos del estudiante
        $this->estudiante = Estudiante::where('user_id',Auth::user()->id)->first();
        $this->colegio = $this->estudiante->colegio;
        $this->matricula = $this->estudiante->matricula;
        $this->grado = $this->matricula->grado;
        $this->grupo = EstudianteGrupo::where('estudiante_id',$this->estudiante->id)->first()->grupo;
        $this->cargarAsignaturas();
    }
    public function render()
    {
        return view('livewire.estudiante.estudiante-asignaturas');
    }
}
