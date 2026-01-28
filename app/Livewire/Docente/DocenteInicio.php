<?php

namespace App\Livewire\Docente;

use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocenteInicio extends Component
{
    public $profesor;
    public $gruposProfesor;
    public function mount()
    {
        $this->profesor = Profesor::where('user_id',Auth::user()->id)->first() ?? null;

        $this->gruposProfesor = $this->profesor ? $this->profesor->gruposAsignados() : null;
    }
    public function render()
    {
        return view('livewire.docente.docente-inicio');
    }
}
