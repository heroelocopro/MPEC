<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;
     protected static function booted()
    {
        static::created(function ($profesor) {
            $emailBase = self::generarEmail($profesor->nombre_completo);
            $emailFinal = self::asegurarEmailUnico($emailBase);

            $user =  User::create(['name' => $profesor->nombre_completo,'email' => $emailFinal."@agora.com",'password' => bcrypt('123456789'),'role_id' => 3]);
            $profesor->user_id = $user->id;
            $profesor->save();
        });
    }

    // Función para generar el email base
    protected static function generarEmail($texto)
    {
        $palabras = explode(' ', trim($texto));
        $iniciales = '';

        foreach ($palabras as $palabra) {
            if (!empty($palabra)) {
                $iniciales .= $palabra[0];
            }
        }

        $iniciales = self::limpiarTexto($iniciales);

        return strtolower($iniciales);
    }

    // Función para limpiar texto (quitar tildes, ñ, caracteres raros)
    protected static function limpiarTexto($texto)
    {
        $texto = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'],
            ['a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'n', 'n'],
            $texto
        );

        // Eliminar todo lo que no sea letra o número
        $texto = preg_replace('/[^A-Za-z0-9]/', '', $texto);

        return strtolower($texto);
    }

    // Función para asegurar que el email sea único
    protected static function asegurarEmailUnico($emailBase)
    {
        $email = $emailBase . rand(100, 999); // Inicialmente agregamos random
        $contador = 1;

        while (User::where('email', $email . '@agora.com')->exists()) {
            $email = $emailBase . rand(100, 999 + $contador);
            $contador++;
        }

        return $email;
    }

    protected $table = 'profesores';
    protected $fillable = [
        'colegio_id','sede_id', 'nombre_completo', 'documento', 'tipo_documento',
        'correo', 'telefono', 'titulo_academico'
    ];

    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }
    public function sede()
    {
        return $this->belongsTo(sedes_colegio::class);
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }

    public function examenes()
    {
        return $this->hasMany(Examen::class);
    }
    public function usuario()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function asignaturas()
    {
        return $this->belongsToMany(asignatura::class,'asignatura_profesors');
    }

    public function anuncios()
    {
        return $this->morphMany(Anuncio::class, 'anunciable');
    }
}
