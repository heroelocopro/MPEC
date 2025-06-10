<?php

use App\Http\Controllers\EstudianteController;
use App\Livewire\Estudiante\EstudianteNotas;
use App\Livewire\Estudiante\EstudianteVerExamen;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:estudiante'])->prefix('estudiante')->group(function () {
    Route::get('/inicio', [EstudianteController::class,'mostrarInicio'])->name('estudiante-inicio');
    Route::get('/actividades', [EstudianteController::class,'mostrarActividades'])->name('estudiante-actividades');
    Route::get('/examenes', [EstudianteController::class,'mostrarExamenes'])->name('estudiante-examenes');
    Route::get('/horarios', [EstudianteController::class,'mostrarHorarios'])->name('estudiante-horarios');
    Route::get('/asignaturas', [EstudianteController::class,'mostrarAsignaturas'])->name('estudiante-asignaturas');
    Route::get('/docentes', [EstudianteController::class,'mostrarDocentes'])->name('estudiante-docentes');
    Route::get('/anuncios', [EstudianteController::class,'mostrarAnuncios'])->name('estudiante-anuncios');
    Route::get('/notas', EstudianteNotas::class )->name('estudiante-notas');

    Route::get('/estudiante/notas/pdf/{periodo}', [EstudianteController::class, 'descargar'])
    ->name('estudiante.notas.pdf')
    ->middleware('auth');

    Route::get('/examen/{id}', EstudianteVerExamen::class)->name('estudiante-ver-examen');
});
