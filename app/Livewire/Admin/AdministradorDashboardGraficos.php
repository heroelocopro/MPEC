<?php

namespace App\Livewire\Admin;

use App\Models\Colegio;
use Livewire\Component;

class AdministradorDashboardGraficos extends Component
{
    public $colegiosData = [];

    public function mount()
    {
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $this->colegiosData = Colegio::withCount('estudiantes')->get()->map(function ($colegio) {
            return [
                'nombre' => $colegio->nombre,
                'estudiantes' => $colegio->estudiantes_count,
            ];
        })->toArray();
    }
    public function render()
    {
        return view('livewire.admin.administrador-dashboard-graficos');
    }
}
