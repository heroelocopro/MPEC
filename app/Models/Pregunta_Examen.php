<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta_Examen extends Model
{
     protected $table = 'preguntas__examenes';
     protected $fillable = ['examen_id', 'pregunta', 'tipo', 'opciones', 'respuesta_correcta','puntos'];

    protected $casts = ['opciones' => 'array'];

    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta_Examen::class,'pregunta_id','id');
    }

        // Método helper para obtener opciones (para compatibilidad con la vista)
        public function getOptionsAttribute()
        {
            if ($this->tipo === 'true_false' && empty($this->opciones)) {
                return ['Verdadero', 'Falso'];
            }
            return $this->opciones ?? [];
        }

        // Método helper para verificar si una opción es correcta
        public function isCorrectOption($option)
        {
            return $option === $this->respuesta_correcta;
        }
}
