<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignaturaGrado extends Model
{
    protected $table = 'asignatura_grados';
    protected $fillable = ['asignatura_id','grado_id'];

    public function asignatura()
    {
        return $this->belongsTo(asignatura::class,'asignatura_id');
    }
    public function grado()
    {
        return $this->belongsTo(Grado::class,'grado_id');
    }
}
