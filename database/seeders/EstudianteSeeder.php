<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstudianteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estudiantes = [
            [
                'carnet' => 'CD252968',
                'nombre' => 'Carlos Díaz',
                'email' => 'carlos.hernandez@udb.edu.sv',
                'telefono' => '7890-1234',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'carnet' => 'MD259867',
                'nombre' => 'María Díaz',
                'email' => 'maria.diaz@udb.edu.sv',
                'telefono' => '7891-2345',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'carnet' => 'JR248956',
                'nombre' => 'José Ramírez',
                'email' => 'jose.ramirez@udb.edu.sv',
                'telefono' => '7892-3456',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'carnet' => 'LG237845',
                'nombre' => 'Laura García',
                'email' => 'laura.garcia@udb.edu.sv',
                'telefono' => '7893-4567',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'carnet' => 'AM226734',
                'nombre' => 'Ana Martínez',
                'email' => 'ana.martinez@udb.edu.sv',
                'telefono' => '7894-5678',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'carnet' => 'RL215623',
                'nombre' => 'Roberto López',
                'email' => null,
                'telefono' => null,
                'activo' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('estudiantes')->insert($estudiantes);
    }
}
