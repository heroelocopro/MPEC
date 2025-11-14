<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\NotaFinal;

class Estudiante extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::created(function ($estudiante) {
            $emailBase = self::generarEmail($estudiante->nombre_completo);
            $emailFinal = self::asegurarEmailUnico($emailBase);

            $user = User::create([
                'name' => $estudiante->nombre_completo,
                'email' => $emailFinal . '@' . self::getDominio(),
                'password' => bcrypt('123456789'),
                'role_id' => 4,
            ]);

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

    protected $fillable = [
        'colegio_id', 'sede_id', 'nombre_completo', 'documento', 'tipo_documento', 'fecha_nacimiento',
        'genero', 'grupo_sanguineo', 'eps', 'sisben', 'poblacion_vulnerable',
        'discapacidad', 'direccion', 'telefono', 'correo',
    ];

    // Relaciones
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
        return $this->hasMany(matricula::class);
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
        return $this->belongsTo(User::class, 'user_id');
    }

    public function estudiantesGrupos()
    {
        return $this->hasMany(EstudianteGrupo::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'estudiante_id');
    }
    public function getAsistenciasTotalesAttribute()
    {
        return $this->hasMany(Asistencia::class, 'estudiante_id')
                ->where('estado', 'presente')
                ->count();
    }

    public function notasFinales()
    {
        return $this->hasMany(NotaFinal::class);
    }

    public function Promedio($anoFiltro = null)
    {

        return round(NotaFinal::where('estudiante_id', $this->id)
            ->where('ano',$anoFiltro)
            ->avg('nota'),1);
    }

    public function edad()
    {
        $edad = now()->diffInYears($this->fecha_nacimiento);

        return intval(abs($edad));
    }
}
