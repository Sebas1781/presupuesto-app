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
            // Campos de Seguridad Pública
            $table->string('servicio_seguridad')->nullable()->after('obras_calificadas');
            $table->string('confia_policia')->nullable()->after('servicio_seguridad');
            $table->string('horario_inseguro')->nullable()->after('confia_policia');
            $table->json('problemas_seguridad')->nullable()->after('horario_inseguro');
            $table->json('lugares_seguros')->nullable()->after('problemas_seguridad');
            $table->integer('emergencia_transporte')->nullable()->after('lugares_seguros');
            $table->integer('caminar_noche')->nullable()->after('emergencia_transporte');
            $table->integer('hijos_solos')->nullable()->after('caminar_noche');
            $table->integer('transporte_publico')->nullable()->after('hijos_solos');
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
