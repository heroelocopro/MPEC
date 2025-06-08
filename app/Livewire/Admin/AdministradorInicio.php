<?php

namespace App\Livewire\Admin;

use App\Models\Colegio;
use App\Models\Estudiante;
use App\Models\Profesor;
use Livewire\Component;

class AdministradorInicio extends Component
{
    public $totalColegios;
    public $totalEstudiantes;
    public $totalProfesores;
    public function mount()
    {
        $this->totalColegios = count(Colegio::all());
        $this->totalEstudiantes = count(Estudiante::all());
        $this->totalProfesores = count(Profesor::all());
    }
    public function render()
    {
        return view('livewire.admin.administrador-inicio');
    }
}
