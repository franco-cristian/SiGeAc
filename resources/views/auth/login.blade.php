<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SiGeAc') }} - Iniciar Sesión</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="relative min-h-screen w-full flex items-center justify-center p-4 bg-gradient-to-br from-secondary to-dark-neutral-background">
        
        <!-- Tarjeta Principal -->
        <div class="relative w-full max-w-4xl sm:mx-auto grid grid-cols-1 lg:grid-cols-2 bg-dark-neutral-card/50 rounded-2xl shadow-2xl backdrop-blur-lg overflow-hidden border border-white/10">
            
            <!-- Panel Izquierdo Decorativo -->
            <div class="flex relative flex-col items-center justify-center p-12 bg-dark-primary/20 overflow-hidden lg:border-r lg:border-white/10">
                <!-- 12 iconos para animación -->
                <div class="absolute inset-0 opacity-20">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[10%] left-[20%] w-16 h-16 text-white animate-float-complex" style="animation-delay: -2s;">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[50%] left-[80%] w-12 h-12 text-white animate-float-complex" style="animation-delay: -4s;">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute bottom-[10%] left-[50%] w-20 h-20 text-white animate-float-complex" style="animation-delay: 0s;">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[33%] right-[15%] w-8 h-8 text-white animate-float-complex" style="animation-delay: -6s;">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute bottom-[33%] left-[10%] w-14 h-14 text-white animate-float-complex" style="animation-delay: -8s;">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[75%] right-[50%] w-10 h-10 text-white animate-float-complex" style="animation-delay: -1s;">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute bottom-[60%] left-[30%] w-16 h-16 text-white animate-float-complex" style="animation-delay: -5s;">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[20%] right-[30%] w-12 h-12 text-white animate-float-complex" style="animation-delay: -7s;">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute bottom-[20%] right-[20%] w-10 h-10 text-white animate-float-complex" style="animation-delay: -3s;">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[80%] left-[15%] w-14 h-14 text-white animate-float-complex" style="animation-delay: -9s;">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[5%] right-[5%] w-10 h-10 text-white animate-float-complex" style="animation-delay: -1.5s;">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute bottom-[5%] left-[5%] w-12 h-12 text-white animate-float-complex" style="animation-delay: -3.5s;">
                </div>
                <div class="relative z-10 text-center">
                    <img src="{{ asset('images/utn-logo.svg') }}" alt="Logo SiGeAc" class="w-2/3 max-w-[250px] text-white inline-block">
                    <div class="mt-6">
                        <h2 class="text-2xl font-bold text-white">¡Bienvenido de nuevo!</h2>
                        <p class="text-white/80 mt-2 text-sm">
                            <span class="font-semibold">SiGeAc</span> (Sistema de Gestión Académica)
                            <br>
                            Plataforma para la comunidad de UTN FRRe Sede Formosa.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Panel Derecho del Formulario -->
            <div x-data="{}" x-init="setTimeout(() => { $el.classList.remove('opacity-0') }, 100)" class="p-8 sm:p-12 opacity-0 transition-opacity duration-500 ease-in-out">
                <h2 class="text-center text-2xl font-bold tracking-tight text-white">
                    Iniciar Sesión
                </h2>

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                    @csrf
                    <!-- Email -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-lucide-mail class="h-5 w-5 text-white/40"/>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Correo Electrónico"
                               class="block w-full pl-10 pr-3 py-2.5 bg-white/10 text-white border-white/20 focus:ring-dark-primary focus:border-dark-primary placeholder:text-white/60 rounded-md shadow-sm">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />

                    <!-- Contraseña -->
                    <div x-data="{ show: false }" class="relative">
                         <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-lucide-lock class="h-5 w-5 text-white/40"/>
                        </span>
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required placeholder="Contraseña"
                               class="block w-full pl-10 pr-10 py-2.5 bg-white/10 text-white border-white/20 focus:ring-dark-primary focus:border-dark-primary placeholder:text-white/60 rounded-md shadow-sm">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 z-10 text-white/40 hover:text-white">
                            <x-lucide-eye-off x-show="!show" class="h-5 w-5" />
                            <x-lucide-eye x-show="show" class="h-5 w-5" style="display: none;" />
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                    <!-- Opciones -->
                    <div class="flex items-center justify-between text-sm">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-white/30 text-dark-primary focus:ring-dark-primary bg-transparent" name="remember">
                            <span class="ms-2 text-white/70">Mantener sesión</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="underline text-white/70 hover:text-white" href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Botón -->
                    <div>
                        <x-primary-button class="w-full justify-center !py-3 text-base">
                            Ingresar
                        </x-primary-button>
                    </div>

                    <!-- Enlace de Registro -->
                    <p class="text-center text-sm text-white/70">
                        ¿No tienes una cuenta?
                        <a href="{{ route('register') }}" class="font-semibold underline hover:text-white">
                            Regístrate aquí
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>