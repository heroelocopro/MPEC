<?php

use App\Http\Controllers\ColegioController;
use App\Livewire\Colegio\CerrarNotas;
use App\Livewire\Colegio\ColegioAnuncios;
use App\Livewire\Colegio\ColegioAsignaturas;
use App\Livewire\Colegio\ColegioAsignaturasGrados;
use App\Livewire\Colegio\ColegioAsistencias;
use App\Livewire\Colegio\ColegioDocentes;
use App\Livewire\Colegio\ColegioDocentesAsignaturas;
use App\Livewire\Colegio\ColegioEstudiantes;
use App\Livewire\Colegio\ColegioEstudiantesGrupos;
use App\Livewire\Colegio\ColegioGrados;
use App\Livewire\Colegio\ColegioGrupo;
use App\Livewire\Colegio\ColegioGrupos;
use App\Livewire\Colegio\ColegioHistorialAcademico;
use App\Livewire\Colegio\ColegioHorarios;
use App\Livewire\Colegio\ColegioInicio;
use App\Livewire\Colegio\ColegioMatriculas;
use App\Livewire\Colegio\ColegioNotas;
use App\Livewire\Colegio\ColegioPeriodos;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:colegio'])->prefix('colegio')->group(function () {
    Route::get('/inicio', ColegioInicio::class )->name('colegio-inicio');

    Route::livewire('/docentes',ColegioDocentes::class)->name('colegio-docentes');

    Route::livewire('/estudiantes',ColegioEstudiantes::class)->name('colegio-estudiantes');

    Route::livewire('/asignaturas',ColegioAsignaturas::class)->name('colegio-asignaturas');

    Route::livewire('/grados',ColegioGrados::class)->name('colegio-grados');

    Route::livewire('/grupos',ColegioGrupos::class)->name('colegio-grupos');

    Route::livewire('/grupo/{id}',ColegioGrupo::class)->name('colegio-grupo');

    Route::livewire('/matriculas',ColegioMatriculas::class)->name('colegio-matriculas');

    Route::livewire('/estudiantes-grupos',ColegioEstudiantesGrupos::class)->name('colegio-estudiantes-grupos');

    Route::livewire('/docentes-asignaturas',ColegioDocentesAsignaturas::class)->name('colegio-docentes-asignaturas');

    Route::livewire('/asignaturas-grados',ColegioAsignaturasGrados::class)->name('colegio-asignaturas-grados');

    Route::livewire('/horarios',ColegioHorarios::class)->name('colegio-horarios');

    Route::livewire('/periodos',ColegioPeriodos::class)->name('colegio-periodos');

    Route::livewire('/asignaturas',ColegioAsignaturas::class)->name('colegio-asignaturas');

    Route::livewire('/anuncios',ColegioAnuncios::class)->name('colegio-anuncios');

    Route::livewire('/asistencias',ColegioAsistencias::class)->name('colegio-asistencias');

    Route::livewire('/notas',ColegioNotas::class)->name('colegio-notas');

    Route::livewire('/historial-academico',ColegioHistorialAcademico::class)->name('colegio-historial-academico');

    Route::get('/Cierre-de-notas', CerrarNotas::class )->name('colegio-cierre-de-notas');

    Route::post('/cerrar-notas-periodo',[ColegioController::class,'cerrarNotas'])->name('colegio-cerrar-notas');

    Route::get('/descargar-notas/{periodo}/{estudiante}',[ColegioController::class,'descargarNotas'])->name('colegio-descargar-notas');

});
