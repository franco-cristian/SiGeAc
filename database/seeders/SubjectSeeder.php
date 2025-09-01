<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Probabilidad y Estadística'],
            ['name' => 'Base de Datos I'],
            ['name' => 'Programación II'],
            ['name' => 'Inglés I'],
            ['name' => 'Metodología de Sistemas II'],
            ['name' => 'Programación IV'],
            ['name' => 'Gestión de Desarrollo de Software'],
            ['name' => 'Introducción al Análisis de Datos'],
            ['name' => 'Legislación'],
            ['name' => 'TFI'], // Trabajo Final Integrador
        ];

        // Usamos insert para mayor eficiencia en un solo query
        Subject::insert($subjects);
    }
}