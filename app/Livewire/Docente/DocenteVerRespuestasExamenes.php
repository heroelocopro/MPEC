<?php

namespace App\Livewire\Docente;

use App\Models\asignaturaProfesor;
use App\Models\Examen;
use App\Models\PeriodoAcademico;
use App\Models\Profesor;
use App\Models\Respuesta_Examen;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocenteVerRespuestasExamenes extends Component
{
    // variables iniciales
    public $profesor;
    public $colegio;
    public $asignaturas = [];
    public $periodo;
    public $examen;
    public $preguntas = [];
    public $respuestas = [];

    public function cargarRespuestas()
    {
        // Limpiamos para evitar duplicados
        $this->respuestas = [];

        $estudiantesUnicos = [];

        foreach ($this->preguntas as $pregunta) {
            foreach ($pregunta->respuestas as $respuesta) {
                $estudianteId = $respuesta->estudiante_id;

                // Si ya agregamos a este estudiante, lo ignoramos
                if (!isset($estudiantesUnicos[$estudianteId])) {
                    $estudiantesUnicos[$estudianteId] = $respuesta;
                }
            }
        }

        // Guardamos solo una respuesta por estudiante
        $this->respuestas = array_values($estudiantesUnicos);
    }

    public $mostrarModal = false;
    public $detalleEstudiante;
    public $detalleRespuestas = [];
    public $preguntasExamen = [];
    public $detalleExamen;
    public $preguntasRespuestas = [
        'preguntas' => [],
        'respuestas' => [],
    ];

    public function verDetalleRespuesta($respuesta_id)
    {
        $this->mostrarModal = true;

        $respuesta = Respuesta_Examen::findOrFail($respuesta_id);

        $this->detalleExamen = $respuesta->pregunta->examen;
        $this->detalleEstudiante = $respuesta->estudiante;
        $this->preguntasExamen = $this->detalleExamen->preguntas;

        $this->preguntasRespuestas = [];

        foreach ($this->preguntasExamen as $pregunta) {
            $respuestaEstudiante = $pregunta->respuestas()
                ->where('estudiante_id', $this->detalleEstudiante->id)
                ->first();

            $this->preguntasRespuestas[] = [
                'pregunta' => $pregunta,
                'respuesta' => $respuestaEstudiante
            ];
        }
    }



    public function mount($id)
    {
        // Obtenemos al profesor, colegio, periodo y cargamos examen y preguntas
        $this->profesor = Profesor::where('user_id', Auth::user()->id)->first();
        $this->colegio = $this->profesor->colegio;
        $this->periodo = PeriodoAcademico::periodoActual($this->colegio->id);
        $this->examen = Examen::findOrFail($id);
        $this->preguntas = $this->examen->preguntas;
        $this->preguntasExamen = $this->examen->preguntas;

        // Cargamos las respuestas de las preguntas
        $this->cargarRespuestas();
    }

    public function render()
    {
        return view('livewire.docente.docente-ver-respuestas-examenes');
    }
}
