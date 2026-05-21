<?php

use Illuminate\Database\Migrations\Migration; // Clase base
use Illuminate\Database\Schema\Blueprint; // Constructorde tablas
use Illuminate\Support\Facades\Schema; // Facade para manejar la base de datos
// Laravel usa clases anonimas para migraciones
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void // Metodo up() crea la tabla
    {
        // Schema::create crea una tabla llamada libros
        // function (Blueprint $tables) Define las columnas
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id') // Crea una columna numerica
                  ->constrained('categorias') // Crea relacion con tabla categorias
                  ->onDelete('restrict'); // No permitir eliminar categoría con libros
            $table->string('titulo', 200);
            $table->string('autor', 150);
            $table->string('isbn', 13)->unique();
            $table->integer('stock')->default(0)->unsigned(); // Solo numeros positivos y cero
            $table->timestamps(); // Crea fecha de creacion y fecha de actualizacion
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Metodo down() deshace la migracion
    {
        Schema::dropIfExists('libros'); // Se ejecuta con php artisan migrate:rollback
    }
};
