<div x-data="{ reportModalOpen: false }">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                <h2 class="font-semibold text-xl text-secondary dark:text-dark-secondary leading-tight">
                    Panel del Docente
                </h2>
                @if($selectedCourse)
                    <x-secondary-button @click="reportModalOpen = true">
                        <x-lucide-file-down class="w-4 h-4 mr-2"/>
                        Generar Reporte PDF
                    </x-secondary-button>
                @endif
            </div>
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
                             <select wire:model.live="selectedCourseId" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-dark-primary focus:ring-primary dark:focus:ring-dark-primary rounded-md shadow-sm">
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->subject->name }} - {{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-grow">
                             <input 
                                wire:model.live.debounce.300ms="search" 
                                type="text" 
                                placeholder="Buscar alumno por nombre, email, DNI o teléfono..."
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-dark-primary focus:ring-primary dark:focus:ring-dark-primary rounded-md shadow-sm"
                            >
                        </div>
                        <div class="flex items-center space-x-4 justify-end">
                            <select wire:model.live="perPage" class="w-auto border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-dark-primary focus:ring-primary dark:focus:ring-dark-primary rounded-md shadow-sm">
                                <option value="10">10 / pág</option>
                                <option value="25">25 / pág</option>
                                <option value="50">50 / pág</option>
                            </select>
                            <label class="flex items-center text-sm text-neutral-text dark:text-dark-neutral-text whitespace-nowrap cursor-pointer">
                                <input type="checkbox" wire:model.live="showPhotos" class="rounded text-primary dark:focus:ring-dark-primary focus:ring-offset-dark-neutral-card">
                                <span class="ml-2">Fotos</span>
                            </label>
                        </div>
                    </div>

                    @if($selectedCourse)
                        <h3 class="text-lg font-bold text-secondary dark:text-dark-secondary border-t border-gray-200 dark:border-gray-700 pt-4">
                            Alumnos de: {{ $selectedCourse->subject->name }} - {{ $selectedCourse->name }}
                        </h3>
                    @endif

                    <div class="overflow-x-auto">
                        <!-- Tabla para Escritorio -->
                        <table class="hidden md:table w-full text-left table-auto">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    @if($showPhotos) <th class="w-16 px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Foto</th> @endif
                                    <th class="w-1/4 px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nombre</th>
                                    <th class="w-1/4 px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                    <th class="w-28 px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Teléfono</th>
                                    <th class="w-28 px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">DNI</th>
                                    <th class="w-20 px-4 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($students as $student)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                        @if($showPhotos)
                                            <td class="px-4 py-2"><img class="h-10 w-10 rounded-full object-cover" src="{{ $student->photo_path ? route('users.photo', $student) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) }}" alt="{{ $student->name }}"></td>
                                        @endif
                                        <td class="px-4 py-2"><button wire:click="viewStudentProfile({{ $student->id }})" class="font-medium text-primary dark:text-dark-primary hover:underline text-left">{{ $student->name }}</button></td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400"><a href="mailto:{{ $student->email }}" class="hover:underline">{{ $student->email }}</a></td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">@if($student->phone)<a href="https://wa.me/549{{ $student->phone }}" target="_blank" class="hover:underline">{{ $student->phone }}</a>@endif</td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $student->dni }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <button wire:click="confirmUnenrollStudent({{ $student->id }})" class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400"><x-lucide-user-x class="h-5 w-5"/></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ $showPhotos ? 6 : 5 }}" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No se encontraron alumnos para los filtros seleccionados.</td></tr>
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
                                        <button wire:click="confirmUnenrollStudent({{ $student->id }})" class="p-1 text-gray-400 hover:text-red-600"><x-lucide-user-x class="h-5 w-5"/></button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 dark:text-gray-400">No se encontraron alumnos.</p>
                            @endforelse
                        </div>
                    </div>

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
            <div class="flex items-center">
                <img class="w-24 h-24 rounded-full object-cover" src="{{ $studentToView->photo_path ? route('users.photo', $studentToView) : 'https://ui-avatars.com/api/?name=' . urlencode($studentToView->name) }}" alt="{{ $studentToView->name }}">
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-secondary dark:text-dark-secondary">{{ $studentToView->name }}</h3>
                    <p class="text-sm text-neutral-text dark:text-dark-neutral-text">{{ $studentToView->email }}</p>
                </div>
            </div>
            <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4 space-y-4">
                <div class="flex items-center text-neutral-text dark:text-dark-neutral-text"><x-lucide-hash class="w-5 h-5 mr-3 text-gray-400 shrink-0"/><span><strong>DNI:</strong> {{ $studentToView->dni }}</span></div>
                @if($studentToView->phone)<a href="https://wa.me/549{{ $studentToView->phone }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition"><x-lucide-phone class="w-5 h-5 mr-3 text-gray-400 shrink-0"/><span>{{ $studentToView->phone }}</span></a>@endif
                @if($studentToView->github_url)<a href="{{ $studentToView->github_url }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition"><x-lucide-github class="w-5 h-5 mr-3 text-gray-400 shrink-0"/><span>Perfil de GitHub</span></a>@endif
                @if($studentToView->linkedin_url)<a href="{{ $studentToView->linkedin_url }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition"><x-lucide-linkedin class="w-5 h-5 mr-3 text-gray-400 shrink-0"/><span>Perfil de LinkedIn</span></a>@endif
                @if($studentToView->professional_url)<a href="{{ $studentToView->professional_url }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition"><x-lucide-globe class="w-5 h-5 mr-3 text-gray-400 shrink-0"/><span>Sitio Web / Portafolio</span></a>@endif
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
                <h3 class="text-xl font-bold text-secondary dark:text-dark-secondary">Desinscribir Alumno</h3>
                <p class="mt-2 text-neutral-text dark:text-dark-neutral-text">
                    ¿Estás seguro de que quieres desinscribir a <strong class="font-bold">{!! $studentToUnenroll->name !!}</strong> del curso? Esta acción es irreversible.
                </p>
                <div class="mt-6 flex justify-end space-x-4">
                    <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
                    <x-danger-button wire:click="unenrollStudent">Sí, Desinscribir</x-danger-button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal para Generar Reporte PDF -->
    <div x-show="reportModalOpen" x-transition class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click.away="reportModalOpen = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-2xl">
            <h3 class="text-xl font-bold text-secondary dark:text-dark-secondary">Generar Lista de Alumnos en PDF</h3>
            <p class="mt-1 text-sm text-neutral-text dark:text-dark-neutral-text">
                Personaliza el reporte para el curso <strong class="font-semibold">{{ $selectedCourse?->subject->name }}</strong>.
            </p>
            <form action="{{ $selectedCourse ? route('docente.courses.report', $selectedCourse->id) : '#' }}" method="POST" target="_blank" class="mt-6 space-y-6">
                @csrf
                @foreach($students as $student)
                    <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                @endforeach
                
                <div>
                    <label class="font-medium text-secondary dark:text-dark-secondary">Columnas a Incluir</label>
                    <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <label class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50"><input type="checkbox" name="columns[]" value="photo" checked class="rounded text-primary focus:ring-primary"><span class="text-sm font-medium text-secondary dark:text-dark-secondary">Foto</span></label>
                        <label class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50"><input type="checkbox" name="columns[]" value="name" checked class="rounded text-primary focus:ring-primary"><span class="text-sm font-medium text-secondary dark:text-dark-secondary">Nombre</span></label>
                        <label class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50"><input type="checkbox" name="columns[]" value="email" checked class="rounded text-primary focus:ring-primary"><span class="text-sm font-medium text-secondary dark:text-dark-secondary">Email</span></label>
                        <label class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50"><input type="checkbox" name="columns[]" value="dni" checked class="rounded text-primary focus:ring-primary"><span class="text-sm font-medium text-secondary dark:text-dark-secondary">DNI</span></label>
                        <label class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50"><input type="checkbox" name="columns[]" value="phone" class="rounded text-primary focus:ring-primary"><span class="text-sm font-medium text-secondary dark:text-dark-secondary">Teléfono</span></label>
                        <label class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50"><input type="checkbox" name="columns[]" value="date_of_birth" class="rounded text-primary focus:ring-primary"><span class="text-sm font-medium text-secondary dark:text-dark-secondary">Fecha de Nac.</span></label>
                        <label class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50"><input type="checkbox" name="columns[]" value="linkedin_url" class="rounded text-primary focus:ring-primary"><span class="text-sm font-medium text-secondary dark:text-dark-secondary">LinkedIn</span></label>
                        <label class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50"><input type="checkbox" name="columns[]" value="github_url" class="rounded text-primary focus:ring-primary"><span class="text-sm font-medium text-secondary dark:text-dark-secondary">GitHub</span></label>
                        <label class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50"><input type="checkbox" name="columns[]" value="professional_url" class="rounded text-primary focus:ring-primary"><span class="text-sm font-medium text-secondary dark:text-dark-secondary">Portafolio</span></label>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <label class="font-medium text-secondary dark:text-dark-secondary">Orientación del Papel</label>
                    <div class="mt-2 flex items-center space-x-6">
                        <label class="flex items-center"><input type="radio" name="orientation" value="portrait" checked class="text-primary focus:ring-primary"><span class="ml-2 text-neutral-text dark:text-dark-neutral-text">Vertical</span></label>
                        <label class="flex items-center"><input type="radio" name="orientation" value="landscape" class="text-primary focus:ring-primary"><span class="ml-2 text-neutral-text dark:text-dark-neutral-text">Horizontal</span></label>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <x-secondary-button type="button" @click="reportModalOpen = false">Cancelar</x-secondary-button>
                    <x-primary-button type="submit">Generar PDF</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>