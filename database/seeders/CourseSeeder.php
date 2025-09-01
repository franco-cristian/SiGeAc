<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Definimos las comisiones (cursos) sin sus horarios.
        //    Cada comisión es un registro único.
        $coursesData = [
            // --- COM 1.1 ---
            ['name' => 'COM 1.1', 'term' => 'Segundo Cuatrimestre', 'subject_name' => 'Probabilidad y Estadística', 'teacher_name' => 'Ortiz, Noemí'],
            ['name' => 'COM 1.1', 'term' => 'Segundo Cuatrimestre', 'subject_name' => 'Base de Datos I', 'teacher_name' => 'Britez, Horacio'],
            ['name' => 'COM 1.1', 'term' => 'Segundo Cuatrimestre', 'subject_name' => 'Programación II', 'teacher_name' => 'Rolón, Lautaro'],
            ['name' => 'COM 1.1', 'term' => 'Segundo Cuatrimestre', 'subject_name' => 'Inglés I', 'teacher_name' => 'Vallejos Vega, Maria A.'],

            // --- COM 2.1 ---
            ['name' => 'COM 2.1', 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Metodología de Sistemas II', 'teacher_name' => 'Verón, Facundo'],
            ['name' => 'COM 2.1', 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Programación IV', 'teacher_name' => 'Crozy, Germán'],
            ['name' => 'COM 2.1', 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Gestión de Desarrollo de Software', 'teacher_name' => 'Hoferek, Silvia'],
            ['name' => 'COM 2.1', 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Introducción al Análisis de Datos', 'teacher_name' => 'Maza, Alejandra'],
            ['name' => 'COM 2.1', 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Legislación', 'teacher_name' => 'Castel, Silvia del Carmen'],
            ['name' => 'COM 2.1', 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'TFI', 'teacher_name' => 'Britez, Horacio'],

            // --- COM 2.2 ---
            ['name' => 'COM 2.2', 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Gestión de Desarrollo de Software', 'teacher_name' => 'Romero, Melodi'],
            ['name' => 'COM 2.2', 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Programación IV', 'teacher_name' => 'Aguirre, Enrique'],
            ['name' => 'COM 2.2', 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Metodología de Sistemas II', 'teacher_name' => 'Verón, Facundo'],
            ['name' => 'COM 2.2', 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Legislación', 'teacher_name' => 'Castel, Silvia del Carmen'],
            ['name' => 'COM 2.2', 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'Introducción al Análisis de Datos', 'teacher_name' => 'Roig, Eduardo'],
            ['name' => 'COM 2.2', 'term' => 'Cuarto Cuatrimestre', 'subject_name' => 'TFI', 'teacher_name' => 'Roig, Eduardo'],
        ];

        // 2. Creamos los cursos en la base de datos
        foreach ($coursesData as $courseData) {
            $subject = Subject::where('name', $courseData['subject_name'])->first();
            $teacher = User::where('name', 'like', '%' . explode(', ', $courseData['teacher_name'])[0] . '%')->role('Docente')->first();

            if ($subject && $teacher) {
                Course::create([
                    'name' => $courseData['name'],
                    'year' => 2025,
                    'term' => $courseData['term'],
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                    'capacity' => 40,
                ]);
            } else {
                Log::warning("Seeder: No se pudo crear el curso '{$courseData['subject_name']}'. Materia o docente '{$courseData['teacher_name']}' no encontrado.");
            }
        }

        // 3. Definimos y creamos los bloques horarios para cada curso
        $schedulesData = [
            // COM 1.1
            ['course_name' => 'COM 1.1', 'subject_name' => 'Probabilidad y Estadística', 'day' => 'lunes', 'start' => '13:30', 'end' => '17:45'],
            ['course_name' => 'COM 1.1', 'subject_name' => 'Base de Datos I', 'day' => 'martes', 'start' => '13:30', 'end' => '17:45'],
            ['course_name' => 'COM 1.1', 'subject_name' => 'Programación II', 'day' => 'miercoles', 'start' => '13:30', 'end' => '17:45'],
            ['course_name' => 'COM 1.1', 'subject_name' => 'Programación II', 'day' => 'jueves', 'start' => '13:30', 'end' => '17:45'], // Segundo bloque
            ['course_name' => 'COM 1.1', 'subject_name' => 'Inglés I', 'day' => 'viernes', 'start' => '13:30', 'end' => '17:45'],

            // COM 2.1
            ['course_name' => 'COM 2.1', 'subject_name' => 'Metodología de Sistemas II', 'day' => 'lunes', 'start' => '13:30', 'end' => '17:45'],
            ['course_name' => 'COM 2.1', 'subject_name' => 'Programación IV', 'day' => 'martes', 'start' => '13:30', 'end' => '17:45'],
            ['course_name' => 'COM 2.1', 'subject_name' => 'Programación IV', 'day' => 'jueves', 'start' => '13:30', 'end' => '17:45'], // Segundo bloque
            ['course_name' => 'COM 2.1', 'subject_name' => 'Gestión de Desarrollo de Software', 'day' => 'miercoles', 'start' => '13:30', 'end' => '17:45'],
            ['course_name' => 'COM 2.1', 'subject_name' => 'Introducción al Análisis de Datos', 'day' => 'viernes', 'start' => '13:30', 'end' => '15:30'],
            ['course_name' => 'COM 2.1', 'subject_name' => 'Legislación', 'day' => 'viernes', 'start' => '15:45', 'end' => '17:45'],
            ['course_name' => 'COM 2.1', 'subject_name' => 'TFI', 'day' => 'martes', 'start' => '18:00', 'end' => '20:00'],

            // COM 2.2
            ['course_name' => 'COM 2.2', 'subject_name' => 'Gestión de Desarrollo de Software', 'day' => 'lunes', 'start' => '13:30', 'end' => '17:45'],
            ['course_name' => 'COM 2.2', 'subject_name' => 'Programación IV', 'day' => 'martes', 'start' => '13:30', 'end' => '17:45'],
            ['course_name' => 'COM 2.2', 'subject_name' => 'Programación IV', 'day' => 'jueves', 'start' => '13:30', 'end' => '17:45'], // Segundo bloque
            ['course_name' => 'COM 2.2', 'subject_name' => 'Metodología de Sistemas II', 'day' => 'miercoles', 'start' => '13:30', 'end' => '17:45'],
            ['course_name' => 'COM 2.2', 'subject_name' => 'Legislación', 'day' => 'viernes', 'start' => '13:30', 'end' => '15:30'],
            ['course_name' => 'COM 2.2', 'subject_name' => 'Introducción al Análisis de Datos', 'day' => 'viernes', 'start' => '15:45', 'end' => '17:45'],
            ['course_name' => 'COM 2.2', 'subject_name' => 'TFI', 'day' => 'viernes', 'start' => '18:00', 'end' => '20:00'],
        ];

        foreach ($schedulesData as $scheduleData) {
            // Buscamos el curso al que pertenece este horario
            $course = Course::where('name', $scheduleData['course_name'])
                ->whereHas('subject', function ($query) use ($scheduleData) {
                    $query->where('name', $scheduleData['subject_name']);
                })
                ->first();
            
            if ($course) {
                Schedule::create([
                    'course_id' => $course->id,
                    'day_of_week' => $scheduleData['day'],
                    'start_time' => $scheduleData['start'],
                    'end_time' => $scheduleData['end'],
                ]);
            } else {
                 Log::warning("Seeder: No se pudo crear el horario para '{$scheduleData['subject_name']}'. Curso no encontrado.");
            }
        }
    }
}