<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Ficción',
                'descripcion' => 'Novelas y cuentos de ficción literaria',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'No Ficción',
                'descripcion' => 'Libros basados en hechos reales, ensayos y biografías',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ciencia',
                'descripcion' => 'Libros de divulgación científica y tecnología',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Historia',
                'descripcion' => 'Libros sobre eventos históricos y personajes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Programación',
                'descripcion' => 'Libros sobre desarrollo de software y tecnología',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categorias')->insert($categorias);
    }
}
