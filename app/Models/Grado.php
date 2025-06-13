<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $fillable = [
        'nombre',
        'colegio_id',
        'nivel',
        'descripcion',
        'edad_referencia',
        'estado',
    ];

    public function colegio()
    {
        return $this->belongsTo(Colegio::class,'colegio_id');
    }
    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }
    public function asignaturas()
    {
        return $this->belongsToMany(asignatura::class, 'asignatura_grados');
    }
}
