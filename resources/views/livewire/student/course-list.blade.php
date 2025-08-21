<div>
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-secondary dark:text-dark-secondary leading-tight">
                Cursos Disponibles
            </h2>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-neutral-card overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 space-y-8">
                    
                    <!-- Barra de Filtros -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input 
                            wire:model.live.debounce.300ms="search" 
                            type="text" 
                            placeholder="Buscar por curso, materia o docente..."
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @php
                            // Obtenemos los IDs de los cursos en los que el alumno ya está inscrito
                            // Lo hacemos una vez fuera del bucle para optimizar
                            $enrolledCourseIds = auth()->user()->coursesAsStudent()->pluck('course_id')->toArray();
                        @endphp

                        @forelse ($courses as $course)
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
                                        <span><span class="font-bold">{{ $course->capacity }}</span> Cupos</span>
                                        {{-- Lógica de disponibilidad aquí --}}
                                        <span class="font-bold text-accent-success dark:text-dark-accent-success">Disponible</span>
                                    </div>
                                    
                                    <div>
                                        @if(in_array($course->id, $enrolledCourseIds))
                                            <button disabled class="w-full text-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-gray-400 dark:bg-gray-600 cursor-not-allowed">
                                                Inscripto
                                            </button>
                                        @else
                                            <x-primary-button wire:click="enroll({{ $course->id }})" wire:loading.attr="disabled" class="w-full justify-center">
                                                Inscribirme
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