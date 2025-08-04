<?php

namespace App\Livewire\Docente;

use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Grupo;
use App\Models\PeriodoAcademico;
use Livewire\Component;

class DocenteGrupo extends Component
{
    // variables iniciales para datos
    public $grupo;
    public $colegio;
    // variables para estadisticas
    public $estudiantes;
    public $tablaEstudiantes;
    public $promedioNotas;
    public $estudiantesEspeciales;
    public $diasTotales;
    public $diasAsistidos;
    // modal
    public $estudianteSeleccionado;
    public $modalVer;

    public function verEstudiante($id)
    {
        $this->modalVer = true;
        $this->estudianteSeleccionado = Estudiante::findOrFail($id);
    }
    public function mount($id)
    {
        // datos iniciales
        $this->grupo = Grupo::findOrFail($id);
        $this->colegio = $this->grupo->colegio;
        // datos estadisticos
        $this->estudiantes = $this->grupo->estudiantes->count();
        $this->tablaEstudiantes = $this->grupo->estudiantes;
        $this->promedioNotas = $this->grupo->promedioNotas();
        $this->estudiantesEspeciales = count($this->grupo->estudiantesEspeciales());
        $this->diasTotales = PeriodoAcademico::diasTotales($this->colegio->id);
        $this->diasAsistidos = Asistencia::where('colegio_id',$this->colegio->id)->where('grupo_id',$this->grupo->id)->count();
        $this->modalVer = false;
    }
    public function render()
    {
        return view('livewire.docente.docente-grupo');
    }
}
