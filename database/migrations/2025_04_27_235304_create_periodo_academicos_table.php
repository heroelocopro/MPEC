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
        Schema::create('periodo_academicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colegio_id')->constrained('colegios')->onDelete('cascade'); // cada colegio gestiona sus periodos
            $table->string('nombre'); // Ej: "Primer Periodo"
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('estado')->default('activo'); // activo, finalizado, futuro, etc.
            $table->year('ano'); // opcional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodo_academicos');
    }
};
