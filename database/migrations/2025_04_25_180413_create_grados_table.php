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
        Schema::create('grados', function (Blueprint $table) {
        $table->id();
        $table->string('nombre'); // Primero, Segundo, Sexto, Undécimo...
        $table->foreignId('colegio_id')->constrained('colegios')->onDelete('cascade');
        $table->enum('nivel', ['preescolar', 'primaria', 'secundaria', 'media']);
        $table->text('descripcion')->nullable(); // "Inicio de lectura y escritura", "Preparación para primaria", etc.
        $table->string('edad_referencia')->nullable(); // "6-7 años", "11-12 años", etc.
        $table->boolean('estado')->default(true); // por si un grado no se ofrece ese año
        $table->timestamps();

    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grados');
    }
};
