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
        Schema::table('encuestas', function (Blueprint $table) {
            // Campos del bloque C - Seguridad Pública
            $table->string('servicio_seguridad')->nullable();
            $table->string('confia_policia')->nullable();
            $table->string('horario_inseguro')->nullable();
            $table->json('problemas_seguridad')->nullable();
            $table->json('lugares_seguros')->nullable();
            $table->integer('emergencia_transporte')->nullable();
            $table->integer('caminar_noche')->nullable();
            $table->integer('hijos_solos')->nullable();
            $table->integer('transporte_publico')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encuestas', function (Blueprint $table) {
            $table->dropColumn([
                'servicio_seguridad',
                'confia_policia',
                'horario_inseguro',
                'problemas_seguridad',
                'lugares_seguros',
                'emergencia_transporte',
                'caminar_noche',
                'hijos_solos',
                'transporte_publico'
            ]);
        });
    }
};
