<div>
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-secondary dark:text-dark-secondary leading-tight">
                Mi Panel
            </h2>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Columna Izquierda: Widget de Perfil -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-dark-neutral-card shadow-xl rounded-lg p-6 text-center">
                        <img class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-primary dark:border-dark-primary" 
                             src="{{ $user->photo_path ? route('users.photo', $user) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}" 
                             alt="{{ $user->name }}">
                        
                        <h3 class="mt-4 text-2xl font-bold text-secondary dark:text-dark-secondary">{{ $user->name }}</h3>
                        <p class="text-sm text-neutral-text dark:text-dark-neutral-text">{{ $user->email }}</p>
                    </div>

                    <div class="bg-white dark:bg-dark-neutral-card shadow-xl rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-secondary dark:text-dark-secondary border-b border-gray-200 dark:border-gray-700 pb-3">Información de Contacto</h4>
                        <div class="mt-4 space-y-4">
                            @if($user->phone)
                                <a href="https://wa.me/549{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition">
                                    <x-lucide-phone class="w-5 h-5 mr-3 text-gray-400"/>
                                    <span>{{ $user->phone }}</span>
                                </a>
                            @endif
                            @if($user->github_url)
                                <a href="{{ $user->github_url }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition">
                                    <x-lucide-github class="w-5 h-5 mr-3 text-gray-400"/>
                                    <span>Perfil de GitHub</span>
                                </a>
                            @endif
                            @if($user->linkedin_url)
                                <a href="{{ $user->linkedin_url }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition">
                                    <x-lucide-linkedin class="w-5 h-5 mr-3 text-gray-400"/>
                                    <span>Perfil de LinkedIn</span>
                                </a>
                            @endif
                            @if($user->professional_url)
                                <a href="{{ $user->professional_url }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition">
                                    <x-lucide-globe class="w-5 h-5 mr-3 text-gray-400"/>
                                    <span>Sitio Web / Portafolio</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Horario -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-dark-neutral-card shadow-xl rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                             <h4 class="text-lg font-semibold text-secondary dark:text-dark-secondary">Mi Horario Semanal</h4>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-5">
                            @foreach($schedule as $day => $slots)
                                <div class="border-t sm:border-t-0 sm:border-l border-gray-200 dark:border-gray-700">
                                    <div class="py-2 px-4 bg-gray-50 dark:bg-gray-700/50 text-center font-bold text-secondary dark:text-dark-secondary capitalize">{{ $day }}</div>
                                    <div class="p-4 space-y-4 min-h-[200px]">
                                        @forelse($slots as $slot)
                                            <button wire:click="showCourseDetails({{ $slot['course']->id }})" class="w-full text-left p-3 rounded-lg bg-primary/10 dark:bg-dark-primary/20 hover:bg-primary/20 dark:hover:bg-dark-primary/30 transition">
                                                <p class="font-bold text-sm text-primary dark:text-dark-primary">{{ $slot['course']->subject->name }}</p>
                                                <p class="text-xs text-neutral-text dark:text-dark-neutral-text mt-1">
                                                    <x-lucide-clock class="w-3 h-3 inline-block mr-1"/>
                                                    {{ \Carbon\Carbon::parse($slot['schedule']->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot['schedule']->end_time)->format('H:i') }}
                                                </p>
                                            </button>
                                        @empty
                                            <div class="flex items-center justify-center h-full">
                                                <p class="text-xs text-neutral-text dark:text-dark-neutral-text">Sin clases</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal de Detalles del Curso -->
    @if($selectedCourse)
    <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg">
            <h3 class="text-xl font-bold text-secondary dark:text-dark-secondary">{{ $selectedCourse->subject->name }}</h3>
            <p class="text-sm text-neutral-text dark:text-dark-neutral-text">{{ $selectedCourse->name }} - {{ $selectedCourse->term }} {{ $selectedCourse->year }}</p>
            
            <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2">
                <div>
                    <span class="font-semibold">Docente:</span> 
                    <button wire:click="showTeacherDetails({{ $selectedCourse->teacher->id }})" class="text-primary dark:text-dark-primary underline">
                        {{ $selectedCourse->teacher->name ?? 'No asignado' }}
                    </button>
                </div>
                <div>
                    <span class="font-semibold">Horarios:</span>
                    <ul class="list-disc list-inside">
                        @foreach($selectedCourse->schedules as $schedule)
                            <li class="capitalize">{{ $schedule->day_of_week }}: {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} a {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button wire:click="closeModal">Cerrar</x-secondary-button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal de Detalles del Docente -->
    @if($selectedTeacher)
    <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg">
            <div class="flex items-center">
                <img class="w-24 h-24 rounded-full object-cover" 
                     src="{{ $selectedTeacher->photo_path ? route('users.photo', $selectedTeacher) : 'https://ui-avatars.com/api/?name=' . urlencode($selectedTeacher->name) . '&background=random' }}" 
                     alt="{{ $selectedTeacher->name }}">
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-secondary dark:text-dark-secondary">{{ $selectedTeacher->name }}</h3>
                    <p class="text-sm text-neutral-text dark:text-dark-neutral-text">{{ $selectedTeacher->email }}</p>
                </div>
            </div>
            
            <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4 space-y-4">
                 @if($selectedTeacher->phone)
                    <a href="https://wa.me/549{{ preg_replace('/[^0-9]/', '', $selectedTeacher->phone) }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition">
                        <x-lucide-phone class="w-5 h-5 mr-3 text-gray-400"/>
                        <span>{{ $selectedTeacher->phone }}</span>
                    </a>
                @endif
                @if($selectedTeacher->github_url)
                    <a href="{{ $selectedTeacher->github_url }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition">
                        <x-lucide-github class="w-5 h-5 mr-3 text-gray-400"/>
                        <span>Perfil de GitHub</span>
                    </a>
                @endif
                 @if($selectedTeacher->linkedin_url)
                    <a href="{{ $selectedTeacher->linkedin_url }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition">
                        <x-lucide-linkedin class="w-5 h-5 mr-3 text-gray-400"/>
                        <span>Perfil de LinkedIn</span>
                    </a>
                @endif
                 @if($selectedTeacher->professional_url)
                    <a href="{{ $selectedTeacher->professional_url }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition">
                        <x-lucide-globe class="w-5 h-5 mr-3 text-gray-400"/>
                        <span>Sitio Web / Portafolio</span>
                    </a>
                @endif
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button wire:click="closeModal">Cerrar</x-secondary-button>
            </div>
        </div>
    </div>
    @endif
</div>