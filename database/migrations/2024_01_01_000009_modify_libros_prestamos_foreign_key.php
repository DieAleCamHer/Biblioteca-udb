<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Eliminar la clave foránea existente
        Schema::table('prestamos', function (Blueprint $table) {
            $table->dropForeign(['libro_id']);
        });
        
        // Recrear con restrict en lugar de cascade
        Schema::table('prestamos', function (Blueprint $table) {
            $table->foreign('libro_id')
                  ->references('id')
                  ->on('libros')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestamos', function (Blueprint $table) {
            $table->dropForeign(['libro_id']);
        });
        
        Schema::table('prestamos', function (Blueprint $table) {
            $table->foreign('libro_id')
                  ->references('id')
                  ->on('libros')
                  ->onDelete('cascade');
        });
    }
};
