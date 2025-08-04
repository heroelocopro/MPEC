<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
   public function index()
{
    $usuario = Auth::user();

    switch ($usuario->role_id) {
        case 1:
            return redirect()->route('administrador-principal');
        case 2:
            return redirect()->route('colegio-inicio');
        case 3:
            return redirect()->route('docente-inicio');
        case 4:
            return redirect()->route('estudiante-inicio');
        case 5:
            return redirect()->route('acudiente-inicio'); // opcional
        default:
            return redirect()->route('dashboard');
    }
}
}
