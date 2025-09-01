<?php

namespace App\Livewire\Admin\Courses;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CourseManager extends Component
{
    use WithPagination;

    // --- ESTADO GENERAL ---
    public string $search = '';

    // --- ESTADO DEL MODAL PRINCIPAL (CREAR/EDITAR) ---
    public bool $showCourseModal = false;
    public ?Course $editingCourse = null;

    // --- ESTADO DEL MODAL DE NUEVA MATERIA ---
    public bool $showSubjectModal = false;
    public string $newSubjectName = '';

    // --- ESTADO DEL MODAL DE ELIMINAR CURSO ---
    public bool $confirmingCourseDeletion = false;
    public ?Course $courseToDelete = null;

    // --- ESTADO DEL FORMULARIO ---
    public $subject_id;
    public $teacher_id;
    public $name;
    public $year;
    public $term;
    public $capacity;
    public $schedules = [];

    // --- DATOS PARA DROPDOWNS ---
    public $subjects;
    public $teachers;

    public function mount()
    {
        $this->loadFormDependencies();
    }

    protected function loadFormDependencies()
    {
        $this->subjects = Subject::orderBy('name')->get();
        $this->teachers = User::role('Docente')->orderBy('name')->get();
    }
    
    public function updatingSearch() { $this->resetPage(); }

    // --- LÓGICA DE CURSOS ---
    public function createCourse()
    {
        $this->resetForm();
        $this->showCourseModal = true;
    }

    public function editCourse(Course $course)
    {
        $this->resetForm();
        $this->editingCourse = $course;
        $this->subject_id = $course->subject_id;
        $this->teacher_id = $course->teacher_id;
        $this->name = $course->name;
        $this->year = $course->year;
        $this->term = $course->term;
        $this->capacity = $course->capacity;
        $this->schedules = $course->schedules->map->only(['day_of_week', 'start_time', 'end_time'])->toArray();
        $this->showCourseModal = true;
    }

    /**
     * Valida y guarda (crea o actualiza) un curso y sus horarios.
     */
    public function saveCourse()
    {
        // --- REGLAS DE VALIDACIÓN CORREGIDAS ---
        $validated = $this->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'year' => 'required|digits:4|integer|min:2020',
            'term' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'schedules' => 'present|array|min:1',
            'schedules.*.day_of_week' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado',
            'schedules.*.start_time' => 'required|date_format:H:i',
            // La regla 'after' se aplica de forma diferente para cada elemento del array
            'schedules.*.end_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    // Extraemos el índice del array, ej: "schedules.0.end_time" -> 0
                    $index = explode('.', $attribute)[1];
                    $startTime = $this->schedules[$index]['start_time'];
                    if (strtotime($value) <= strtotime($startTime)) {
                        $fail('La hora de fin debe ser posterior a la hora de inicio.');
                    }
                },
            ],
        ], [
            // Mensajes de error personalizados para mayor claridad
            'schedules.*.start_time.required' => 'La hora de inicio es obligatoria.',
            'schedules.*.end_time.required' => 'La hora de fin es obligatoria.',
            'schedules.*.start_time.date_format' => 'El formato de hora no es válido.',
            'schedules.*.end_time.date_format' => 'El formato de hora no es válido.',
        ]);

        $courseData = collect($validated)->except('schedules')->toArray();

        $course = $this->editingCourse 
            ? tap($this->editingCourse)->update($courseData)
            : Course::create($courseData);

        $course->schedules()->delete();
        $course->schedules()->createMany($validated['schedules']);
        
        $this->closeModal();
        $this->dispatch('show-toast', ['message' => 'Curso guardado con éxito.']);
    }

    public function confirmCourseDeletion(Course $course)
    {
        $this->courseToDelete = $course;
        $this->confirmingCourseDeletion = true;
    }

    public function deleteCourse()
    {
        if ($this->courseToDelete) {
            $this->courseToDelete->delete();
            $this->closeModal();
            $this->dispatch('show-toast', ['message' => 'Curso eliminado con éxito.']);
        }
    }

    // --- LÓGICA DE HORARIOS EN EL FORMULARIO ---
    public function addSchedule()
    {
        $this->schedules[] = ['day_of_week' => 'lunes', 'start_time' => '08:00', 'end_time' => '10:00'];
    }

    public function removeSchedule($index)
    {
        unset($this->schedules[$index]);
        $this->schedules = array_values($this->schedules);
    }

    // --- LÓGICA DE CREACIÓN DE MATERIAS ---
    public function createSubject()
    {
        $this->showSubjectModal = true;
    }

    public function saveSubject()
    {
        $validated = $this->validate(['newSubjectName' => 'required|string|max:255|unique:subjects,name']);
        
        Subject::create(['name' => $validated['newSubjectName']]);
        
        $this->closeModal();
        $this->loadFormDependencies(); // Recargamos las materias para que aparezca la nueva
        $this->dispatch('show-toast', ['message' => 'Materia creada con éxito.']);
    }

    // --- GESTIÓN GENERAL DE MODALES Y FORMULARIO ---
    public function closeModal()
    {
        $this->showCourseModal = false;
        $this->showSubjectModal = false;
        $this->confirmingCourseDeletion = false;
        $this->resetForm();
    }
    
    private function resetForm()
    {
        $this->editingCourse = null;
        $this->courseToDelete = null;
        $this->newSubjectName = '';
        $this->subject_id = null;
        $this->teacher_id = null;
        $this->name = '';
        $this->year = date('Y');
        $this->term = '';
        $this->capacity = 40;
        $this->schedules = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        $courses = Course::with(['subject', 'teacher', 'schedules'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('subject', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('teacher', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('name')->paginate(10);
            
        return view('livewire.admin.courses.course-manager', [
            'courses' => $courses,
        ]);
    }
}