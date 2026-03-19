<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Foro;
use App\Livewire\VerForo;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Models\Profesor;
use App\Models\Estudiante;
use App\Models\Colegio;


Route::get('/', function () {
    $profesor = Profesor::where('colegio_id',1)->with('usuario')->first()->usuario->email;
    $colegio = Colegio::where('id',1)->with('usuario')->first()->usuario->email;
    $estudiante = Estudiante::where('colegio_id',1)->with('usuario')->first()->usuario->email;
    return view('inicio', compact('profesor','colegio','estudiante'));
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



Route::get('/s3-test', function () {
    \Illuminate\Support\Facades\Storage::disk('s3')->put(
        'debug/test.txt',
        'railway ok'
    );

    return 'OK';
});


Route::get('/check-env', function () {
    return [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'bucket' => env('AWS_BUCKET'),
        'endpoint' => env('AWS_ENDPOINT'),
    ];
});


Route::fallback(function()  {
    return view('https.404');
    }
);


require __DIR__.'/colegio.php';
require __DIR__.'/administrador.php';
require __DIR__.'/docente.php';
require __DIR__.'/estudiante.php';
require __DIR__.'/auth.php';
