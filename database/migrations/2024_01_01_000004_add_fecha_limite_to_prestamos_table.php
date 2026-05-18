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
        Schema::table('prestamos', function (Blueprint $table) {
            $table->date('fecha_limite')->nullable()->after('fecha_prestamo');
            $table->integer('dias_retraso')->default(0)->after('fecha_devolucion');
            $table->boolean('tiene_retraso')->default(false)->after('dias_retraso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestamos', function (Blueprint $table) {
            $table->dropColumn(['fecha_limite', 'dias_retraso', 'tiene_retraso']);
        });
    }
};