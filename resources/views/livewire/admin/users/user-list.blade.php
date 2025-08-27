<div>
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-secondary dark:text-dark-secondary leading-tight">
                Gestión de Usuarios
            </h2>
        </div>
    </header>

    <!-- Contenido -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-neutral-card overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 space-y-6">

                    <!-- Barra de Filtros -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input
                            wire:model.live.debounce.300ms="search"
                            type="text"
                            placeholder="Buscar por nombre o email..."
                            class="md:col-span-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-dark-primary focus:ring-primary dark:focus:ring-dark-primary rounded-md shadow-sm w-full">
                        <select wire:model.live="role" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-dark-primary focus:ring-primary dark:focus:ring-dark-primary rounded-md shadow-sm w-full">
                            <option value="">Todos los Roles</option>
                            @foreach ($roles as $roleName)
                            <option value="{{ $roleName }}">{{ $roleName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Contenedor de la Lista -->
                    <div>
                        <!-- Tabla para Escritorio -->
                        <div class="hidden md:block">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nombre</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rol</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-dark-neutral-card divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @foreach ($user->roles as $role)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if ($user->id !== auth()->id() && !$user->hasRole('SuperAdmin'))
                                            <button wire:click="confirmDelete({{ $user->id }})" class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                                <x-lucide-trash-2 class="h-5 w-5" />
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No se encontraron usuarios.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Tarjetas para Móvil -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:hidden">
                            @forelse ($users as $user)
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                    </div>
                                    <div class="mt-1">
                                        @foreach ($user->roles as $role)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">{{ $role->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    @if ($user->id !== auth()->id() && !$user->hasRole('SuperAdmin'))
                                    <button wire:click="confirmDelete({{ $user->id }})" class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                        <x-lucide-trash-2 class="h-5 w-5" />
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-gray-500 dark:text-gray-400">No se encontraron usuarios.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    @if($confirmingUserDeletion)
    <div
        x-data="{ showModal: false }"
        x-init="showModal = true"
        x-show="showModal"
        x-transition
        x-on:keydown.escape.window="showModal = false; setTimeout(() => $wire.cancelDelete(), 300)"
        class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
        <div
            x-on:click.away="showModal = false; setTimeout(() => $wire.cancelDelete(), 300)"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-bold text-secondary dark:text-dark-secondary">Confirmar Eliminación</h3>
            <p class="mt-2 text-neutral-text dark:text-dark-neutral-text">
                ¿Estás seguro de que quieres eliminar al usuario
                <strong class="font-bold text-secondary dark:text-dark-secondary">{{ $userToDelete?->name }}</strong>?
                Esta acción no se puede deshacer.
            </p>
            <div class="mt-6 flex justify-end space-x-4">
                <x-secondary-button wire:click="cancelDelete">Cancelar</x-secondary-button>
                <x-danger-button wire:click="delete">Sí, Eliminar</x-danger-button>
            </div>
        </div>
    </div>
    @endif