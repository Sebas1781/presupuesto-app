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
        Schema::create('encuestas', function (Blueprint $table) {
            $table->id();
            // Datos sociodemográficos
            $table->foreignId('colonia_id')->constrained('colonias')->onDelete('cascade');
            $table->string('genero');
            $table->integer('edad');
            $table->string('nivel_educativo');
            $table->string('estado_civil');
            
            // Obra pública calificada
            $table->json('obras_calificadas'); // Guardará un JSON con las obras y calificaciones
            
            // Desea realizar reporte anónimo
            $table->boolean('desea_reporte')->default(false);
            $table->string('tipo_reporte')->nullable();
            $table->text('descripcion_reporte')->nullable();
            $table->string('evidencia_reporte')->nullable(); // ruta del archivo
            $table->string('ubicacion_reporte')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuestas');
    }
};
