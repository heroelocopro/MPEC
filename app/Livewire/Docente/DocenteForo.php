<?php

namespace App\Livewire\Docente;

use Livewire\Component;

class DocenteForo extends Component
{
    // variables publicas
    public $foros = [];
    public $modalCrear = false;
    // funciones publicas
    public function crearForo()
    {

    }
    public function render()
    {
        return view('livewire.docente.docente-foro');
    }
}
