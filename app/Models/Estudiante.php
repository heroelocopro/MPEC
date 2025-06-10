<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected static function booted()
    {
        static::created(function ($estudiante) {
            $emailBase = self::generarEmail($estudiante->nombre_completo);
            $emailFinal = self::asegurarEmailUnico($emailBase);
            $user =  User::create(['name' => $estudiante->nombre_completo,'email' => $emailFinal."@agora.com",'password' => bcrypt('123456789'),'role_id' => 4]);
            $estudiante->user_id = $user->id;
            $estudiante->save();
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
    protected $fillable = [
        'colegio_id','sede_id', 'nombre_completo', 'documento', 'tipo_documento', 'fecha_nacimiento',
        'genero', 'grupo_sanguineo', 'eps', 'sisben', 'poblacion_vulnerable',
        'discapacidad', 'direccion', 'telefono','correo',
    ];

    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }
    public function sede()
    {
        return $this->belongsTo(sedes_colegio::class);
    }
    public function notas()
    {
        return $this->hasMany(nota::class);
    }

    public function acudientes()
    {
        return $this->hasMany(Acudiente::class);
    }
    public function matricula()
    {
        return $this->hasOne(matricula::class);
    }

    public function matriculas()
    {
        return $this->hasMany(\App\Models\matricula::class);
    }

    public function respuestasActividades()
    {
        return $this->hasMany(respuesta_actividad::class);
    }

    public function respuestasExamenes()
    {
        return $this->hasMany(Respuesta_Examen::class);
    }
    public function usuario()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function estudiantesGrupos()
    {
        return $this->hasMany(EstudianteGrupo::class);
    }
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function notasFinales()
{
    return $this->hasMany(\App\Models\NotaFinal::class);
}

}
