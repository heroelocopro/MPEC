<?php

namespace App\Livewire\Docente;

use App\Models\Actividad;
use App\Models\configNota;
use App\Models\nota;
use App\Models\PeriodoAcademico;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocenteVerRespuestasActividades extends Component
{
    public $nota_minima;
    public $nota_maxima;
    public $notas = [];
    public $notableClass = 'App\Models\Actividad';
    public $grupoid;
    public $asignaturaId;
    public $actividadId;

    public function subirNota()
    {
        try {
            $errores = [];

            // Validar todas las notas primero
            foreach ($this->notas as $index => $nota) {
                if (!is_numeric($nota) || $nota < $this->nota_minima || $nota > $this->nota_maxima) {
                    $errores[] = "Nota inválida para el índice $index. Debe estar entre ". $this->nota_minima. " y ". $this->nota_maxima;
                }
            }

            // Si hay errores, lanzar alerta y detener
            if (!empty($errores)) {
                $this->dispatch('alerta', [
                    'title' => 'Errores en notas',
                    'text' => implode(' ', $errores),
                    'icon' => 'error',
                    'toast' => true,
                    'position' => 'top-end',
                ]);
                return;
            }

            // Si todo está bien, guardar
            foreach ($this->notas as $index => $nota) {
                [$estudianteId, $actividadId] = explode('_', $index);

                Nota::updateOrCreate(
                    [
                        'grupo_id' => $this->grupoid,
                        'estudiante_id' => $estudianteId,
                        'asignatura_id' => $this->asignaturaId,
                        'notable_id' => $actividadId,
                        'notable_type' => $this->notableClass,
                        'periodo_id' => PeriodoAcademico::periodoActual($this->colegio->id)->id,
                        'ano' => now()->format('Y'),
                    ],
                    [
                        'valor' => $nota,
                    ]
                );
            }

            $this->dispatch('alerta', [
                'title' => 'Notas guardadas',
                'text' => 'Todas las notas se guardaron correctamente.',
                'icon' => 'success',
                'toast' => true,
                'position' => 'top-end',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('alerta', [
                'title' => 'Error al guardar',
                'text' => $th->getMessage(),
                'icon' => 'error',
                'toast' => true,
                'position' => 'top-end',
            ]);
        }
    }



    public function updatedNotas($value, $path)
    {
        // El path ahora será algo como "123_45" (estudiante_id_actividad_id)
        [$estudianteId, $actividadId] = explode('_', $path);

        // Obtener el valor directamente del array notas
        $nota = $value; // Esto ya contiene el valor actualizado

    }


    // datos normales

    public $profesor;
    public $colegio;
    public $periodo;
    public $actividad;
    public $asignatura;
    public $grupo;
    public $respuestas = [];

    public function cargarNotas()
    {
        foreach ($this->respuestas as $respuesta) {
            if ($respuesta->nota) {
                $key = $respuesta->estudiante_id . '_' . $respuesta->actividad_id;
                $this->notas[$key] = $respuesta->nota->valor;
            }
        }
    }


    public function mount($id)
    {
        // Obtenemos al profesor, colegio, periodo y cargamos examen y preguntas
        $this->profesor = Profesor::where('user_id', Auth::user()->id)->first();
        $this->colegio = $this->profesor->colegio;
        $this->periodo = PeriodoAcademico::periodoActual($this->colegio->id);
        $this->actividad = Actividad::findOrFail($id);
        $this->actividadId = $this->actividad->id;
        $this->asignatura = $this->actividad->asignatura;
        $this->asignaturaId = $this->asignatura->id;
        $this->grupo = $this->actividad->grupo;
        $this->grupoid = $this->grupo->id;
        $this->respuestas = $this->actividad->respuestas;
        // xd
        $configNota = configNota::where('colegio_id',$this->colegio->id)->first();
        $this->nota_minima = $configNota->nota_minima ?? 0;
        $this->nota_maxima = $configNota->nota_maxima ?? 5;
        $this->cargarNotas();
    }

    public function render()
    {
        return view('livewire.docente.docente-ver-respuestas-actividades');
    }
}
