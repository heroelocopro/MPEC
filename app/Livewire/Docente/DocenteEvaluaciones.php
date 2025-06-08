<?php

namespace App\Livewire\Docente;

use App\Models\asignatura;
use App\Models\Colegio;
use App\Models\configNota;
use App\Models\Examen;
use App\Models\Grupo;
use App\Models\PeriodoAcademico;
use App\Models\Pregunta_Examen;
use App\Models\Profesor;
use App\Notifications\NuevoExamenNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DocenteEvaluaciones extends Component
{
    protected $rules = [
    'titulo' => 'required|string|max:255',
    'asignatura_id' => 'required|exists:asignaturas,id',
    'grupo_id' => 'required|exists:grupos,id',
    'puntaje_total' => 'required|numeric|min:1',
    'fecha_vencimiento' => 'required|date|after:today',
    'tiempo_limite' => 'required|integer|min:1', // minutos, por ejemplo
    'profesor_id' => 'required|exists:profesores,id',
    ];

    public $nota_minima;
    public $nota_maxima;


    public $colegio;
    public $profesor;
    public $grados = [];
    public $subjects = [];
    public $groups = [];
    public $questions = [];

    // examen variables
    public $titulo = '';
    public $asignatura_id = '';
    public $grupo_id = '';
    public $fecha_vencimiento = '';
    public $tiempo_limite = 60;
    public $puntaje_total = 5;
    public $profesor_id = '';

    public function addQuestion()
    {
        $this->questions[] = [
            'text' => '',
            'type' => 'multiple_choice',
            'points' => 1,
            'options' => ['', ''],
            'correct_option' => 0,
            'correct_answer' => true,
        ];
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function addOption($questionIndex)
    {
        $this->questions[$questionIndex]['options'][] = '';
    }

    public function removeOption($questionIndex, $optionIndex)
    {
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);

        // Ajustar la opción correcta si es necesario
        if ($this->questions[$questionIndex]['correct_option'] >= count($this->questions[$questionIndex]['options'])) {
            $this->questions[$questionIndex]['correct_option'] = 0;
        }
    }

    public function saveExam()
    {
        $this->validate();

        // Crear el examen
        $exam = Examen::create([
            'titulo' => $this->titulo,
            'asignatura_id' => $this->asignatura_id,
            'grupo_id' => $this->grupo_id,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'tiempo_limite' => gmdate('H:i:s', $this->tiempo_limite * 60),
            'puntaje_total' => $this->puntaje_total,
            'profesor_id' => $this->profesor->id,
            'periodo_id' => PeriodoAcademico::periodoActual($this->colegio->id)->id,
        ]);

       // Crear las preguntas
    foreach ($this->questions as $questionData) {
        $opciones = [];
        $respuestaCorrecta = null;

        if ($questionData['type'] === 'multiple_choice') {
            // Preparar opciones para selección múltiple
            foreach ($questionData['options'] as $index => $optionText) {
                $opciones[] = $optionText;
                if ($index == $questionData['correct_option']) {
                    $respuestaCorrecta = $optionText;
                }
            }
        } elseif ($questionData['type'] === 'true_false') {
            // Preparar opciones para verdadero/falso
            $opciones = ['Verdadero', 'Falso'];
            $respuestaCorrecta = $questionData['correct_answer'] ? 'Verdadero' : 'Falso';
        }

        Pregunta_Examen::create([
            'examen_id' => $exam->id,
            'pregunta' => $questionData['text'],
            'tipo' => $questionData['type'],
            'opciones' => $questionData['type'] === 'essay' || $questionData['type'] === 'short_answer' ? null : $opciones,
            'respuesta_correcta' => $questionData['type'] === 'essay' ? null : $respuestaCorrecta,
            'puntos' => $questionData['points'],
        ]);
    }
        $estudiantes = $exam->grupo->estudiantes;
        foreach ($estudiantes as $estudiante) {
            // Verifica que la relación existe y es una instancia de User
            if ($estudiante->usuario instanceof \App\Models\User) {
                $estudiante->usuario->notify(new \App\Notifications\NuevoExamenNotification($exam));
            }
        }
        return redirect()->route('docente-evaluaciones');
    }

    public function updatedAsignaturaId($value)
    {
        if($value != '' || $value != null)
        {
            $this->groups = [];
            $asignatura = asignatura::findOrFail($value);
            foreach($asignatura->asignaturaGrados as $grado)
            {
                foreach($grado->grado->grupos as $grupo)
                {
                    array_push($this->groups,$grupo);
                }
            }
        }else {
            $this->groups = [];
        }
    }

    public function mount()
    {
        $this->profesor = Profesor::where('user_id',Auth::user()->id)->first();
        $this->profesor_id = $this->profesor->id;
        $this->colegio = $this->profesor->colegio;
        $this->subjects = $this->profesor->asignaturas;
        $configNota = configNota::where('colegio_id',$this->colegio->id)->first();
        $this->nota_minima = $configNota->nota_minima;
        $this->nota_maxima = $configNota->nota_maxima;
        $this->puntaje_total = $this->nota_maxima;
    }
    public function render()
    {
        return view('livewire.docente.docente-evaluaciones');
    }
}
