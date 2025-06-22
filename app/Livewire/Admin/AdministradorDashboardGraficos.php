<?php

namespace App\Livewire\Admin;

use App\Models\Colegio;
use Livewire\Component;

class AdministradorDashboardGraficos extends Component
{
    public $colegios;
    public $nombres;
    public $cantidades;
    public $ids;

    public function mount()
    {
        $this->reset();
        $this->colegios = Colegio::withCount('estudiantes')->get();
        $this->nombres = $this->colegios->pluck('nombre');
        $this->cantidades = $this->colegios->pluck('estudiantes_count')->map(fn($v) => (int) $v);
        $this->ids = $this->colegios->pluck('id');
        $this->dispatch('initGraficoColegios', $this->nombres, $this->cantidades,$this->ids);
    }
    public function render()
    {
        return view('livewire.admin.administrador-dashboard-graficos');
    }

}
