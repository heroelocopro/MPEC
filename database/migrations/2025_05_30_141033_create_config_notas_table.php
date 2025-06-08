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
        Schema::create('config_notas', function (Blueprint $table) {
            $table->id();
            $table->float('nota_minima');
            $table->float('nota_maxima');
            $table->float('nota_requerida');
            $table->foreignId('colegio_id')->constrained('colegios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_notas');
    }
};
