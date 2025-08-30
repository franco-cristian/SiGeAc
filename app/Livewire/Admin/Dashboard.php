<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    // Propiedades para almacenar las estadísticas
    public $totalStudents;
    public $totalTeachers;
    public $totalCourses;
    public $mostPopularCourse;

    /**
     * El método mount se ejecuta una vez al cargar el componente.
     * para calcular estadísticas.
     */
    public function mount()
    {
        // Contamos usuarios asignados al rol 'Alumno'
        $this->totalStudents = User::role('Alumno')->count();
        
        // Contamos usuarios asignados al rol 'Docente'
        $this->totalTeachers = User::role('Docente')->count();

        // Contamos el total de cursos activos
        $this->totalCourses = Course::count();

        // Buscamos el curso con el mayor número de estudiantes inscriptos.
        // withCount('students') añade una columna virtual 'students_count' a cada curso.
        $this->mostPopularCourse = Course::with('subject')
            ->withCount('students')
            ->orderBy('students_count', 'desc')
            ->first();
    }

    /**
     * Renderiza la vista del componente.
     * Livewire pasará automáticamente las propiedades públicas a la vista.
     */
    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}