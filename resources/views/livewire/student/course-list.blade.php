<div>
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-secondary dark:text-dark-secondary leading-tight">
                Inscripción a Cursos
            </h2>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-neutral-card overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 space-y-8">
                    
                    <!-- Aviso de Período de Inscripción -->
                    @if (!$isEnrollmentPeriodActive)
                        <div class="p-4 rounded-md bg-yellow-50 dark:bg-yellow-800/20">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <x-lucide-alert-triangle class="h-5 w-5 text-yellow-400 dark:text-yellow-500" />
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">
                                        Período de Inscripción Cerrado
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-400">
                                        <p>
                                            El período para inscribirse o darse de baja de cursos no está activo actualmente.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Barra de Filtros -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input 
                            wire:model.live.debounce.300ms="search" 
                            type="text" 
                            placeholder="Buscar por curso, materia, comisión o docente..."
                            class="md:col-span-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-dark-primary focus:ring-primary dark:focus:ring-dark-primary rounded-md shadow-sm w-full"
                        >
                        <select wire:model.live="subjectFilter" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-dark-primary focus:ring-primary dark:focus:ring-dark-primary rounded-md shadow-sm w-full">
                            <option value="">Todas las Materias</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Grid de Cursos -->
                    @php
                        $user = auth()->user();
                        $enrolledCourses = $user->coursesAsStudent()->withPivot('status')->get();
                        $enrolledCourseIds = $enrolledCourses->pluck('id')->toArray();
                        $enrolledSubjectIds = $enrolledCourses->pluck('subject_id')->toArray();
                        $enrollmentCount = $enrolledCourses->where('pivot.status', 'cursando')->count();
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($courses as $course)
                            @php
                                $isEnrolled = in_array($course->id, $enrolledCourseIds);
                                $subjectAlreadyTaken = !$isEnrolled && in_array($course->subject_id, $enrolledSubjectIds);
                                
                                $enrolledStudentsCount = $course->students->where('pivot.status', 'cursando')->count();
                                $availableSlots = $course->capacity - $enrolledStudentsCount;
                                $isFull = $availableSlots <= 0;

                                $canEnroll = $isEnrollmentPeriodActive && $enrollmentCount < 6 && !$subjectAlreadyTaken;
                            @endphp

                            <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg shadow-md flex flex-col justify-between hover:shadow-lg hover:scale-[1.02] transition-all duration-300">
                                <div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-primary/10 text-primary dark:bg-dark-primary/20 dark:text-dark-primary">{{ $course->name }} - {{ $course->year }}</span>
                                    <h3 class="mt-4 font-bold text-lg text-secondary dark:text-dark-secondary">{{ $course->subject->name }}</h3>
                                    <p class="mt-2 text-sm text-neutral-text dark:text-dark-neutral-text">
                                        <span class="font-semibold">Docente:</span> {{ $course->teacher->name ?? 'No asignado' }}
                                    </p>
                                    <p class="text-sm text-neutral-text dark:text-dark-neutral-text">
                                        <span class="font-semibold">Período:</span> {{ $course->term }}
                                    </p>
                                </div>
                                <div class="mt-6 space-y-4">
                                    <div class="flex justify-between items-center text-sm text-neutral-text dark:text-dark-neutral-text">
                                        <span><span class="font-bold">{{ $availableSlots > 0 ? $availableSlots : 0 }}</span> Cupos Disp.</span>
                                        @if($isFull)
                                            <span class="font-bold text-yellow-500 dark:text-yellow-400">Lista de Espera</span>
                                        @else
                                            <span class="font-bold text-accent-success dark:text-dark-accent-success">Disponible</span>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        @if($isEnrolled)
                                            <x-danger-button wire:click="withdraw({{ $course->id }})" class="w-full justify-center" :disabled="!$isEnrollmentPeriodActive">
                                                Darme de Baja
                                            </x-danger-button>
                                        @else
                                            <x-primary-button wire:click="enroll({{ $course->id }})" wire:loading.attr="disabled" class="w-full justify-center" :disabled="!$canEnroll">
                                                @if(!$isEnrollmentPeriodActive)
                                                    Inscripción Cerrada
                                                @elseif($enrollmentCount >= 6)
                                                    Límite Alcanzado
                                                @elseif($subjectAlreadyTaken)
                                                    Materia ya Inscripta
                                                @elseif($isFull)
                                                    Anotarse en Espera
                                                @else
                                                    Inscribirme
                                                @endif
                                            </x-primary-button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="sm:col-span-2 lg:col-span-3 text-center py-12">
                                <p class="text-neutral-text dark:text-dark-neutral-text">No se encontraron cursos que coincidan con tu búsqueda.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Paginación -->
                    <div class="mt-6">
                        {{ $courses->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>