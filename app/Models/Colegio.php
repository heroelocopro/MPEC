<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colegio extends Model
{
    protected static function booted()
    {
        static::created(function ($colegio) {
            $emailBase = self::generarEmail($colegio->nombre);
            $emailFinal = self::asegurarEmailUnico($emailBase);

            $user = User::create([
                'name' => $colegio->nombre,
                'email' => $emailFinal . '@' . self::getDominio(),
                'password' => bcrypt('123456789'),
                'role_id' => 2
            ]);

            $colegio->user_id = $user->id;
            $colegio->save();
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

        return self::limpiarTexto($iniciales);
    }

    // Función para limpiar texto (quitar tildes, ñ, caracteres raros)
    protected static function limpiarTexto($texto)
    {
        $texto = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'],
            ['a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'n', 'n'],
            $texto
        );

        return strtolower(preg_replace('/[^A-Za-z0-9]/', '', $texto));
    }

    // Función para asegurar que el email sea único
    protected static function asegurarEmailUnico($emailBase)
    {
        $email = $emailBase . rand(100, 999);
        $contador = 1;

        while (User::where('email', $email . '@' . self::getDominio())->exists()) {
            $email = $emailBase . rand(100, 999 + $contador);
            $contador++;
        }

        return $email;
    }

    // Función que construye el dominio basado en APP_NAME
    protected static function getDominio()
    {
        $appName = env('APP_NAME', 'plataforma');

        // Eliminar espacios al inicio y final
        $appName = trim($appName);

        // Reemplazar espacios internos con guiones
        $dominio = str_replace(' ', '-', $appName);

        // Quitar caracteres especiales y convertir a minúsculas
        $dominio = strtolower(preg_replace('/[^a-zA-Z0-9\-]/', '', $dominio));

        // Asegurarse de que no queden guiones múltiples o al inicio/final
        $dominio = preg_replace('/\-+/', '-', $dominio);
        $dominio = trim($dominio, '-');

        return $dominio . '.com';
    }

    protected $fillable = ['nombre', 'codigo_dane', 'direccion', 'telefono', 'correo','departamento','municipio','estado','calendario'];

    public function profesores()
    {
        return $this->hasMany(Profesor::class);
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }

    public function matriculas()
    {
        return $this->hasMany(matricula::class);
    }

    public function materias()
    {
        return $this->hasMany(asignatura::class);
    }

    public function sedes()
    {
        return $this->hasMany(sedes_colegio::class);
    }

    public function foros()
    {
        return $this->hasMany(Foro::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function grados()
    {
        return $this->hasMany(Grado::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function asignaturas()
    {
        return $this->hasMany(asignatura::class);
    }

    public function anuncios()
    {
        return $this->morphMany(Anuncio::class, 'anunciable');
    }

    public function notas()
    {
        return $this->hasMany(NotaFinal::class);
    }
}
