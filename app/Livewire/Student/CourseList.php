<?php

namespace App\Livewire\Student;

use App\Models\Course;
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

    public function updatingSearch() { $this->resetPage(); }
    public function updatingSubjectFilter() { $this->resetPage(); }

    public function enroll(Course $course)
    {
        $user = Auth::user();

        // 1. Verificar si ya está inscrito
        if ($user->coursesAsStudent()->where('course_id', $course->id)->exists()) {
            $this->dispatch('show-toast', ['message' => 'Ya estás inscrito en este curso.']);
            return;
        }

        // 2. Verificar cupos
        $enrolledCount = $course->students()->where('status', 'cursando')->count();
        
        if ($enrolledCount >= $course->capacity) {
            // No hay cupos, lo añadimos a la lista de espera
            $user->coursesAsStudent()->attach($course->id, ['status' => 'lista_de_espera']);
            $this->dispatch('show-toast', ['message' => 'Curso completo. Has sido añadido a la lista de espera.']);
        } else {
            // Hay cupos, lo inscribimos
            $user->coursesAsStudent()->attach($course->id, ['status' => 'cursando']);
            $this->dispatch('show-toast', ['message' => "¡Inscripción exitosa a {$course->subject->name}!"]);
        }
    }

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