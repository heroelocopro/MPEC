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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colegio_id')->constrained('colegios')->onDelete('cascade');
            $table->foreignId('sede_id')->nullable(true)->constrained('sedes_colegios')->onDelete('cascade');
            $table->string('nombre_completo');
            $table->string('documento')->unique();
            $table->string('tipo_documento');
            $table->date('fecha_nacimiento');
            $table->string('genero');
            $table->string('grupo_sanguineo')->nullable();
            $table->string('eps')->nullable();
            $table->string('sisben')->nullable();
            $table->string('poblacion_vulnerable');
            $table->string('discapacidad')->nullable();
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->foreignId('user_id')->nullable(true)->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
