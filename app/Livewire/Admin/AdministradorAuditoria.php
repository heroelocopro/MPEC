<?php

namespace App\Livewire\Admin;

use App\Models\PeriodoAcademico;
use Livewire\Component;

class AdministradorAuditoria extends Component
{
    public $modeloSeleccionado;
    public $auditorias;
    public array $modelos;
    public int $paginate;
    public function mount()
    {
        $this->paginate = 5;
        $periodo = PeriodoAcademico::first();
        $all = $periodo->audits()->first();
    }
    public function render()
    {
        return view('livewire.admin.administrador-auditoria');
    }
}
