<?php

namespace App\Livewire\Colegio;

use App\Models\asignatura;
use App\Models\Colegio;
use App\Models\Grupo;
use App\Models\Horario;
use App\Models\matricula;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioInicio extends Component
{
    public $totalEstudiantes, $totalDocentes, $totalGrupos, $totalAsignaturas, $totalClasesProgramadas;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $colegioId = Colegio::where('user_id', Auth::user()->id)->first()->id;
        $this->totalEstudiantes = matricula::where('colegio_id', $colegioId)->count();
        $this->totalDocentes = Profesor::where('colegio_id', $colegioId)->count();
        $this->totalGrupos = Grupo::where('colegio_id', $colegioId)->count();
        $this->totalAsignaturas = asignatura::where('colegio_id', $colegioId)->count();
        $this->totalClasesProgramadas = Horario::where('colegio_id',$colegioId)->count();

        $grupos = Grupo::where('colegio_id', $colegioId)
            ->withCount('estudiantes')
            ->get();

        $nombresGrupos = $grupos->pluck('nombre');
        $cantidadesEstudiantes = $grupos->pluck('estudiantes_count');
        $grupos = Grupo::where('colegio_id', $colegioId)
        ->withCount('estudiantes')
        ->pluck( 'nombre');
        $estudiantes = Grupo::where('colegio_id', $colegioId)
        ->withCount('estudiantes')
        ->pluck('estudiantes_count');
        $ids = Grupo::where('colegio_id', $colegioId)
        ->withCount('estudiantes')
        ->pluck( 'id');
        $this->dispatch('chartUpdate', $grupos,$estudiantes,$ids);

    }

    public function render()
    {
        return view('livewire.colegio.colegio-inicio');
    }
}
