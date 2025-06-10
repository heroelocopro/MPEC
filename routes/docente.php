<?php

use App\Http\Controllers\DocenteController;
use App\Livewire\Docente\DocenteAnuncios;
use App\Livewire\Docente\DocenteHorarios;
use App\Livewire\Docente\DocenteNotas;
use App\Livewire\Docente\DocenteNotasPeriodo;
use App\Livewire\Docente\DocenteVerAnuncios;
use App\Livewire\Docente\DocenteVerExamenes;
use App\Livewire\Docente\DocenteVerRespuestasActividades;
use App\Livewire\Docente\DocenteVerRespuestasExamenes;
use App\Models\Actividad;
use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use App\Models\Examen;
use App\Models\nota;
use App\Models\NotaFinal;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:docente'])->prefix('docente')->group(function () {
    Route::get('/inicio',[DocenteController::class,'inicio'])->name('docente-inicio');
    Route::get('/notas',[DocenteController::class,'mostrarNotas'] )->name('docente-notas');
    Route::get('/asistencias',[DocenteController::class,'mostrarAsistencias'] )->name('docente-asistencias');
    Route::get('/actividades',[DocenteController::class,'mostrarActividades'] )->name('docente-actividades');
    Route::get('/tareas',[DocenteController::class,'mostrarNotas'] )->name('docente-tareas');
    Route::get('/evaluaciones',[DocenteController::class,'mostrarEvaluaciones'] )->name('docente-evaluaciones');
    // Route::get('/test',[DocenteController::class,'notas'])->name('test');
    Route::get('/ver-evaluaciones',DocenteVerExamenes::class)->name('docente-ver-evaluaciones');
    Route::get('/ver-evaluacion/{id}',DocenteVerRespuestasExamenes::class)->name('docente-ver-evaluacion');

    Route::get('/ver-actividad/{id}',DocenteVerRespuestasActividades::class)->name('docente-ver-actividad');

    Route::get('/anuncios',DocenteAnuncios::class)->name('docente-anuncios');
    Route::get('/ver-anuncios',DocenteVerAnuncios::class)->name('docente-ver-anuncios');

    Route::get('/horarios', DocenteHorarios::class)->name('docente-horarios');
    Route::get('/notas/periodo', DocenteNotasPeriodo::class)->name('docente-notas-periodo');

    Route::get('/notaFinal', function (){
        $periodoId = 2;
$grupoId = 1;
$asignaturaId = 1;
$colegioId = 3;

// Obtener actividades y evaluaciones del grupo, asignatura y periodo
$actividades = Actividad::where([
    ['periodo_id', $periodoId],
    ['grupo_id', $grupoId],
    ['asignatura_id', $asignaturaId],
])->get();

$evaluaciones = Examen::where([
    ['periodo_id', $periodoId],
    ['grupo_id', $grupoId],
    ['asignatura_id', $asignaturaId],
])->get();

// Crear colección con todos los IDs esperados en el formato "id:tipo"
$itemsEsperados = collect($actividades)
    ->merge($evaluaciones)
    ->map(fn($item) => $item->id . ':' . class_basename($item));

// Obtener estudiantes del grupo
$estudiantesGrupos = EstudianteGrupo::with('estudiante')->where('grupo_id', $grupoId)->get();
$estudiantes = $estudiantesGrupos->pluck('estudiante')->filter();

// Obtener notas agrupadas por estudiante
$notas = Nota::where('periodo_id', $periodoId)
    ->where('grupo_id', $grupoId)
    ->where('asignatura_id', $asignaturaId)
    ->get()
    ->groupBy('estudiante_id');

// Procesar por estudiante
foreach ($estudiantes as $estudiante) {
    $estudianteId = $estudiante->id;

    $notasDelEstudiante = $notas->get($estudianteId, collect());

    // IDs de las notas que tiene el estudiante
    $notasIds = $notasDelEstudiante->map(fn($nota) => $nota->notable_id . ':' . class_basename($nota->notable_type));

    // Faltantes = actividades o exámenes sin nota
    $faltantes = $itemsEsperados->diff($notasIds);

    // Valores de notas existentes
    $valores = $notasDelEstudiante->pluck('valor')->toArray();

    // Agregar 1.0 por cada ítem faltante
    $valores = array_merge($valores, array_fill(0, $faltantes->count(), 1.0));

    // Si no hay ninguna nota ni faltante (ej. no hay actividades ni evaluaciones), omitir
    if (count($valores) === 0) {
        Log::warning("El estudiante {$estudianteId} no tiene ninguna actividad ni evaluación registrada.");
        continue;
    }

    // Calcular promedio
    $promedio = round(array_sum($valores) / count($valores), 2);

    // Guardar nota final
    NotaFinal::updateOrCreate(
        [
            'estudiante_id' => $estudianteId,
            'asignatura_id' => $asignaturaId,
            'grupo_id' => $grupoId,
            'colegio_id' => $colegioId,
            'periodo_id' => $periodoId,
            'ano' => now()->year,
        ],
        ['nota' => $promedio]
    );
}

return 'ok';


    });

});
