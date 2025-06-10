<?php

namespace App\Livewire\Docente;

use App\Models\Horario;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocenteHorarios extends Component
{
    // horarios
    public $horario = [];
    public function cargarHorario()
{
    $this->horario = Horario::where('profesor_id', $this->docente->id)
        ->orderBy('dia')
        ->orderBy('hora_inicio')
        ->get();
}

    // datos iniciales
    public $colegio;
    public $docente;
    public function mount()
    {
        $this->docente = Profesor::where('user_id',Auth::user()->id)->first();
        $this->colegio = $this->docente->colegio;
        $this->cargarHorario();
    }
    public function render()
    {
        return view('livewire.docente.docente-horarios');
    }
}
