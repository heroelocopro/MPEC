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
            $table->string('titulo');
            $table->text('contenido');
            $table->string('tipo_autor'); // 'profesor' o 'Colegio'
            $table->foreignId('autor_id'); // Puede ser profesor o Colegio
            $table->enum('tipo',['Global','Grado','Grupo'])->default('Grupo'); //si lo ve todo el colegio el grado o el grupo.
            $table->foreignId('colegio_id')->constrained('colegios')->onDelete('cascade');
            $table->foreignId('grado_id')->nullable(true)->constrained('grados')->onDelete('cascade');
            $table->foreignId('grupo_id')->nullable(true)->constrained('grupos')->onDelete('cascade');
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
