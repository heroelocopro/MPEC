<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foro extends Model
{
    protected $fillable = ['colegio_id', 'titulo', 'contenido', 'autor_id', 'tipo_autor'];

    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta_Foro::class);
    }
}
