<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear el usuario SuperAdmin
        $superAdmin = User::create([
            'name' => 'Admin',
            'email' => 'admin@sga.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('SuperAdmin');


        // 2. Crear los Docentes
        $teachers = [
            ['name' => 'Ortiz, Noemí'],
            ['name' => 'Britez, Horacio'],
            ['name' => 'Rolón, Lautaro'],
            ['name' => 'Vallejos Vega, Maria A.'],
            ['name' => 'Verón, Facundo'],
            ['name' => 'Crozy, Germán'],
            ['name' => 'Hoferek, Silvia'],
            ['name' => 'Maza, Alejandra'],
            ['name' => 'Castel, Silvia del Carmen'],
            ['name' => 'Romero, Melodi'],
            ['name' => 'Aguirre, Enrique'],
            ['name' => 'Roig, Eduardo'],
        ];

        foreach ($teachers as $teacher) {

            // --- LÓGICA DE EMAIL ---
            // 1. Limpiamos el nombre: "Ortiz, Noemí" -> "Ortiz Noemi"
            $cleanedName = str_replace(',', '', $teacher['name']);
            // 2. Usamos Str::slug para convertirlo a un formato limpio: "Ortiz Noemi" -> "ortiz.noemi"
            $emailSlug = Str::slug($cleanedName, '.');

            $email = $emailSlug . '@sga.com';
            // -----------------------------------

            $user = User::create([
                'name' => $teacher['name'],
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('Docente');
        }

        // 3. Crear algunos Alumnos de prueba
        User::factory()->count(50)
            ->create(['email_verified_at' => now()]) // <-- AÑADE ESTA LÍNEA
            ->each(function ($user) {
                $user->assignRole('Alumno');
            });
    }
}
