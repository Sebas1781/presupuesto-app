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
            $table->json('problemas_seguridad')->nullable();
            $table->json('lugares_seguros')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encuestas', function (Blueprint $table) {
            $table->dropColumn([
                'problemas_seguridad',
                'lugares_seguros'
            ]);
        });
    }
};
