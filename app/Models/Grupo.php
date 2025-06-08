<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = [
        'nombre',
        'colegio_id',
        'grado_id'
    ];

    public function colegio()
    {
        return $this->belongsTo(Colegio::class,'colegio_id');
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class,'grado_id');
    }

    // Grupo.php
    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'estudiante_grupos', 'grupo_id', 'estudiante_id');
    }

}
