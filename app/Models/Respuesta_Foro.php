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
        public function getAutorAttribute()
    {
        $tipoAutor = $this->tipo_autor;
        $autorId = $this->autor_id;
        switch ($tipoAutor) {
            case 'colegio':
                return Colegio::where('user_id',$autorId)->first();
                break;
            case 'docente':
                return Profesor::where('user_id',$autorId)->first();
                break;
            case 'estudiante':
                return Estudiante::where('user_id',$autorId)->first();
                break;
            default:
                return "hola";
                break;
        }
    }
        // Scope: más recientes
    public function scopeMasRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Scope: más antiguos
    public function scopeMasAntiguos($query)
    {
        return $query->orderBy('created_at', 'asc');
    }
}
