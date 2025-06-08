<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class asignatura extends Model
{
    protected $fillable = [
        'nombre','codigo','area','descripcion','colegio_id','grado_minimo','grado_maximo','carga_horaria','tipo','estado','color',
];

    public function colegio()
    {
        return $this->belongsTo(Colegio::class,'colegio_id');
    }
    public function profesores()
    {
        return $this->belongsToMany(Profesor::class,'asignatura_profesors');
    }
    public function grados()
    {
        return $this->hasMany(Grado::class);
    }
    public function asignaturaGrados()
    {
        return $this->hasMany(AsignaturaGrado::class);
    }
    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }
    public function examenes()
    {
        return $this->hasMany(Examen::class);
    }
    public function horarios()
    {
        return $this->hasMany(Horario::class,'asignatura_id','id');
    }

}
