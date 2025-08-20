<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Datos de horarios TUP Formosa 2025
        $coursesData = [
            // --- COM 1.1 PRIMER AÑO - SEGUNDO CUATRIMESTRE ---
            ['name' => 'COM 1.1', 'year' => 2025, 'term' => 'Segundo Cuatrimestre', 'subject_name' => 'Probabilidad y Estadística', 'teacher_name' => 'Ortiz, Noemí'],
            ['name' => 'COM 1.1', 'year' => 2025, 'term' => 'Segundo Cuatrimestre', 'subject_name' => 'Base de Datos I', 'teacher_name' => 'Britez, Horacio'],
            ['name' => 'COM 1.1', 'year' => 2025, 'term' => 'Segundo Cuatrimestre', 'subject_name' => 'Programación II', 'teacher_name' => 'Rolón, Lautaro'],
            ['name' => 'COM 1.1', 'year' => 2025, 'term' => 'Segundo Cuatrimestre', 'subject_name' => 'Inglés I', 'teacher_name' => 'Vallejos Vega, Maria A.'],

            // --- COM 2.1 SEGUNDO AÑO - CUARTO CUATRIMESTRE ---
            ['name' => 'COM 2.1', 'year' => 2025, 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Metodología de Sistemas II', 'teacher_name' => 'Verón, Facundo'],
            ['name' => 'COM 2.1', 'year' => 2025, 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Programación IV', 'teacher_name' => 'Crozy, Germán'],
            ['name' => 'COM 2.1', 'year' => 2025, 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Gestión de Desarrollo de Software', 'teacher_name' => 'Hoferek, Silvia'],
            ['name' => 'COM 2.1', 'year' => 2025, 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Introducción al Análisis de Datos', 'teacher_name' => 'Maza, Alejandra'],
            ['name' => 'COM 2.1', 'year' => 2025, 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Legislación', 'teacher_name' => 'Castel, Silvia del Carmen'],
            ['name' => 'COM 2.1', 'year' => 2025, 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'TFI', 'teacher_name' => 'Britez, Horacio'],

            // --- COM 2.2 SEGUNDO AÑO - CUARTO CUATRIMESTRE ---
            ['name' => 'COM 2.2', 'year' => 2025, 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Gestión de Desarrollo de Software', 'teacher_name' => 'Romero, Melodi'],
            ['name' => 'COM 2.2', 'year' => 2025, 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Programación IV', 'teacher_name' => 'Aguirre, Enrique'],
            ['name' => 'COM 2.2', 'year' => 2025, 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Metodología de Sistemas II', 'teacher_name' => 'Verón, Facundo'],
            ['name' => 'COM 2.2', 'year' => 2025, 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Legislación', 'teacher_name' => 'Castel, Silvia del Carmen'],
            ['name' => 'COM 2.2', 'year' => 2025, 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Introducción al Análisis de Datos', 'teacher_name' => 'Roig, Eduardo'],
            ['name' => 'COM 2.2', 'year' => 2025, 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'TFI', 'teacher_name' => 'Roig, Eduardo'],
        ];

        foreach ($coursesData as $courseData) {
            // Buscamos la materia por su nombre
            $subject = Subject::where('name', $courseData['subject_name'])->first();
            // Buscamos al docente por su nombre
            $teacher = User::where('name', $courseData['teacher_name'])->role('Docente')->first();

            if ($subject && $teacher) {
                Course::create([
                    'name' => $courseData['name'],
                    'year' => $courseData['year'],
                    'term' => $courseData['term'],
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                    'capacity' => 40, // Un valor por defecto
                ]);
            } else {
                // Si algo falla, dejamos un log para saber qué fue
                Log::warning("No se pudo crear el curso: {$courseData['name']} - {$courseData['subject_name']}. Materia o docente no encontrado.");
            }
        }
    }
}