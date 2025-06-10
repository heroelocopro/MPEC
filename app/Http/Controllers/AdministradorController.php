<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministradorController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard.index');
    }
    public function usuarios()
    {
        return view('admin.usuarios.index');
    }
}
