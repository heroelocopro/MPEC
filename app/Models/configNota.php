<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class configNota extends Model
{
    protected $fillable = [
        'nota_minima',
        'nota_maxima',
        'nota_requerida',
        'colegio_id',
    ];
}
