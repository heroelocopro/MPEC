<?php

use App\Http\Controllers\EstudianteController;
use App\Livewire\Estudiante\EstudianteActividades;
use App\Livewire\Estudiante\EstudianteAnuncios;
use App\Livewire\Estudiante\EstudianteAsignaturas;
use App\Livewire\Estudiante\EstudianteDocentes;
use App\Livewire\Estudiante\EstudianteExamenes;
use App\Livewire\Estudiante\EstudianteHorarios;
use App\Livewire\Estudiante\EstudianteInicio;
use App\Livewire\Estudiante\EstudianteNotas;
use App\Livewire\Estudiante\EstudianteVerExamen;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:estudiante'])->prefix('estudiante')->group(function () {
    Route::get('/inicio', EstudianteInicio::class)->name('estudiante-inicio');
    Route::livewire('/actividades', EstudianteActividades::class)->name('estudiante-actividades');
    Route::livewire('/examenes', EstudianteExamenes::class)->name('estudiante-examenes');
    Route::livewire('/horarios', EstudianteHorarios::class)->name('estudiante-horarios');
    Route::livewire('/asignaturas', EstudianteAsignaturas::class)->name('estudiante-asignaturas');
    Route::livewire('/docentes', EstudianteDocentes::class)->name('estudiante-docentes');
    Route::livewire('/anuncios', EstudianteAnuncios::class)->name('estudiante-anuncios');
    Route::livewire('/notas', EstudianteNotas::class )->name('estudiante-notas');

    Route::get('/estudiante/notas/pdf/{periodo}', [EstudianteController::class, 'descargar'])
    ->name('estudiante.notas.pdf')
    ->middleware('auth');

    Route::get('/examen/{id}', EstudianteVerExamen::class)->name('estudiante-ver-examen');
});
