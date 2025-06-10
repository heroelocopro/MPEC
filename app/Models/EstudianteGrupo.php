<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstudianteGrupo extends Model
{
    protected $fillable = [
        'estudiante_id','grupo_id','colegio_id',
    ];
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class,'estudiante_id');
    }
    public function grupo()
    {
        return $this->belongsTo(Grupo::class,'grupo_id');
    }
    public function colegio()
    {
        return $this->belongsTo(Colegio::class,'colegio_id');
    }
}
