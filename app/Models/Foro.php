<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foro extends Model
{
    protected $fillable = [
        'colegio_id',
        'titulo',
        'contenido',
        'autor_id',
        'tipo_autor',
        'tipo',
        'grupo_id',
        'grado_id',
    ];

    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta_Foro::class);
    }
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }
    // otros
    public function getTotalComentariosAttribute()
    {
        return $this->respuestas()->count();
    }
    public function getAutorAttribute()
    {
        $tipoAutor = $this->tipo_autor;
        $autorId = $this->autor_id;
        switch ($tipoAutor) {
            case 'colegio':
                return Colegio::find($autorId);
                break;
            case 'docente':
                return Profesor::find($autorId);
                break;
            case 'estudiante':
                return Estudiante::find($autorId);
                break;
            default:
                # code...
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
