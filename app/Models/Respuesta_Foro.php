<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respuesta_Foro extends Model
{
    protected $table = 'respuestas__foros';
    protected $fillable = ['foro_id', 'autor_id', 'tipo_autor', 'mensaje'];

    public function foro()
    {
        return $this->belongsTo(Foro::class);
    }
}
