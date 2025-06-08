<?php

namespace App\Livewire\Estudiante;

use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use App\Models\nota;
use App\Models\NotaFinal;
use App\Models\PeriodoAcademico;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EstudianteNotas extends Component
{
    public $notaMinima = 3.5;
    public $colegio;
    public $estudiante;
    public $matricula;
    public $grado;
    public $grupo;
    public $periodos = [];
    public $periodoSeleccionado = null;
    public $periodoSeleccionadoObj;
    public $asignaturas = [];

    public function mount()
    {
        $this->estudiante = Estudiante::where('user_id', Auth::id())->first();
        $this->colegio = $this->estudiante->colegio;
        $this->matricula = $this->estudiante->matricula;
        $this->grado = $this->matricula->grado;
        $this->grupo = EstudianteGrupo::where('estudiante_id', $this->estudiante->id)->first()->grupo;

        $this->periodos = PeriodoAcademico::where('colegio_id', $this->colegio->id)->orderBy('fecha_inicio', 'asc')->get();
        $this->periodoSeleccionadoObj = PeriodoAcademico::periodoActual($this->colegio->id);
        $this->periodoSeleccionado = PeriodoAcademico::periodoActual($this->colegio->id)->id;

        $this->actualizarNotas();
    }

    public function updatedPeriodoSeleccionado()
    {
        $this->actualizarNotas();
    }

    public function actualizarNotas()
    {
        $this->asignaturas = NotaFinal::with('asignatura')
            ->where('estudiante_id', $this->estudiante->id)
            ->where('periodo_id', $this->periodoSeleccionado)
            ->get()
            ->map(function ($nota) {
                return (object)[
                    'nombre' => $nota->asignatura->nombre,
                    'nota_final' => $nota->nota,
                ];
            });
    }

    public function descargarNotas()
    {
        return redirect()->route('estudiante.notas.pdf', ['periodo' => $this->periodoSeleccionado]);
    }
    public function render()
    {
        return view('livewire.estudiante.estudiante-notas');
    }
}
