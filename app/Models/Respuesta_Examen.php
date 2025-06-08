<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respuesta_Examen extends Model
{
    protected $table = 'respuestas__examenes';
    protected $fillable = ['pregunta_id', 'estudiante_id', 'respuesta'];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta_Examen::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
}
