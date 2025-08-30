<div>
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-secondary dark:text-dark-secondary leading-tight">
                Panel de Administración
            </h2>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Grid de Tarjetas de Estadísticas (Reestructurado a 2x2) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <!-- Tarjeta 1: Total de Alumnos -->
                <div class="bg-white dark:bg-dark-neutral-card p-6 rounded-lg shadow-lg flex items-start space-x-4">
                    <div class="bg-primary/10 dark:bg-dark-primary/20 p-3 rounded-full">
                        <x-lucide-users class="w-8 h-8 text-primary dark:text-dark-primary" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-neutral-text dark:text-dark-neutral-text">Total de Alumnos</p>
                        <p class="text-3xl font-bold text-secondary dark:text-dark-secondary">{{ $totalStudents }}</p>
                    </div>
                </div>

                <!-- Tarjeta 2: Total de Docentes -->
                <div class="bg-white dark:bg-dark-neutral-card p-6 rounded-lg shadow-lg flex items-start space-x-4">
                    <div class="bg-accent-creative/10 dark:bg-dark-accent-creative/20 p-3 rounded-full">
                        <x-lucide-contact class="w-8 h-8 text-accent-creative dark:text-dark-accent-creative" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-neutral-text dark:text-dark-neutral-text">Total de Docentes</p>
                        <p class="text-3xl font-bold text-secondary dark:text-dark-secondary">{{ $totalTeachers }}</p>
                    </div>
                </div>

                <!-- Tarjeta 3: Total de Cursos -->
                <div class="bg-white dark:bg-dark-neutral-card p-6 rounded-lg shadow-lg flex items-start space-x-4">
                    <div class="bg-accent-success/10 dark:bg-dark-accent-success/20 p-3 rounded-full">
                        <x-lucide-book-marked class="w-8 h-8 text-accent-success dark:text-dark-accent-success" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-neutral-text dark:text-dark-neutral-text">Cursos Activos</p>
                        <p class="text-3xl font-bold text-secondary dark:text-dark-secondary">{{ $totalCourses }}</p>
                    </div>
                </div>

                <!-- Tarjeta 4: Curso Más Popular -->
                <div class="bg-white dark:bg-dark-neutral-card p-6 rounded-lg shadow-lg flex items-start space-x-4">
                    <div class="bg-red-500/10 dark:bg-red-500/20 p-3 rounded-full">
                        <x-lucide-flame class="w-8 h-8 text-red-500 dark:text-red-400" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-neutral-text dark:text-dark-neutral-text">Curso Más Popular</p>
                        @if($mostPopularCourse)
                            <p class="text-xl font-bold text-secondary dark:text-dark-secondary leading-tight" title="{{ $mostPopularCourse->subject->name }}">
                                {{ $mostPopularCourse->subject->name }}
                            </p>
                            <p class="text-sm text-neutral-text dark:text-dark-neutral-text">{{ $mostPopularCourse->students_count }} inscriptos</p>
                        @else
                            <p class="text-xl font-bold text-secondary dark:text-dark-secondary">N/A</p>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>