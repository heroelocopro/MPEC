<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class respuesta_actividad extends Model
{
    protected $table = 'respuestas_actividades';
    protected $fillable = ['actividad_id', 'estudiante_id', 'contenido', 'archivo'];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
    public function nota()
    {
        return $this->hasOne(Nota::class, 'notable_id', 'actividad_id')
            ->where('estudiante_id', $this->estudiante_id);
    }

}
