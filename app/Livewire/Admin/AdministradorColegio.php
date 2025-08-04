<?php

namespace App\Livewire\Admin;

use App\Models\Colegio;
use Livewire\Component;

class AdministradorColegio extends Component
{
    public Colegio $colegio;

    // Estadísticas
    public int $totalEstudiantes;
    public int $totalEstudiantesPrimaria;
    public int $totalEstudiantesSecundaria;
    public int $totalMatriculados;
    public int $totalProfesores;
    public int $totalMaterias;
    public int $totalGrupos;
    public int $totalGrados;
    public int $totalAsistencias;
    public float $promedioInferiores;
    public float $promedioSuperiores;

    public function mount($id)
    {
        $this->colegio = Colegio::findOrFail($id);
        $this->cargarEstadisticas();
    }

    protected function cargarEstadisticas(): void
    {
        $this->totalEstudiantes = $this->colegio->estudiantes()->count();
        $this->totalMatriculados = $this->colegio->matriculas()->count();
        $this->totalProfesores = $this->colegio->profesores()->count();
        $this->totalMaterias = $this->colegio->materias()->count();
        $this->totalGrupos = $this->colegio->grupos()->count();
        $this->totalGrados = $this->colegio->grados()->count();
        $this->totalAsistencias = $this->colegio->asistencias()->count();

        $this->totalEstudiantesPrimaria = $this->colegio->matriculas()
            ->whereHas('grado', fn($q) => $q->where('nivel', 'primaria'))
            ->activas()
            ->count();

            $this->totalEstudiantesSecundaria = $this->colegio->matriculas()
            ->whereHas('grado', fn($q) => $q->whereIn('nivel', ['secundaria', 'media']))
            ->activas()
            ->count();





        $this->promedioInferiores = round(
            $this->colegio->notas()
                ->whereHas('grupo.grado', fn($q) => $q->where('nivel', '=', 'primaria'))
                ->avg('nota') ?? 0,
            2
        );

        $this->promedioSuperiores = round(
            $this->colegio->notas()
                ->whereHas('grupo.grado', fn($q) => $q->whereIn('nivel', ['secundaria','media']))
                ->avg('nota') ?? 0,
            2
        );
    }

    public function render()
    {
        return view('livewire.admin.administrador-colegio');
    }
}
