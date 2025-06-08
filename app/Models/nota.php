<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class nota extends Model
{
    protected $fillable = ['estudiante_id','asignatura_id','grupo_id','notable_id','notable_type','valor','periodo_id','ano'];
    public function notable()
    {
        return $this->morphTo();
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
    public function asignatura()
    {
        return $this->belongsTo(asignatura::class);
    }
    public function periodo()
    {
        return $this->belongsTo(PeriodoAcademico::class);
    }
}
