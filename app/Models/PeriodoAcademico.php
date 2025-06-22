<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PeriodoAcademico extends Model
{
    protected $table = 'periodo_academicos';
    protected $fillable = [
    'colegio_id',
    'nombre',
    'fecha_inicio',
    'fecha_fin',
    'estado',
    'ano',
];
protected $casts = [
    'fecha_inicio' => 'date','fecha_fin' => 'date',
];

public function getEsActivoAttribute()
{
    $hoy = Carbon::now();
    return $hoy->between($this->fecha_inicio, $this->fecha_fin);
}
public static function diasTotales($colegio_id = null)
{

    // Si no pasas el colegio, intenta obtenerlo del usuario autenticado
    if (!$colegio_id && Auth::check()) {
        $colegio_id = Auth::user()->colegio_id;
    }

    // Obtener todos los periodos del año actual para ese colegio
    $periodos = self::where('colegio_id', $colegio_id)
        ->where('ano', now()->format('Y'))
        ->get();
    $dias = 0;

    foreach ($periodos as $periodo) {
        // Convertimos fechas a instancias de Carbon
        $inicio = Carbon::parse($periodo->fecha_inicio);
        $fin = Carbon::parse($periodo->fecha_fin);

        // Sumamos la diferencia en días (inclusive)
        $dias += $inicio->diffInDays($fin) + 1;
    }

    return $dias;
}

public static function periodoActual($colegio_id = null)
{
    // Si no pasas el colegio, intenta obtenerlo del usuario autenticado (opcional)
    if (!$colegio_id && Auth::check()) {
        $colegio_id = Auth::user()->colegio_id;
    }

    return self::where('estado', 'activo')
        ->when($colegio_id, fn($q) => $q->where('colegio_id', $colegio_id))
        ->orderByDesc('fecha_inicio') // por si hay varios activos, devuelve el más reciente
        ->first();
}

public function colegio()
{
    return $this->belongsTo(Colegio::class);
}


}
