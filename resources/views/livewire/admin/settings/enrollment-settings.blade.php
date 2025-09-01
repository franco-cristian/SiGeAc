<div>
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-secondary dark:text-dark-secondary leading-tight">
                Configuración del Período de Inscripción
            </h2>
        </div>
    </header>

    <!-- Contenido -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-neutral-card shadow-xl sm:rounded-lg">
                <form wire:submit="saveSettings" class="p-6 lg:p-8 space-y-6">
                    <div>
                        <x-input-label for="start_date" value="Fecha de Inicio" />
                        <x-text-input wire:model="enrollment_start_date" id="start_date" type="date" class="mt-1 block w-full md:w-1/3" />
                        <x-input-error :messages="$errors->get('enrollment_start_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="end_date" value="Fecha de Finalización" />
                        <x-text-input wire:model="enrollment_end_date" id="end_date" type="date" class="mt-1 block w-full md:w-1/3" />
                        <x-input-error :messages="$errors->get('enrollment_end_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-primary-button type="submit">Guardar Cambios</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>