<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'SiGeAc') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
        
        <!-- Script para gestionar el modo oscuro y prevenir el parpadeo -->
        <script>
            if (localStorage.getItem('darkMode') === 'true' || 
               (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
              document.documentElement.classList.add('dark');
            } else {
              document.documentElement.classList.remove('dark');
            }
        </script>
        
        <!-- Estilos y Scripts Principales -->
        {{-- El atributo 'data-navigate-once' es CRUCIAL. Le dice a Livewire que no toque estos scripts al navegar --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    
    <body class="font-sans antialiased flex flex-col min-h-screen">
        <div class="flex-grow bg-neutral-background dark:bg-dark-neutral-background">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <footer class="bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500 dark:text-gray-400">
                <span>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</span>
                <span class="mx-2">|</span>
                <span>¿Problemas? Contacta a <a href="mailto:soporte.academico@utn.com" class="underline hover:text-gray-900 dark:hover:text-gray-100">soporte.academico@utn.com</a></span>
            </div>
        </footer>
        
        @livewireScripts
        
        <!-- Componente Global de Notificación (Toast) -->
        <div 
            x-data="{ show: false, message: '' }"
            x-on:show-toast.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition
            class="fixed top-5 right-5 z-50 p-4 rounded-md shadow-lg text-white bg-accent-success"
            style="display: none;"
        >
            <div x-text="message"></div>
        </div>
    </body>
</html>