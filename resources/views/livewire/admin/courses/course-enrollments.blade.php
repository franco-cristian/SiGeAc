<div>
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.courses.index') }}" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                    <x-lucide-arrow-left class="w-5 h-5 text-secondary dark:text-dark-secondary" />
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-secondary dark:text-dark-secondary leading-tight">
                        Gestionar Inscriptos
                    </h2>
                    <p class="text-sm text-neutral-text dark:text-dark-neutral-text">
                        {{ $course->subject->name }} - {{ $course->name }}
                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @php
                $enrolledCount = $enrolledStudents->count();
                $hasAvailableSlots = $enrolledCount < $course->capacity;
                    @endphp

                    <!-- Columna: Alumnos Inscriptos -->
                    <div class="bg-white dark:bg-dark-neutral-card shadow-xl rounded-lg">
                        <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex flex-wrap justify-between items-center gap-4">
                            <h3 class="text-lg font-semibold text-secondary dark:text-dark-secondary">
                                Alumnos Inscriptos ({{ $enrolledCount }} / {{ $course->capacity }})
                            </h3>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-neutral-text dark:text-dark-neutral-text">Aumentar cupo:</span>
                                <x-secondary-button wire:click="increaseCapacity" title="Aumentar el cupo en 1">
                                    <x-lucide-plus class="w-4 h-4" />
                                </x-secondary-button>
                            </div>
                        </div>
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[60vh] overflow-y-auto">
                            @forelse($enrolledStudents as $student)
                            <li class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                <div class="flex items-center space-x-4">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $student->photo_path ? route('users.photo', $student) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) }}" alt="{{ $student->name }}">
                                    <div>
                                        <p class="font-medium text-secondary dark:text-dark-secondary">{{ $student->name }}</p>
                                        <p class="text-sm text-neutral-text dark:text-dark-neutral-text">{{ $student->email }}</p>
                                    </div>
                                </div>
                                <x-danger-button wire:click="confirmUnenrollStudent({{ $student->id }})" title="Desinscribir">
                                    <x-lucide-user-x class="w-4 h-4" />
                                </x-danger-button>
                            </li>
                            @empty
                            <li class="p-4 text-center text-sm text-neutral-text dark:text-dark-neutral-text">No hay alumnos inscriptos.</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Columna: Lista de Espera -->
                    <div class="bg-white dark:bg-dark-neutral-card shadow-xl rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-secondary dark:text-dark-secondary">
                                Lista de Espera ({{ $waitingListStudents->count() }})
                            </h3>
                        </div>
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[60vh] overflow-y-auto">
                            @forelse($waitingListStudents as $student)
                            <li class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                <div class="flex items-center space-x-4">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $student->photo_path ? route('users.photo', $student) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) }}" alt="{{ $student->name }}">
                                    <div>
                                        <p class="font-medium text-secondary dark:text-dark-secondary">{{ $student->name }}</p>
                                        <p class="text-sm text-neutral-text dark:text-dark-neutral-text">{{ $student->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <x-primary-button
                                        wire:click="promoteStudent({{ $student->id }})"
                                        title="{{ $hasAvailableSlots ? 'Promover a Inscripto' : 'No hay cupos disponibles' }}"
                                        :disabled="!$hasAvailableSlots">
                                        <x-lucide-user-check class="w-4 h-4" />
                                    </x-primary-button>
                                    <x-danger-button wire:click="confirmUnenrollStudent({{ $student->id }})" title="Quitar de la lista">
                                        <x-lucide-user-x class="w-4 h-4" />
                                    </x-danger-button>
                                </div>
                            </li>
                            @empty
                            <li class="p-4 text-center text-sm text-neutral-text dark:text-dark-neutral-text">La lista de espera está vacía.</li>
                            @endforelse
                        </ul>
                    </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Desinscripción -->
    @if($studentToUnenroll)
    <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
        <div @click.away="$wire.closeModal()" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-bold text-secondary dark:text-dark-secondary">Confirmar Desinscripción</h3>
            <p class="mt-2 text-neutral-text dark:text-dark-neutral-text">
                ¿Estás seguro de que quieres desinscribir a <strong class="font-bold">{!! $studentToUnenroll->name !!}</strong> de este curso?
            </p>
            <div class="mt-6 flex justify-end space-x-4">
                <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
                <x-danger-button wire:click="unenrollStudent">Sí, Desinscribir</x-danger-button>
            </div>
        </div>
    </div>
    @endif
</div>