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
