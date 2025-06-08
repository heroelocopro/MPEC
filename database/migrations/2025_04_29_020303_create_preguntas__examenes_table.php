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
        Schema::create('preguntas__examenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examen_id')->constrained('examenes')->onDelete('cascade');
            $table->text('pregunta');
            $table->string('tipo')->default('multiple_choice'); // o texto_libre
            $table->float('puntos');
            $table->json('opciones')->nullable(); // Para opción múltiple
            $table->string('respuesta_correcta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregunta__examens');
    }
};
