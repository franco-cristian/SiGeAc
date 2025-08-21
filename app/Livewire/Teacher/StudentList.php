<?php

namespace App\Livewire\Teacher;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class StudentList extends Component
{
    use WithPagination;

    public function render()
    {
        $teacher = Auth::user();

        // Obtenemos los IDs de los alumnos inscritos en los cursos del docente
        $studentIds = $teacher->coursesAsTeacher()
            ->with('students')
            ->get()
            ->pluck('students.*.id')
            ->flatten()
            ->unique();
        
        // Obtenemos los modelos de usuario de esos alumnos y los paginamos
        $students = User::whereIn('id', $studentIds)
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.teacher.student-list', [
            'students' => $students
        ]);
    }
}