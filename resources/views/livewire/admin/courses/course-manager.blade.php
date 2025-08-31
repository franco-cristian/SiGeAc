<div>
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                <h2 class="font-semibold text-xl text-secondary dark:text-dark-secondary leading-tight">
                    Gestión de Cursos
                </h2>
                <div class="flex items-center space-x-2">
                    <x-secondary-button wire:click="createSubject">
                        <x-lucide-plus class="w-4 h-4 mr-2" />
                        Nueva Materia
                    </x-secondary-button>
                    <x-primary-button wire:click="createCourse">
                        <x-lucide-plus class="w-4 h-4 mr-2" />
                        Nuevo Curso
                    </x-primary-button>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-neutral-card overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 space-y-6">

                    <!-- Barra de Búsqueda -->
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Buscar por comisión, materia o docente..."
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-dark-primary focus:ring-primary dark:focus:ring-dark-primary rounded-md shadow-sm">

                    <!-- Contenedor de la Lista -->
                    <div class="overflow-x-auto">
                        <!-- Tabla para Escritorio -->
                        <table class="hidden md:table w-full text-left table-auto">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="w-2/5 px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Curso / Comisión</th>
                                    <th class="w-1/5 px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Docente</th>
                                    <th class="w-1/5 px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Inscriptos / Espera</th>
                                    <th class="w-1/12 px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cupo</th>
                                    <th class="w-auto px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-dark-neutral-card divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($courses as $course)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                    <td class="px-4 py-4 align-top">
                                        <a href="{{ route('admin.courses.enrollments', $course) }}" class="font-medium text-primary dark:text-dark-primary hover:underline">
                                            {{ $course->subject->name }}
                                        </a>
                                        <p class="text-sm text-neutral-text dark:text-dark-neutral-text">{{ $course->name }} ({{ $course->year }}) - {{ $course->term }}</p>
                                    </td>
                                    <td class="px-4 py-4 align-top text-sm text-gray-500 dark:text-gray-400">{{ $course->teacher->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-4 align-top text-sm text-gray-500 dark:text-gray-400">
                                        @php
                                        $enrolledCount = $course->students->where('pivot.status', 'cursando')->count();
                                        $waitingCount = $course->students->where('pivot.status', 'lista_de_espera')->count();
                                        @endphp
                                        <span>{{ $enrolledCount }} Inscriptos</span>
                                        @if($waitingCount > 0)
                                        <span class="ml-2 px-2 py-1 text-xs font-bold text-yellow-800 bg-yellow-200 dark:text-yellow-200 dark:bg-yellow-800/50 rounded-full">
                                            {{ $waitingCount }} en Espera
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 align-top text-sm text-gray-500 dark:text-gray-400">{{ $course->capacity }}</td>
                                    <td class="px-4 py-4 align-top text-center text-sm font-medium space-x-2">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.courses.enrollments', $course) }}" title="Gestionar Inscriptos" class="inline-block p-1 text-gray-400 hover:text-green-600 dark:hover:text-green-400 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                                <x-lucide-users class="h-5 w-5" />
                                            </a>
                                            <button wire:click="editCourse({{ $course->id }})" title="Editar Curso" class="p-1 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"><x-lucide-file-pen-line class="h-5 w-5" /></button>
                                            <button wire:click="confirmCourseDeletion({{ $course->id }})" title="Eliminar Curso" class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"><x-lucide-trash-2 class="h-5 w-5" /></button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No se encontraron cursos.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Tarjetas para Móvil -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:hidden">
                            @forelse ($courses as $course)
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow space-y-3">
                                <div>
                                    <a href="{{ route('admin.courses.enrollments', $course) }}" class="font-bold text-primary dark:text-dark-primary hover:underline">
                                        {{ $course->subject->name }}
                                    </a>
                                    <p class="text-sm text-neutral-text dark:text-dark-neutral-text">{{ $course->name }} ({{ $course->year }})</p>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400"><strong>Docente:</strong> {{ $course->teacher->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    @php
                                    $enrolledCount = $course->students->where('pivot.status', 'cursando')->count();
                                    $waitingCount = $course->students->where('pivot.status', 'lista_de_espera')->count();
                                    @endphp
                                    <span><strong>Inscriptos:</strong> {{ $enrolledCount }} / {{ $course->capacity }}</span>
                                    @if($waitingCount > 0)
                                    <span class="ml-2 px-2 py-1 text-xs font-bold text-yellow-800 bg-yellow-200 dark:text-yellow-200 dark:bg-yellow-800/50 rounded-full">
                                        {{ $waitingCount }} en Espera
                                    </span>
                                    @endif
                                </div>

                                <div class="flex justify-end space-x-2 border-t border-gray-200 dark:border-gray-600 pt-3">
                                    <a href="{{ route('admin.courses.enrollments', $course) }}" title="Gestionar Inscriptos" class="p-1 text-gray-400 hover:text-green-600 ..."><x-lucide-users class="h-5 w-5" /></a>
                                    <button wire:click="editCourse({{ $course->id }})" title="Editar Curso" class="p-1 text-gray-400 hover:text-blue-600 ..."><x-lucide-file-pen-line class="h-5 w-5" /></button>
                                    <button wire:click="confirmCourseDeletion({{ $course->id }})" title="Eliminar Curso" class="p-1 text-gray-400 hover:text-red-600 ..."><x-lucide-trash-2 class="h-5 w-5" /></button>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-gray-500 dark:text-gray-400">No se encontraron cursos.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-6">{{ $courses->links() }}</div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal para Crear/Editar Curso -->
@if($showCourseModal)
<div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-secondary dark:text-dark-secondary">
                {{ $editingCourse ? 'Editar Curso' : 'Crear Nuevo Curso' }}
            </h3>
        </div>
        
        <!-- El formulario solo envuelve el cuerpo y el footer -->
        <form wire:submit="saveCourse" class="flex flex-col flex-grow overflow-hidden">
            <!-- Modal Body (con scroll) -->
            <div class="px-6 py-4 space-y-4 flex-grow overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="subject_id" value="Materia" />
                        <select wire:model.live="subject_id" id="subject_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-dark-primary focus:ring-primary dark:focus:ring-dark-primary rounded-md shadow-sm">
                            <option value="">Seleccione una materia...</option>
                            @foreach($subjects as $subject) <option value="{{ $subject->id }}">{{ $subject->name }}</option> @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('subject_id')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="teacher_id" value="Docente" />
                        <select wire:model.live="teacher_id" id="teacher_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-dark-primary focus:ring-primary dark:focus:ring-dark-primary rounded-md shadow-sm">
                            <option value="">Seleccione un docente...</option>
                            @foreach($teachers as $teacher) <option value="{{ $teacher->id }}">{{ $teacher->name }}</option> @endforeach
                        </select>
                         <x-input-error :messages="$errors->get('teacher_id')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="name" value="Nombre Comisión (ej: COM 2.1)" />
                        <x-text-input wire:model="name" id="name" class="mt-1 w-full" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="term" value="Período (ej: Cuarto Cuatrimestre)" />
                        <x-text-input wire:model="term" id="term" class="mt-1 w-full" />
                        <x-input-error :messages="$errors->get('term')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="year" value="Año" />
                        <x-text-input wire:model="year" id="year" type="number" class="mt-1 w-full" />
                         <x-input-error :messages="$errors->get('year')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="capacity" value="Cupo" />
                        <x-text-input wire:model="capacity" id="capacity" type="number" class="mt-1 w-full" />
                        <x-input-error :messages="$errors->get('capacity')" class="mt-1" />
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex justify-between items-center">
                        <h4 class="font-semibold text-secondary dark:text-dark-secondary">Bloques Horarios</h4>
                        <x-secondary-button type="button" wire:click="addSchedule"><x-lucide-plus class="w-4 h-4"/></x-secondary-button>
                    </div>
                    <div class="mt-4 space-y-4">
                        @forelse($schedules as $index => $schedule)
                            <div wire:key="schedule-{{ $index }}">
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-center">
                                    <div>
                                        <x-input-label value="Día" class="text-xs" />
                                        <select wire:model="schedules.{{ $index }}.day_of_week" class="mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-dark-primary focus:ring-primary dark:focus:ring-dark-primary rounded-md shadow-sm">
                                            <option value="lunes">Lunes</option><option value="martes">Martes</option><option value="miercoles">Miércoles</option><option value="jueves">Jueves</option><option value="viernes">Viernes</option><option value="sabado">Sábado</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label value="Hora Inicio" class="text-xs" />
                                        <x-text-input wire:model="schedules.{{ $index }}.start_time" type="time" class="mt-1 w-full"/>
                                    </div>
                                    <div>
                                        <x-input-label value="Hora Fin" class="text-xs" />
                                        <x-text-input wire:model="schedules.{{ $index }}.end_time" type="time" class="mt-1 w-full"/>
                                    </div>
                                    <div class="pt-5 text-center">
                                        <x-danger-button type="button" wire:click="removeSchedule({{ $index }})"><x-lucide-trash-2 class="w-4 h-4"/></x-danger-button>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('schedules.'.$index.'.day_of_week')" class="mt-1" />
                                <x-input-error :messages="$errors->get('schedules.'.$index.'.start_time')" class="mt-1" />
                                <x-input-error :messages="$errors->get('schedules.'.$index.'.end_time')" class="mt-1" />
                            </div>
                        @empty
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400">Añade al menos un bloque horario.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Modal Footer (Fijo) -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 rounded-b-lg border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-4">
                <x-secondary-button type="button" wire:click="closeModal">Cancelar</x-secondary-button>
                <x-primary-button type="submit" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveCourse">Guardar Curso</span>
                    <span wire:loading wire:target="saveCourse">Guardando...</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endif

    <!-- Modal para Crear Nueva Materia -->
    @if($showSubjectModal)
    <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-bold text-secondary dark:text-dark-secondary">Crear Nueva Materia</h3>
            <form wire:submit="saveSubject" class="mt-6 space-y-4">
                <div>
                    <x-input-label for="newSubjectName" value="Nombre de la Materia" />
                    <x-text-input wire:model="newSubjectName" id="newSubjectName" class="mt-1 w-full" autofocus />
                    <x-input-error :messages="$errors->get('newSubjectName')" class="mt-1" />
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <x-secondary-button type="button" wire:click="closeModal">Cancelar</x-secondary-button>
                    <x-primary-button type="submit">Crear Materia</x-primary-button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Modal de Confirmación de Eliminación de Curso -->
    @if($confirmingCourseDeletion)
    <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-bold text-secondary dark:text-dark-secondary">Confirmar Eliminación</h3>
            <p class="mt-2 text-neutral-text dark:text-dark-neutral-text">
                ¿Estás seguro de que quieres eliminar el curso <strong class="font-bold">{{ $courseToDelete?->subject->name }} - {{ $courseToDelete?->name }}</strong>? Esta acción no se puede deshacer.
            </p>
            <div class="mt-6 flex justify-end space-x-4">
                <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
                <x-danger-button wire:click="deleteCourse">Sí, Eliminar</x-danger-button>
            </div>
        </div>
    </div>
    @endif
</div>