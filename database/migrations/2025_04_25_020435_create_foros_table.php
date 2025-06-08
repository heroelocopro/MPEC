<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('foros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colegio_id')->constrained('colegios')->onDelete('cascade');
            $table->string('titulo');
            $table->text('contenido');
            $table->foreignId('autor_id'); // Puede ser profesor o estudiante
            $table->string('tipo_autor'); // 'profesor' o 'estudiante'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foros');
    }
};
