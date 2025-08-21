<?php

namespace App\Livewire\Student;

use App\Models\Course;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CourseList extends Component
{
    use WithPagination;

    // Propiedades para los filtros
    public string $search = '';
    public string $subjectFilter = '';

    // Resetea la paginación cuando se aplica un filtro
    public function updatingSearch() { $this->resetPage(); }
    public function updatingSubjectFilter() { $this->resetPage(); }

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