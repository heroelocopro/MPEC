<?php

namespace App\Livewire\Estudiante;

use App\Models\Estudiante;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EstudianteInicio extends Component
{
    public $estudiante;
    public $anuncios;
    public function mount()
    {
        $usuario = Auth::user();
        $this->estudiante = Estudiante::where('user_id',$usuario->id)->first();
        $colegio = $estudiante->colegio ?? 'sin Colegio';
        if ($colegio != 'sin Colegio')
        {
            $this->anuncios = $colegio->anuncios()->latest()->get();
        }else
        {
            $this->anuncios = [];
        }
    }
    public function render()
    {
        return view('livewire.estudiante.estudiante-inicio');
    }
}
