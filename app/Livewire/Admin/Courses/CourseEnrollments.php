<?php

namespace App\Livewire\Admin\Courses;

use App\Models\Course;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class CourseEnrollments extends Component
{
    public Course $course;

    // --- PROPIEDAD PARA EL MODAL DE CONFIRMACIÓN ---
    public ?User $studentToUnenroll = null;

    public function mount(Course $course)
    {
        $this->course = $course->load(['subject', 'students.roles']);
    }

    public function increaseCapacity()
    {
        $this->course->increment('capacity');
        $this->course->refresh();
        $this->dispatch('show-toast', ['message' => 'El cupo ha sido aumentado en 1.']);
    }

    public function promoteStudent(int $studentId)
    {
        $enrolledCount = $this->course->students()->where('status', 'cursando')->count();

        if ($enrolledCount >= $this->course->capacity) {
            $this->dispatch('show-toast', ['message' => 'No hay cupos disponibles. Aumente la capacidad primero.', 'type' => 'error']);
            return;
        }

        $this->course->students()->updateExistingPivot($studentId, [
            'status' => 'cursando'
        ]);

        $this->course->load('students.roles');
        $this->dispatch('show-toast', ['message' => 'Alumno promovido a "cursando" con éxito.']);
    }
    
    // --- LÓGICA PARA CONFIRMAR ANTES DE DESINSCRIBIR ---
    public function confirmUnenrollStudent(int $studentId)
    {
        $this->studentToUnenroll = User::find($studentId);
    }

    public function unenrollStudent()
    {
        if ($this->studentToUnenroll) {
            $studentName = $this->studentToUnenroll->name;
            $this->course->students()->detach($this->studentToUnenroll->id);
            $this->course->load('students.roles');
            $this->closeModal();
            $this->dispatch('show-toast', ['message' => "Alumno '{$studentName}' desinscrito con éxito."]);
        }
    }

    public function closeModal()
    {
        $this->studentToUnenroll = null;
    }

    public function render()
    {
        $enrolledStudents = $this->course->students->where('pivot.status', 'cursando')->sortBy('name');
        $waitingListStudents = $this->course->students->where('pivot.status', 'lista_de_espera')->sortBy('pivot.created_at');

        return view('livewire.admin.courses.course-enrollments', [
            'enrolledStudents' => $enrolledStudents,
            'waitingListStudents' => $waitingListStudents,
        ]);
    }
}