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
        Schema::create('sedes_colegios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo_dane')->unique();
            $table->string('direccion');
            $table->string('telefono');
            $table->string('correo')->nullable();
            $table->string('departamento');
            $table->string('municipio');
            $table->string('estado')->nullable();
            $table->string('calendario')->nullable();
            // $table->foreignId('user_id')->nullable(true)->constrained('users')->onDelete('cascade');
            $table->foreignId('colegio_id')->nullable(true)->constrained('colegios')->onDelete('cascade');
            $table->foreignId('user_id')->nullable(true)->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colegios');
    }
};
