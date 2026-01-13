<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Anuncio extends Model
{
    protected $fillable = [
        'titulo',
        'contenido',
        'imagen',
        'colegio_id',
        'anunciable_id',
        'anunciable_type',
    ];

    public function anunciable()
    {
        return $this->morphTo();
    }

    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }

    public function getImagenUrlAttribute()
    {
        if (!$this->imagen) return null;

        return Storage::disk('s3')->temporaryUrl(
            $this->imagen,
            now()->addMinutes(30)
        );
    }

}
