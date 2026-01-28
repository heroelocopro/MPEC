<?php

namespace App\Livewire\Colegio;

use App\Models\PeriodoAcademico;
use Livewire\Component;

class ColegioCerrarNotas extends Component
{
    public $periodo;
    public function mount()
    {
        $this->periodo = PeriodoAcademico::periodoActual();
    }
    public function render()
    {
        return view('livewire.colegio.colegio-cerrar-notas');
    }
}
