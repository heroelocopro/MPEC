<?php

namespace App\Livewire\Estudiante;

use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EstudianteDocentes extends Component
{
    public $profesores = [];
    // otros
    public $colegio;
    public $estudiante;
    public $matricula;
    public $grado;
    public $grupo;
    public function cargarDocentes()
    {
        $this->profesores = Profesor::with('asignaturas')->get();
    }
    public function mount()
    {
        // datos basicos del estudiante
        $this->estudiante = Estudiante::where('user_id',Auth::user()->id)->first();
        $this->colegio = $this->estudiante->colegio;
        $this->matricula = $this->estudiante->matricula;
        $this->grado = $this->matricula->grado;
        $this->grupo = EstudianteGrupo::where('estudiante_id',$this->estudiante->id)->first()->grupo;
        $this->cargarDocentes();
    }
    public function render()
    {
        return view('livewire.estudiante.estudiante-docentes');
    }
}
