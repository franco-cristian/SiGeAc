<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'password' => Hash::make('password'), //Cambiar en producción
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
            // Genera un email a partir del nombre, ej: "Britez, Horacio" -> "britez.h@sga.com"
            $email = str_replace(
                [' ', ','], 
                ['', '.'], 
                strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $teacher['name']))
            ) . '@sga.com';
            
            // Corrige la inicial del segundo nombre si existe
            $email = preg_replace('/\.(\w)\./', '.\1', $email);

            $user = User::create([
                'name' => $teacher['name'],
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('Docente');
        }

        // 3. Crear Alumnos de prueba
        User::factory()->count(50)->create()->each(function ($user) {
            $user->assignRole('Alumno');
        });
    }
}