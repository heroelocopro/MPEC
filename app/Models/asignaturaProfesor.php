<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class asignaturaProfesor extends Model
{
    protected $table = 'asignatura_profesors';
    protected $fillable = [
        'asignatura_id',
        'profesor_id',
    ];

    public function profesor()
    {
        return $this->belongsTo(Profesor::class,'profesor_id');
    }
    public function asignatura()
    {
        return $this->belongsTo(asignatura::class,'asignatura_id');
    }
}
