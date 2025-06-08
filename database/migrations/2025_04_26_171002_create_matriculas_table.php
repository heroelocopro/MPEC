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
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('colegio_id')->constrained('colegios')->onDelete('cascade');
            $table->foreignId('sede_id')->nullable(true)->constrained('sedes_colegios')->onDelete('cascade');
            $table->foreignId('grado_id')->constrained('grados')->onDelete('cascade');
            $table->enum('tipo_matricula', ['nueva', 'renovacion', 'traslado'])->default('nueva'); // Nueva, Renovación, Traslado
            $table->string('estado')->default('activo');
            $table->timestamp('fecha_matricula')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
