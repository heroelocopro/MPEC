<?php

namespace App\Livewire\Estudiante;

use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use App\Models\Examen;
use App\Models\nota;
use App\Models\PeriodoAcademico;
use App\Models\Respuesta_Examen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EstudianteVerExamen extends Component
{
    public $examenHecho;
    public $examen, $preguntas = [], $respuestas = [], $inicio;
    public $colegio;
    public $asignatura;
    public $estudiante;
    public $matricula;
    public $grado;
    public $grupo;
    public $usuario;
    public $contador = '00:00:00';
    public $tiempoLimiteSegundos;

    public function actualizarContador()
    {
        $ahora = Carbon::now();

        // CORRECCIÓN IMPORTANTE: El orden de los parámetros en diffInSeconds es crucial
        $transcurridos = $this->inicio->diffInSeconds($ahora);

        $restante = $this->tiempoLimiteSegundos - $transcurridos;

        if ($restante <= 0) {
            $this->contador = '00:00:00';
            $this->enviarRespuestas();
        } else {
            $horas = floor($restante / 3600);
            $minutos = floor(($restante % 3600) / 60);
            $segundos = $restante % 60;

            $this->contador = sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
        }
    }

    public function examenHecho($id)
    {
        $examen = Examen::findOrFail($id);
        $this->examenHecho = false;

        foreach ($examen->preguntas as $pregunta) {
            foreach ($pregunta->respuestas as $respuesta) {
                if ($respuesta->estudiante_id == $this->estudiante->id) {
                    $this->examenHecho = true;
                    return redirect()->route('estudiante-examenes');
                }
            }
        }

        // Si no se encontró ninguna respuesta, puedes redirigir o hacer otra acción
        // return redirect()->route('estudiante-examenes'); // opcional
    }

    public function cargarPuntaje()
    {

        $notableClass  = \App\Models\Examen::class;
        $nota = $this->examen->puntajeObtenidoPorEstudiante($this->estudiante->id);
        $periodo = PeriodoAcademico::periodoActual($this->colegio->id)->id;
        nota::updateOrCreate(
            [
                'grupo_id' => $this->grupo->id,
                'estudiante_id' => $this->estudiante->id,
                'asignatura_id' => $this->asignatura->id,
                'notable_id' => $this->examen->id,
                'notable_type' => $notableClass,
                'periodo_id' => $periodo,
                'ano' => now()->format('Y'),
            ],
            [
                'valor' => $nota,
            ]
        );
    }


    public function enviarRespuestas()
    {
        $this->validate([
            'respuestas' => 'required|array|min:' . count($this->preguntas),
        ]);

        foreach ($this->respuestas as $preguntaId => $opcionId) {
            Respuesta_Examen::create([
                'estudiante_id' => $this->estudiante->id,
                'pregunta_id' => $preguntaId,
                'respuesta' => $opcionId,
            ]);
        }
        $this->cargarPuntaje();
        $this->dispatch('alerta', [
            'title' => 'Examen Finalizado',
            'text' => '¡Se finalizo correctamente!',
            'icon' => 'success',
            'toast' => true,
            'position' => 'top-end',
        ]);
        return redirect()->route('estudiante-inicio');
    }

    public function mount($id)
    {
        $this->usuario = Auth::user() ?? null;
        $this->estudiante = Estudiante::where('user_id', $this->usuario->id)->first() ?? null;
        $this->colegio = $this->estudiante->colegio ?? null;
        $this->matricula = $this->estudiante->matricula ?? null;
        $this->grado = $this->matricula->grado ?? null;
        $this->grupo = EstudianteGrupo::where('estudiante_id', $this->estudiante->id)->first()->grupo ?? null;
        $this->examen = Examen::findOrFail($id) ?? null;
        $this->asignatura = $this->examen->asignatura ?? null;
        $this->preguntas = $this->examen->preguntas ?? null;

        // Convertir tiempo límite a segundos
        $tiempoParts = explode(':', $this->examen->tiempo_limite);
        $this->tiempoLimiteSegundos = ($tiempoParts[0] * 3600) + ($tiempoParts[1] * 60) + $tiempoParts[2];

        $claveInicio = "inicio_examen_{$this->examen->id}_usuario_{$this->usuario->id}";

        if (!session()->has($claveInicio)) {
            session()->put($claveInicio, Carbon::now());
        }

        $this->inicio = Carbon::parse(session($claveInicio));

        // Si por algún error la fecha de inicio es futura, la corregimos
        if ($this->inicio->isFuture()) {
            $this->inicio = Carbon::now();
            session()->put($claveInicio, $this->inicio);
        }

        $this->actualizarContador();
        $this->examenHecho($id);
    }

    public function render()
    {
        return view('livewire.estudiante.estudiante-ver-examen');
    }
}
