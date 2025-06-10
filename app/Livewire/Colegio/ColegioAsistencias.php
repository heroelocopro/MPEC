<?php

namespace App\Livewire\Colegio;

use App\Models\Colegio;
use App\Models\EstudianteGrupo;
use App\Models\Grado;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioAsistencias extends Component
{
    public $colegio;
    public $grados = [];
    public $grado_id;
    public $grupos = [];
    public $grupo_id;
    public $grupo = null;
    public $estudiantes = [];

    public function seleccionarGrado($id)
    {
        $this->grado_id = null;
        $this->grupos = [];
        if($id != null || $id != 0)
        {
            $grado = Grado::findOrFail($id);
            $this->grado_id = $grado->id;
            $this->grupos = $grado->grupos;
        }
    }
    public function updatedGrupoId($value)
    {
        $this->grupo = null;
        if($value != null || $value != 0)
        {
            $this->grupo = Grupo::findOrFail($value);
            foreach(EstudianteGrupo::where('grupo_id',$value)->get() as $estudianteGrupo)
            {
                array_push($this->estudiantes,$estudianteGrupo->estudiante);
            }
        }
    }
    public function cargarGrados()
    {
        $this->grados = $this->colegio->grados;
    }
    public function mount()
    {
        $this->colegio = Colegio::where('user_id',Auth::user()->id)->first();
        $this->cargarGrados();
    }
    public function render()
    {
        return view('livewire.colegio.colegio-asistencias');
    }
}
