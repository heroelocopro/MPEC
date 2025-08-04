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
        Schema::create('respuestas__foros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('foro_id')->constrained('foros')->onDelete('cascade');
            $table->foreignId('autor_id');
            $table->string('tipo_autor');
            $table->text('mensaje');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuesta__foros');
    }
};
