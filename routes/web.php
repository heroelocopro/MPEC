<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Foro;
use App\Livewire\VerForo;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth','verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Route::get('/foro',Foro::class)->name('foro');
    Route::get('/foro/{id}',VerForo::class)->name('ver-foro');
});

require __DIR__.'/colegio.php';
require __DIR__.'/administrador.php';
require __DIR__.'/docente.php';
require __DIR__.'/estudiante.php';
require __DIR__.'/auth.php';
