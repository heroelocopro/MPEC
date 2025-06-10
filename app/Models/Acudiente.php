<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acudiente extends Model
{
        protected static function booted()
{
    static::created(function ($acudiente) {
        $user =  User::create(['name' => $acudiente->nombre_completo,'email' => $acudiente->nombre_completo."@agora.com",'password' => bcrypt('123456789'),'role_id' => 5]);
        $acudiente->user_id = $user->id;
        $acudiente->save();
    });
}
    protected $fillable = [
        'estudiante_id', 'nombre_completo', 'documento',
        'tipo_documento', 'parentesco', 'telefono', 'correo'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }

    public function sede()
    {
        return $this->belongsTo(sedes_colegio::class);
    }
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
