<?php

namespace App\Livewire\Student;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public User $user;
    public array $schedule = []; // Propiedad para guardar el horario organizado
    public ?Course $selectedCourse = null; // Para el modal de detalles del curso
    public ?User $selectedTeacher = null; // Para el modal de detalles del profesor

    /**
     * El método mount se ejecuta una sola vez al inicializar el componente.
     */
    public function mount()
    {
        $this->user = Auth::user();
        $this->buildSchedule();
    }

    /**
     * Construye una estructura de datos del horario del alumno a partir de sus cursos inscriptos.
     */
    protected function buildSchedule()
    {
        // Obtenemos los cursos en los que el alumno está inscripto y que están activos ("cursando")
        // Precargamos las relaciones 'schedules', 'subject' y 'teacher' para optimizar las consultas (evitar N+1)
        $enrolledCourses = $this->user->coursesAsStudent()
            ->with(['schedules', 'subject', 'teacher'])
            ->where('status', 'cursando')
            ->get();
        
        // Inicializamos la estructura del horario con los días de la semana vacíos
        $scheduleData = [
            'lunes' => [],
            'martes' => [],
            'miercoles' => [],
            'jueves' => [],
            'viernes' => [],
        ];

        // Recorremos cada curso inscrito
        foreach ($enrolledCourses as $course) {
            // Recorremos cada bloque horario de ese curso
            foreach ($course->schedules as $schedule) {
                // Verificamos si el día de la semana existe en nuestra estructura
                if (array_key_exists($schedule->day_of_week, $scheduleData)) {
                    // Añadimos el objeto de horario a la lista del día correspondiente
                    // Incluimos una referencia al curso completo para tener todos los datos
                    $scheduleData[$schedule->day_of_week][] = [
                        'course' => $course,
                        'schedule' => $schedule,
                    ];
                }
            }
        }
        
        // Ordenamos los bloques horarios de cada día por hora de inicio
        foreach ($scheduleData as $day => &$slots) {
            usort($slots, function ($a, $b) {
                return strtotime($a['schedule']->start_time) - strtotime($b['schedule']->start_time);
            });
        }

        $this->schedule = $scheduleData;
    }

    /**
     * Métodos para manejar los modales interactivos.
     */
    public function showCourseDetails(int $courseId)
    {
        $this->selectedCourse = Course::with(['subject', 'teacher', 'schedules'])->find($courseId);
    }
    
    public function showTeacherDetails(int $teacherId)
    {
        $this->selectedTeacher = User::find($teacherId);
    }

    public function closeModal()
    {
        $this->selectedCourse = null;
        $this->selectedTeacher = null;
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        return view('livewire.student.dashboard');
    }
}