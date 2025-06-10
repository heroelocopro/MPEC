<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anuncio extends Model
{
    protected $fillable = [
        'titulo',
        'contenido',
        'imagen',
        'colegio_id',
        'anunciable_id',
        'anunciable_type',
    ];

    public function anunciable()
    {
        return $this->morphTo();
    }

    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }

}
