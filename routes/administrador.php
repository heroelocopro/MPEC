<?php

use App\Http\Controllers\AdministradorController;
use App\Livewire\Admin\AdministradorColegios;
use App\Livewire\Admin\AdministradorInicio;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('administrador')->group(function () {
    Route::get('/',AdministradorInicio::class)->name('administrador-principal');
    Route::get('/usuarios', [AdministradorController::class,'usuarios'])->name('administrador-usuarios');
    Route::get('/colegios', AdministradorColegios::class)->name('administrador-colegios');
});
