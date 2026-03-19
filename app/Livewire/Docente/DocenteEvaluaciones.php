<?php

namespace App\Livewire\Docente;

use App\Models\asignatura;
use App\Models\configNota;
use App\Models\Examen;
use App\Models\PeriodoAcademico;
use App\Models\Pregunta_Examen;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocenteEvaluaciones extends Component
{
    // ---------- Validaciones principales ----------
    protected $rules = [
        'titulo'           => 'required|string|max:255',
        'asignatura_id'    => 'required|exists:asignaturas,id',
        'grupo_id'         => 'required|exists:grupos,id',
        'puntaje_total'    => 'required|numeric|min:1',
        'fecha_vencimiento'=> 'required|date|after:today',
        'tiempo_limite'    => 'required|integer|min:1', // minutos
        'profesor_id'      => 'required|exists:profesores,id',
    ];

    // ---------- Variables generales ----------
    public $nota_minima;
    public $nota_maxima;
    public $colegio;
    public $profesor;
    public $grados = [];
    public $subjects = [];
    public $groups = [];
    public $questions = [];

    // ---------- Datos del examen ----------
    public $titulo = '';
    public $asignatura_id = '';
    public $grupo_id = '';
    public $fecha_vencimiento = '';
    public $tiempo_limite = 60;
    public $puntaje_total = 5;
    public $profesor_id = null;

    // ============================================================
    // ============= MÉTODOS DE MANEJO DE PREGUNTAS ===============
    // ============================================================

    public function addQuestion()
    {
        // agrega una pregunta con estructura base
        $this->questions[] = [
            'text'            => '',
            'type'            => 'multiple_choice',
            'points'          => 1,
            'options'         => ['', ''],
            'correct_option'  => 0,
            'correct_answer'  => true,
        ];
    }

    public function removeQuestion($index)
    {
        if (isset($this->questions[$index])) {
            unset($this->questions[$index]);
            $this->questions = array_values($this->questions); // reindexar
        }
    }

    public function addOption($questionIndex)
    {
        if (isset($this->questions[$questionIndex])) {
            $this->questions[$questionIndex]['options'][] = '';
        }
    }

    public function removeOption($questionIndex, $optionIndex)
    {
        if (
            isset($this->questions[$questionIndex]) &&
            isset($this->questions[$questionIndex]['options'][$optionIndex])
        ) {
            unset($this->questions[$questionIndex]['options'][$optionIndex]);
            $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);

            // ajustar opción correcta si el índice queda fuera de rango
            if ($this->questions[$questionIndex]['correct_option'] >= count($this->questions[$questionIndex]['options'])) {
                $this->questions[$questionIndex]['correct_option'] = 0;
            }
        }
    }

    // ============================================================
    // ==================== GUARDAR EXAMEN ========================
    // ============================================================

    public function saveExam()
    {
        $this->validate();

        // Validación extra: verificar profesor y colegio
        if (!$this->profesor || !$this->colegio) {
            session()->flash('error', 'Error: No se pudo identificar el profesor o el colegio.');
            return;
        }

        // Verificar periodo académico actual
        $periodo = PeriodoAcademico::periodoActual($this->colegio->id);
        if (!$periodo || !isset($periodo->id)) {
            session()->flash('error', 'No se encontró un periodo académico activo.');
            return;
        }

        // Crear el examen principal
        $exam = Examen::create([
            'titulo'           => $this->titulo,
            'asignatura_id'    => $this->asignatura_id,
            'grupo_id'         => $this->grupo_id,
            'fecha_vencimiento'=> $this->fecha_vencimiento,
            'tiempo_limite'    => gmdate('H:i:s', $this->tiempo_limite * 60),
            'puntaje_total'    => $this->puntaje_total,
            'profesor_id'      => $this->profesor->id,
            'periodo_id'       => $periodo->id,
        ]);

        $counter = 0;
        foreach($this->questions as $q){
            if($q['points'] == 1){
                $counter++;
            }
        }
        if( $counter == count($this->questions)){
                foreach($this->questions as &$q){
                    $q['points'] = $this->puntaje_total / $counter;
                }
                unset($q);   
            }
        

        // Crear las preguntas asociadas
        foreach ($this->questions as $questionData) {
            $opciones = [];
            $respuestaCorrecta = null;

            switch ($questionData['type']) {
                case 'multiple_choice':
                    foreach ($questionData['options'] as $index => $optionText) {
                        $opciones[] = $optionText;
                        if ($index == $questionData['correct_option']) {
                            $respuestaCorrecta = $optionText;
                        }
                    }
                    break;

                case 'true_false':
                    $opciones = ['Verdadero', 'Falso'];
                    $respuestaCorrecta = $questionData['correct_answer'] ? 'Verdadero' : 'Falso';
                    break;

                default:
                    // tipos essay / short_answer no tienen opciones
                    $opciones = null;
                    $respuestaCorrecta = null;
                    break;
            }


            Pregunta_Examen::create([
                'examen_id'          => $exam->id,
                'pregunta'           => $questionData['text'],
                'tipo'               => $questionData['type'],
                'opciones'           => $opciones,
                'respuesta_correcta' => $respuestaCorrecta,
                'puntos'             => $questionData['points'],
            ]);
        }

        // Notificar a estudiantes (si existen)
        if ($exam->grupo && $exam->grupo->estudiantes) {
            foreach ($exam->grupo->estudiantes as $estudiante) {
                if (!empty($estudiante->usuario) && method_exists($estudiante->usuario, 'notify')) {
                    $estudiante->usuario->notify(new \App\Notifications\NuevoExamenNotification($exam));
                }
            }
        }

        // Redirigir con mensaje

         $this->dispatch('alerta', [
            'title' => 'Evaluacion creada con exito',
            'text' => '¡Se guardó correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
        ]);

        return redirect()->route('docente-evaluaciones');
    }

    // ============================================================
    // ==================== ASIGNATURAS Y GRUPOS ==================
    // ============================================================

    public function updatedAsignaturaId($value)
    {
        $this->groups = [];

        if (empty($value)) {
            return;
        }

        $asignatura = asignatura::with('asignaturaGrados.grado.grupos')->find($value);

        if ($asignatura && $asignatura->asignaturaGrados) {
            foreach ($asignatura->asignaturaGrados as $grado) {
                if ($grado->grado && $grado->grado->grupos) {
                    foreach ($grado->grado->grupos as $grupo) {
                        $this->groups[] = $grupo;
                    }
                }
            }
        }

        // eliminar duplicados y reindexar
        $this->groups = collect($this->groups)->unique('id')->values()->all();
    }

    // ============================================================
    // ========================== MOUNT ===========================
    // ============================================================

    public function mount()
    {
        // obtener el profesor autenticado
        $this->profesor = Profesor::where('user_id', Auth::id())->first();

        // si no hay profesor, definimos valores vacíos seguros
        if (!$this->profesor) {
            $this->profesor_id = null;
            $this->colegio = (object)['id' => null];
            $this->subjects = [];
            $this->nota_minima = 0;
            $this->nota_maxima = 5;
            return;
        }

        $this->profesor_id = $this->profesor->id;
        $this->colegio = $this->profesor->colegio ?? (object)['id' => null];
        $this->subjects = $this->profesor->asignaturas ?? [];


        // configuración de notas
        $configNota = configNota::where('colegio_id', $this->colegio->id)->first();
        $this->nota_minima = $configNota->nota_minima ?? 1;
        $this->nota_maxima = $configNota->nota_maxima ?? 5;

        // puntaje total por defecto igual a nota máxima
        $this->puntaje_total = $this->nota_maxima;
    }

    // ============================================================
    // ========================= RENDER ===========================
    // ============================================================

    public function render()
    {
        return view('livewire.docente.docente-evaluaciones');
    }
}
