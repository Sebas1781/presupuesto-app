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
        Schema::create('propuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encuesta_id')->constrained('encuestas')->onDelete('cascade');
            $table->string('tipo_propuesta');
            $table->string('nivel_prioridad');
            $table->string('personas_beneficiadas');
            $table->string('fotografia')->nullable(); // ruta del archivo
            $table->string('ubicacion'); // Google maps
            $table->text('descripcion_breve');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propuestas');
    }
};
