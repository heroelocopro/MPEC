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
        Schema::create('asignaturas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('codigo')->nullable();
        $table->string('area')->nullable();
        $table->text('descripcion')->nullable();
        $table->foreignId('colegio_id')->constrained('colegios')->onDelete('cascade');
        $table->integer('grado_minimo')->nullable();
        $table->integer('grado_maximo')->nullable();
        $table->integer('carga_horaria')->nullable();
        $table->enum('tipo', ['obligatoria', 'optativa'])->default('obligatoria');
        $table->boolean('estado')->default(true);
        $table->string('color')->nullable(); // hex: #FF5733 por ejemplo
        $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaturas');
    }
};
