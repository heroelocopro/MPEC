<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $fillable = [
        'grupo_id','colegio_id', 'asignatura_id', 'profesor_id', 'dia', 'hora_inicio', 'hora_fin'
    ];

    public function grupo() {
        return $this->belongsTo(Grupo::class);
    }

    public function asignatura() {
        return $this->belongsTo(Asignatura::class);
    }

    public function profesor() {
        return $this->belongsTo(Profesor::class);
    }
}
