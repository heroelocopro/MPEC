<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $fillable = [
        'estudiante_id',
        'grupo_id',
        'colegio_id',
        'asignatura_id',
        'profesor_id',
        'fecha',
        'bloque',
        'estado',
        'justificacion',
    ];

    // Relaciones

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }
}
