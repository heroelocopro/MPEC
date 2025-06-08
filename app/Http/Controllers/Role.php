<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Role extends Controller
{
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
