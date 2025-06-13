<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $table = 'examenes';
    protected $fillable = [
        'titulo',
        'instrucciones',
        'puntaje_total',
        'tiempo_limite',
        'fecha_vencimiento',
        'asignatura_id',
        'grupo_id',
        'profesor_id',
        'periodo_id',
    ];

    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta_Examen::class);
    }
    public function notas()
    {
        return $this->morphMany(nota::class, 'notable');
    }
    public function asignatura()
    {
        return $this->belongsTo(asignatura::class);
    }
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
    public function periodo()
    {
        return $this->belongsTo(PeriodoAcademico::class);
    }

    public function puntajeObtenidoPorEstudiante($estudianteId)
    {
        $preguntas = $this->preguntas()->with(['respuestas' => function ($query) use ($estudianteId) {
            $query->where('estudiante_id', $estudianteId);
        }])->get();

        $numPreguntas = $preguntas->count();

        if ($numPreguntas === 0) {
            return 0; // Evita división por cero
        }

        // $puntajePorPregunta = $this->puntaje_total / $numPreguntas;

        $puntajeTotal = 0;

        foreach ($preguntas as $pregunta) {
            $respuesta = $pregunta->respuestas->first();
            $puntajePorPregunta = $pregunta->puntos;
            if ($respuesta && $respuesta->respuesta === $pregunta->respuesta_correcta) {
                $puntajeTotal += $puntajePorPregunta;
            }
        }

        return $puntajeTotal;
    }

        public function yaRealizoExamen($estudianteId)
    {
        return $this->preguntas()
            ->whereHas('respuestas', function ($query) use ($estudianteId) {
                $query->where('estudiante_id', $estudianteId);
            })
            ->exists();
    }



}
