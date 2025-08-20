<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // El orden es importante:
        // 1. Roles
        // 2. Usuarios (necesita que los roles ya existan)
        // 3. Materias
        // 4. Cursos
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SubjectSeeder::class,
            CourseSeeder::class,
        ]);
    }
}