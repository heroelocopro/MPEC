<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaFinal extends Model
{
    protected $fillable = [
        'nota',
        'ano',
        'periodo_id',
        'estudiante_id',
        'colegio_id',
        'asignatura_id',
        'grupo_id',
    ];

    public function periodo()
    {
        return $this->belongsTo(PeriodoAcademico::class);
    }
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }
    public function asignatura()
    {
        return $this->belongsTo(asignatura::class);
    }
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

}
