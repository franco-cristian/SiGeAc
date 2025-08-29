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
                            <!-- Teléfono con enlace a WhatsApp -->
                            @if($user->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="flex items-center text-neutral-text dark:text-dark-neutral-text hover:text-primary dark:hover:text-dark-primary transition">
                                    <x-lucide-phone class="w-5 h-5 mr-3 text-gray-400"/>
                                    <span>{{ $user->phone }}</span>
                                </a>
                            @endif

                            <!-- Enlaces Profesionales -->
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

                <!-- Columna Derecha: Horario y Cursos -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-dark-neutral-card shadow-xl rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-secondary dark:text-dark-secondary">Mi Horario</h4>
                        <div class="mt-4 text-center py-12 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                            <p class="text-neutral-text dark:text-dark-neutral-text">Próximamente: Tu horario de clases semanal.</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-dark-neutral-card shadow-xl rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-secondary dark:text-dark-secondary">Mis Cursos Inscritos</h4>
                        <div class="mt-4 text-center py-12 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                             <p class="text-neutral-text dark:text-dark-neutral-text">Próximamente: Un listado de tus cursos actuales.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>