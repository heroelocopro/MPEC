<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Profesor extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::created(function ($profesor) {
            $emailBase = self::generarEmail($profesor->nombre_completo);
            $emailFinal = self::asegurarEmailUnico($emailBase);

            $user = User::create([
                'name' => $profesor->nombre_completo,
                'email' => $emailFinal . '@' . self::getDominio(),
                'password' => bcrypt('123456789'),
                'role_id' => 3
            ]);

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
    #obtener los grupos que se asignaron al profesor.
    #seria una asignatura->grado
    #seria una asignatura->profesor
    #seria una estudiante->grupos
    #orden final profesor->asignatura->grado->grupo->estudiante
public function gruposAsignados()
{
    $gruposAsignados = [];
    $profesorR = Profesor::where('user_id',Auth::user()->id)->first();
    $grupos = Grupo::with('grado.asignaturas.profesores')->where('colegio_id', $this->colegio_id)->get();
    foreach ($grupos as $grupo) {
        foreach ($grupo->grado->asignaturas as $asignatura) {
            foreach ($asignatura->profesores as $profesor) {
                if ($profesor->id == $profesorR->id) {
                    $gruposAsignados[] = $grupo;
                    break 2; // grupo válido, salir de los dos bucles internos
                }
            }
        }
    }

    return collect($gruposAsignados)->unique('id')->values(); // sin duplicados
}

}
