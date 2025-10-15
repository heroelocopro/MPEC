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
        // Protección si no existe el colegio asociado al usuario
        $colegio = Colegio::where('user_id', Auth::user()->id)->first() ?? (object) ['id' => null];
        $colegioId = $colegio->id;

        if (!$colegioId) {
            // Si no hay colegio, evitar errores y mostrar datos vacíos
            $this->totalEstudiantes = 0;
            $this->totalDocentes = 0;
            $this->totalGrupos = 0;
            $this->totalAsignaturas = 0;
            $this->totalClasesProgramadas = 0;
            $this->dispatch('chartUpdate', [], [], []);
            return;
        }

        // Cálculos principales
        $this->totalEstudiantes = matricula::where('colegio_id', $colegioId)->count();
        $this->totalDocentes = Profesor::where('colegio_id', $colegioId)->count();
        $this->totalGrupos = Grupo::where('colegio_id', $colegioId)->count();
        $this->totalAsignaturas = asignatura::where('colegio_id', $colegioId)->count();
        $this->totalClasesProgramadas = Horario::where('colegio_id', $colegioId)->count();

        // Solo una consulta para los grupos y sus estudiantes
        $grupos = Grupo::where('colegio_id', $colegioId)
            ->withCount('estudiantes')
            ->get(['id', 'nombre']);

        // Datos para el gráfico
        $nombresGrupos = $grupos->pluck('nombre');
        $cantidadesEstudiantes = $grupos->pluck('estudiantes_count');
        $ids = $grupos->pluck('id');

        // Enviar datos al gráfico
        $this->dispatch('chartUpdate', $nombresGrupos, $cantidadesEstudiantes, $ids);
    }

    public function render()
    {
        return view('livewire.colegio.colegio-inicio');
    }
}
