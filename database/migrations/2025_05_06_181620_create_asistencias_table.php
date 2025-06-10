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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->foreignId('colegio_id')->constrained('colegios')->onDelete('cascade');
            $table->foreignId('asignatura_id')->constrained('asignaturas')->onDelete('cascade');
            $table->foreignId('profesor_id')->constrained('profesores')->onDelete('cascade');

            $table->date('fecha');
            $table->string('bloque'); // bloque puede ser horario o un número

            $table->enum('estado', ['presente', 'ausente', 'tarde', 'justificado']);
            $table->text('justificacion')->nullable();

            $table->timestamps();

            // Evita duplicados por estudiante en una misma clase
            $table->unique(['estudiante_id', 'grupo_id', 'asignatura_id', 'fecha', 'bloque'], 'asistencia_unica_idx');
                });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
