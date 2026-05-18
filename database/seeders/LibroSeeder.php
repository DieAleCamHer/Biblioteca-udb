<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $libros = [
            [
                'categoria_id' => 1, // Ficción
                'titulo' => 'Cien años de soledad',
                'autor' => 'Gabriel García Márquez',
                'isbn' => '9780307474728',
                'anio_publicacion' => 1967,
                'stock' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_id' => 1, // Ficción
                'titulo' => '1984',
                'autor' => 'George Orwell',
                'isbn' => '9780451524935',
                'anio_publicacion' => 1949,
                'stock' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_id' => 2, // No Ficción
                'titulo' => 'Sapiens: De animales a dioses',
                'autor' => 'Yuval Noah Harari',
                'isbn' => '9780062316097',
                'anio_publicacion' => 2011,
                'stock' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_id' => 3, // Ciencia
                'titulo' => 'Cosmos',
                'autor' => 'Carl Sagan',
                'isbn' => '9780345539434',
                'anio_publicacion' => 1980,
                'stock' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_id' => 3, // Ciencia
                'titulo' => 'Una breve historia del tiempo',
                'autor' => 'Stephen Hawking',
                'isbn' => '9780553380163',
                'anio_publicacion' => 1988,
                'stock' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_id' => 5, // Programación
                'titulo' => 'Clean Code',
                'autor' => 'Robert C. Martin',
                'isbn' => '9780132350884',
                'anio_publicacion' => 2008,
                'stock' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_id' => 5, // Programación
                'titulo' => 'El programador pragmático',
                'autor' => 'Andrew Hunt, David Thomas',
                'isbn' => '9780135957059',
                'anio_publicacion' => 1999,
                'stock' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_id' => 4, // Historia
                'titulo' => 'Armas, gérmenes y acero',
                'autor' => 'Jared Diamond',
                'isbn' => '9780393354324',
                'anio_publicacion' => 1997,
                'stock' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('libros')->insert($libros);
    }
}
