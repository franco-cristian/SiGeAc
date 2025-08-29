<div>
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-secondary dark:text-dark-secondary leading-tight">
                Panel del Docente
            </h2>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-neutral-card overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 space-y-6">

                    @if($courses->isEmpty())
                    <p class="text-center text-neutral-text dark:text-dark-neutral-text">Aún no tienes cursos asignados.</p>
                    @else

                    <!-- Barra de Filtros y Acciones -->
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
                        <div class="w-full md:w-auto md:min-w-[250px]">
                            <select wire:model.live="selectedCourseId" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 ... rounded-md shadow-sm">
                                @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->subject->name }} - {{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-grow">
                            <input
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                placeholder="Buscar por nombre, email, DNI o teléfono..."
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 ... rounded-md shadow-sm">
                        </div>
                        <div class="flex items-center space-x-4 justify-end">
                            <select wire:model.live="perPage" class="w-auto border-gray-300 dark:border-gray-700 dark:bg-gray-900 ... rounded-md shadow-sm">
                                <option value="10">10 / pág</option>
                                <option value="25">25 / pág</option>
                                <option value="50">50 / pág</option>
                            </select>
                            <label class="flex items-center text-sm ... whitespace-nowrap">
                                <input type="checkbox" wire:model.live="showPhotos" class="rounded text-primary ...">
                                <span class="ml-2">Fotos</span>
                            </label>
                        </div>
                    </div>

                    @if($selectedCourse)
                    <h3 class="text-lg font-bold ...">Alumnos de: {{ $selectedCourse->subject->name }} - {{ $selectedCourse->name }}</h3>
                    @endif

                    <div class="overflow-x-auto">
                        <!-- Tabla para Escritorio con Anchos Ajustados -->
                        <table class="hidden md:table w-full text-left table-auto">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    @if($showPhotos) <th class="w-16 px-4 py-3 ...">Foto</th> @endif
                                    <th class="w-1/4 px-4 py-3 ...">Nombre</th>
                                    <th class="w-1/4 px-4 py-3 ...">Email</th>
                                    <th class="w-28 px-4 py-3 ...">Teléfono</th>
                                    <th class="w-28 px-4 py-3 ...">DNI</th>
                                    <th class="w-20 px-4 py-3 ... text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($students as $student)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                    @if($showPhotos)
                                    <td class="px-4 py-2"><img class="h-10 w-10 rounded-full object-cover" src="{{ $student->photo_path ? route('users.photo', $student) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) }}" alt="{{ $student->name }}"></td>
                                    @endif
                                    <td class="px-4 py-2"><button wire:click="viewStudentProfile({{ $student->id }})" class="font-medium text-primary dark:text-dark-primary hover:underline">{{ $student->name }}</button></td>
                                    <td class="px-4 py-2 text-sm ..."><a href="mailto:{{ $student->email }}" class="hover:underline">{{ $student->email }}</a></td>
                                    <td class="px-4 py-2 text-sm ...">@if($student->phone)<a href="https://wa.me/549{{ $student->phone }}" target="_blank" class="hover:underline">{{ $student->phone }}</a>@endif</td>
                                    <td class="px-4 py-2 text-sm ...">{{ $student->dni }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <button wire:click="confirmUnenrollStudent({{ $student->id }})" class="p-1 text-gray-400 hover:text-red-600 ..."><x-lucide-user-x class="h-5 w-5" /></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ $showPhotos ? 6 : 5 }}" class="px-6 py-4 text-center ...">No se encontraron alumnos.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Tarjetas para Móvil -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:hidden">
                            @forelse ($students as $student)
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow">
                                <div class="flex items-center space-x-4">
                                    @if($showPhotos) <img class="h-12 w-12 rounded-full object-cover" src="{{ $student->photo_path ? route('users.photo', $student) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) }}" alt="{{ $student->name }}"> @endif
                                    <div class="flex-1">
                                        <button wire:click="viewStudentProfile({{ $student->id }})" class="font-semibold text-left text-secondary dark:text-dark-secondary hover:underline">{{ $student->name }}</button>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600 space-y-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400"><strong>DNI:</strong> {{ $student->dni }}</p>
                                    @if($student->phone)
                                    <p class="text-sm text-gray-500 dark:text-gray-400"><strong>Teléfono:</strong> <a href="https://wa.me/549{{ $student->phone }}" target="_blank" class="hover:underline">{{ $student->phone }}</a></p>
                                    @endif
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <button wire:click="confirmUnenrollStudent({{ $student->id }})" class="p-1 text-gray-400 hover:text-red-600 ..."><x-lucide-user-x class="h-5 w-5" /></button>
                                </div>
                            </div>
                            @empty
                            <p class="text-center ...">No se encontraron alumnos.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-6">{{ $students->links() }}</div>

                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Ver Perfil de Alumno -->
    @if($studentToView)
    <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
        <div @click.away="closeModal()" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg">
            {{-- Aquí reutilizamos la lógica del widget de perfil del dashboard del alumno --}}
            <div class="flex items-center">
                <img class="w-24 h-24 rounded-full object-cover" src="{{ $studentToView->photo_path ? route('users.photo', $studentToView) : 'https://ui-avatars.com/api/?name=' . urlencode($studentToView->name) }}" alt="{{ $studentToView->name }}">
                <div class="ml-4">
                    <h3 class="text-xl font-bold ...">{{ $studentToView->name }}</h3>
                    <p class="text-sm ...">{{ $studentToView->email }}</p>
                </div>
            </div>
            <div class="mt-4 border-t pt-4 space-y-4">
                {{-- ... (código para mostrar DNI, teléfono, links, etc.) ... --}}
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button wire:click="closeModal">Cerrar</x-secondary-button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal de Confirmación de Desinscripción -->
    @if($studentToUnenroll)
    <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
        <div @click.away="closeModal()" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-bold ...">Desinscribir Alumno</h3>
            <p class="mt-2 ...">
                ¿Estás seguro de que quieres desinscribir a <strong class="...">{!! $studentToUnenroll->name !!}</strong> del curso?
            </p>
            <div class="mt-6 flex justify-end space-x-4">
                <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
                <x-danger-button wire:click="unenrollStudent">Sí, Desinscribir</x-danger-button>
            </div>
        </div>
    </div>
    @endif
</div>