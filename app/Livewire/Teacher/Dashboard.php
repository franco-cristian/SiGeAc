<?php

namespace App\Livewire\Teacher;

use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    use WithPagination;

    // --- PROPIEDADES PARA FILTROS Y ESTADO ---
    public $courses; // Cursos del docente
    public $selectedCourseId = null;
    public $search = '';
    public $perPage = 10;
    public $showPhotos = true; // Toggle para mostrar/ocultar fotos

    // --- PROPIEDADES PARA MODALES ---
    public ?User $studentToView = null;
    public ?User $studentToUnenroll = null;

    /**
     * Se ejecuta una vez, al cargar el componente.
     */
    public function mount()
    {
        // Cargamos los cursos del docente para el dropdown de filtro
        $this->courses = Auth::user()->coursesAsTeacher()->with('subject')->get();
        
        // Si el docente tiene cursos, seleccionamos el primero por defecto
        if ($this->courses->isNotEmpty()) {
            $this->selectedCourseId = $this->courses->first()->id;
        }
    }
    
    /**
     * Resetea la paginación cuando un filtro cambia.
     */
    public function updating($property)
    {
        if (in_array($property, ['selectedCourseId', 'search', 'perPage'])) {
            $this->resetPage();
        }
    }

    // --- LÓGICA DE ACCIONES ---

    public function viewStudentProfile(User $student)
    {
        $this->studentToView = $student;
    }

    public function confirmUnenrollStudent(User $student)
    {
        $this->studentToUnenroll = $student;
    }

    public function unenrollStudent()
    {
        if ($this->studentToUnenroll && $this->selectedCourseId) {
            $course = Course::find($this->selectedCourseId);
            $course->students()->detach($this->studentToUnenroll->id);

            $this->closeModal();
            $this->dispatch('show-toast', ['message' => "{$this->studentToUnenroll->name} ha sido desinscrito del curso."]);
        }
    }

    public function closeModal()
    {
        $this->studentToView = null;
        $this->studentToUnenroll = null;
    }

    /**
     * Renderiza la vista con los datos filtrados.
     */
    public function render()
    {
        $students = collect(); // Colección vacía por defecto
        
        if ($this->selectedCourseId) {
            $students = User::whereHas('coursesAsStudent', function ($query) {
                    $query->where('course_id', $this->selectedCourseId);
                })
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%')
                          ->orWhere('dni', 'like', '%' . $this->search . '%')
                          ->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
                })
                ->orderBy('name')
                ->paginate($this->perPage);
        }

        return view('livewire.teacher.dashboard', [
            'students' => $students,
            'selectedCourse' => Course::with('subject')->find($this->selectedCourseId)
        ]);
    }
}