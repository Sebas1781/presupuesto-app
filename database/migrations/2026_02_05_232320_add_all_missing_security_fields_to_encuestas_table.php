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
            // Verificar y agregar solo las columnas que no existen
            if (!Schema::hasColumn('encuestas', 'servicio_seguridad')) {
                $table->string('servicio_seguridad')->nullable();
            }
            if (!Schema::hasColumn('encuestas', 'confia_policia')) {
                $table->string('confia_policia')->nullable();
            }
            if (!Schema::hasColumn('encuestas', 'horario_inseguro')) {
                $table->string('horario_inseguro')->nullable();
            }
            if (!Schema::hasColumn('encuestas', 'emergencia_transporte')) {
                $table->integer('emergencia_transporte')->nullable();
            }
            if (!Schema::hasColumn('encuestas', 'caminar_noche')) {
                $table->integer('caminar_noche')->nullable();
            }
            if (!Schema::hasColumn('encuestas', 'hijos_solos')) {
                $table->integer('hijos_solos')->nullable();
            }
            if (!Schema::hasColumn('encuestas', 'transporte_publico')) {
                $table->integer('transporte_publico')->nullable();
            }
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
                'emergencia_transporte',
                'caminar_noche',
                'hijos_solos',
                'transporte_publico'
            ]);
        });
    }
};
