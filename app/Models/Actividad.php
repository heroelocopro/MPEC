<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table = 'actividades';
    protected $fillable = ['profesor_id','asignatura_id','grupo_id', 'titulo', 'descripcion', 'fecha_entrega','archivo','periodo_id'];

    public function profesor()
    {
        return $this->belongsTo(Profesor::class,'profesor_id');
    }
    public function grupo()
    {
        return $this->belongsTo(Grupo::class,'grupo_id');
    }

    public function respuestas()
    {
        return $this->hasMany(respuesta_actividad::class);
    }
    public function notas()
    {
        return $this->morphMany(nota::class, 'notable');
    }
    public function asignatura()
    {
        return $this->belongsTo(asignatura::class);
    }
    public function periodo()
    {
        return $this->belongsTo(PeriodoAcademico::class);
    }
}
