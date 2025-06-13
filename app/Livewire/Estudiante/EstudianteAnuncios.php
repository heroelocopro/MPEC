<?php

namespace App\Livewire\Estudiante;

use App\Models\Anuncio;
use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EstudianteAnuncios extends Component
{
    public $anuncios = [];
    // normales
    public $colegio;
    public $estudiante;
    public $matricula;
    public $grado;
    public $grupo;
    public function cargarAnuncios()
    {
        $this->anuncios = Anuncio::where('colegio_id',$this->colegio->id)->get();
    }
    public function mount()
    {
        // datos basicos del estudiante
        $this->estudiante = Estudiante::where('user_id',Auth::user()->id)->first() ?? null;
        $this->colegio = $this->estudiante->colegio ?? null;
        $this->matricula = $this->estudiante->matricula ?? null;
        $this->grado = $this->matricula->grado ?? null;
        $this->grupo = EstudianteGrupo::where('estudiante_id',$this->estudiante->id)->first()->grupo ?? null;
        if($this->colegio != null)
        {
            $this->cargarAnuncios();
        }
    }
    public function render()
    {
        return view('livewire.estudiante.estudiante-anuncios');
    }
}
