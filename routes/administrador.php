<?php

use App\Http\Controllers\AdministradorController;
use App\Livewire\Admin\AdministradorAuditoria;
use App\Livewire\Admin\AdministradorColegio;
use App\Livewire\Admin\AdministradorColegios;
use App\Livewire\Admin\AdministradorInicio;
use App\Livewire\Admin\AdministradorUsuarios;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('administrador')->group(function () {
    Route::livewire('/',AdministradorInicio::class)->name('administrador-principal');
    Route::livewire('/usuarios', AdministradorUsuarios::class)->name('administrador-usuarios');
    Route::livewire('/colegios', AdministradorColegios::class)->name('administrador-colegios');
    Route::livewire('/colegio/{id}',AdministradorColegio::class)->name('administrador-colegio');
    Route::livewire('/auditoria',AdministradorAuditoria::class)->name('administrador-auditoria');
});
