<?php

namespace App\Livewire\Estudiante;

use App\Models\AsignaturaGrado;
use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use App\Models\Examen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EstudianteExamenes extends Component
{
    public $verModal = false;
    public $examenModal;
    // datos basicos del colegio,estudiante
    public $colegio;
    public $estudiante;
    public $matricula;
    public $grado;
    public $grupo;
    public $asignaturas = [];
    public $examenesAsignaturas = [
        [
            'nombre',
            'examen'
        ],
    ];
    public $cargandoExamen;

    public $examenHecho = false;

    public function cargarExamenes()
    {
        $grupoFiltrado = $this->grupo->id;
        foreach ($this->asignaturas as $index => $asignatura) {
            // Filtrar examenes por grupo_id y ordenarlas por fecha de entrega
            $examenesFiltrados = $asignatura->examenes
            ->filter(function ($actividad) use ($grupoFiltrado) {
                return $actividad->grupo_id === $grupoFiltrado &&
                    Carbon::now()->lt(Carbon::parse($actividad->fecha_vencimiento));
            })
            ->sortBy('fecha_vencimiento')
            ->values();
            // Guardar nombre de la asignatura y sus actividades filtradas
            $this->examenesAsignaturas[$index]['0'] = $asignatura->nombre;
            $this->examenesAsignaturas[$index]['1'] = $examenesFiltrados;
        }
    }

    public function dejar()
    {
        $this->cargandoExamen = false;
    }

    public function examenHecho($id)
    {
        $examen = Examen::findOrFail($id);
        $this->examenHecho = false;

        foreach ($examen->preguntas as $pregunta) {
            foreach ($pregunta->respuestas as $respuesta) {
                if ($respuesta->estudiante_id == $this->estudiante->id) {
                    $this->examenHecho = true;
                    break 2; // sal del bucle anidado si ya se encontró una respuesta del estudiante
                }
            }
        }
    }


    public function cargarExamen($id)
    {
        $this->cargandoExamen = true;
        $this->examenModal = Examen::findOrFail($id);
        $this->examenHecho($id);
        $this->dejar();
    }

    public function iniciarExamen($id)
    {
        return redirect()->route('estudiante-ver-examen', ['id' => $id]);
    }

    public function cargarAsignaturas()
    {
        // obtenemos todas las asignaturas del grado del estudiante
        $asignaturasGrados = AsignaturaGrado::where('grado_id',$this->grado->id)->get();
        foreach($asignaturasGrados as $asignatura)
        {
            array_push($this->asignaturas,$asignatura->asignatura);
        }
    }

    public function mount()
    {
        // datos basicos del estudiante
        $this->estudiante = Estudiante::where('user_id',Auth::user()->id)->first();
        $this->colegio = $this->estudiante->colegio;
        $this->matricula = $this->estudiante->matricula;
        $this->grado = $this->matricula->grado;
        $this->grupo = EstudianteGrupo::where('estudiante_id',$this->estudiante->id)->first()->grupo;
        // cargar metodos
        $this->cargarAsignaturas();
        $this->cargarExamenes();

    }

    public function render()
    {
        return view('livewire.estudiante.estudiante-examenes');
    }
}
