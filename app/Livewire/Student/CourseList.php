<?php

namespace App\Livewire\Student;

use App\Models\Course;
use App\Models\Setting;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CourseList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $subjectFilter = '';
    public bool $isEnrollmentPeriodActive = false;

    /**
     * El método mount se ejecuta cuando el componente se inicializa.
     * Aquí verificamos si el período de inscripción está activo.
     */
    public function mount()
    {
        $startDate = Setting::where('key', 'enrollment_start_date')->first()?->value;
        $endDate = Setting::where('key', 'enrollment_end_date')->first()?->value;

        if ($startDate && $endDate) {
            $this->isEnrollmentPeriodActive = now()->between($startDate, $endDate);
        }
    }

    /**
     * Estos métodos se ejecutan cada vez que una propiedad con wire:model cambia.
     * Resetean la paginación para evitar bugs al filtrar.
     */
    public function updatingSearch() { $this->resetPage(); }
    public function updatingSubjectFilter() { $this->resetPage(); }
    
    /**
     * Inscribe al alumno en un curso, aplicando todas las reglas de negocio.
     */
    public function enroll(Course $course)
    {
        $user = Auth::user();

        // Regla 0: Verificar si el período de inscripción está activo
        if (!$this->isEnrollmentPeriodActive) {
            $this->dispatch('show-toast', ['message' => 'El período de inscripción no está activo.', 'type' => 'error']);
            return;
        }

        // Regla 1: Contar las inscripciones activas del alumno.
        $currentEnrollmentsCount = $user->coursesAsStudent()->where('enrollments.status', 'cursando')->count();
        if ($currentEnrollmentsCount >= 6) {
            $this->dispatch('show-toast', ['message' => 'Has alcanzado el límite de 6 inscripciones.', 'type' => 'error']);
            return;
        }

        // Regla 2: Verificar si ya está inscrito en otra comisión de la misma materia.
        $enrolledSubjectIds = $user->coursesAsStudent()
                                  ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                                  ->pluck('subjects.id');
                                  
        if ($enrolledSubjectIds->contains($course->subject_id)) {
            $this->dispatch('show-toast', ['message' => 'Ya estás inscrito en otra comisión de esta materia.', 'type' => 'error']);
            return;
        }
        
        // Regla 3: Verificar cupos.
        $enrolledCount = $course->students()->where('enrollments.status', 'cursando')->count();
        
        if ($enrolledCount >= $course->capacity) {
            $user->coursesAsStudent()->attach($course->id, ['status' => 'lista_de_espera']);
            $this->dispatch('show-toast', ['message' => 'Curso completo. Has sido añadido a la lista de espera.']);
        } else {
            $user->coursesAsStudent()->attach($course->id, ['status' => 'cursando']);
            $this->dispatch('show-toast', ['message' => "¡Inscripción exitosa a {$course->subject->name}!"]);
        }
    }

    /**
     * Da de baja al alumno de un curso.
     */
    public function withdraw(Course $course)
    {
        $user = Auth::user();

        if (!$this->isEnrollmentPeriodActive) {
            $this->dispatch('show-toast', ['message' => 'No puedes darte de baja fuera del período de inscripción.', 'type' => 'error']);
            return;
        }

        $user->coursesAsStudent()->detach($course->id);
        $this->dispatch('show-toast', ['message' => "Te has dado de baja de {$course->subject->name}."]);
    }

    /**
     * Renderiza el componente con los datos necesarios para la vista.
     */
    public function render()
    {
        $courses = Course::with(['subject', 'teacher'])
            // Filtro por nombre de materia O nombre de docente
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('subject', function ($subQuery) {
                          $subQuery->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('teacher', function ($teacherQuery) {
                          $teacherQuery->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            // Filtro por materia específica (dropdown)
            ->when($this->subjectFilter, function ($query) {
                $query->where('subject_id', $this->subjectFilter);
            })
            ->orderBy('year', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(12); // Paginamos de a 12 para que se vea bien en un grid

        // Obtenemos todas las materias para el dropdown de filtro
        $subjects = Subject::orderBy('name')->get();

        return view('livewire.student.course-list', [
            'courses' => $courses,
            'subjects' => $subjects,
        ]);
    }
}
