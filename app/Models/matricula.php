<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class matricula extends Model
{
    protected $casts = [
        'fecha_matricula' => 'datetime',
    ];
    protected $fillable = [
        'estudiante_id',
        'colegio_id',
        'sede_id',
        'grado_id',
        'tipo_matricula',
        'estado',
        'fecha_matricula',
    ];
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class,'estudiante_id');
    }
    public function colegio()
    {
        return $this->belongsTo(Colegio::class,'colegio_id');
    }
    public function sede()
    {
        return $this->belongsTo(sedes_colegio::class,'sede_id');
    }
    public function grado()
    {
        return $this->belongsTo(Grado::class,'grado_id');
    }
    public function scopeActivas($query) {
        return $query->where('estado', 'activo');
    }
}
