<?php

use App\Http\Controllers\ColegioController;
use App\Livewire\Colegio\ColegioAsistencias;
use App\Livewire\Colegio\ColegioInicio;
use App\Livewire\Colegio\ColegioNotas;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:colegio'])->prefix('colegio')->group(function () {
    Route::get('/inicio', ColegioInicio::class )->name('colegio-inicio');

    Route::get('/docentes',[ColegioController::class,'mostrarDocentes'])->name('colegio-docentes');

    Route::get('/estudiantes',[ColegioController::class,'mostrarEstudiantes'])->name('colegio-estudiantes');

    Route::get('/asignaturas',[ColegioController::class,'mostrarAsignaturas'])->name('colegio-asignaturas');

    Route::get('/grados',[ColegioController::class,'mostrarGrados'])->name('colegio-grados');

    Route::get('/grupos',[ColegioController::class,'mostrarGrupos'])->name('colegio-grupos');

    Route::get('/matriculas',[ColegioController::class,'mostrarMatriculas'])->name('colegio-matriculas');

    Route::get('/estudiantes-grupos',[ColegioController::class,'mostrarEstudiantesGrupos'])->name('colegio-estudiantes-grupos');

    Route::get('/docentes-asignaturas',[ColegioController::class,'mostrarDocentesAsignaturas'])->name('colegio-docentes-asignaturas');

    Route::get('/asignaturas-grados',[ColegioController::class,'mostrarAsignaturasGrados'])->name('colegio-asignaturas-grados');

    Route::get('/horarios',[ColegioController::class,'mostrarHorarios'])->name('colegio-horarios');

    Route::get('/periodos',[ColegioController::class,'mostrarPeriodos'])->name('colegio-periodos');

    Route::get('/asignaturas',[ColegioController::class,'mostrarAsignaturas'])->name('colegio-asignaturas');

    Route::get('/anuncios',[ColegioController::class,'mostrarAnuncios'])->name('colegio-anuncios');

    Route::get('/asistencias',ColegioAsistencias::class)->name('colegio-asistencias');

    Route::get('/notas',ColegioNotas::class)->name('colegio-notas');

});
