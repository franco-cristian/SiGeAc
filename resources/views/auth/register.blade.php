<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SiGeAc') }} - Registro</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Scripts y Estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="relative min-h-screen w-full flex items-center justify-center p-4 bg-gradient-to-br from-secondary to-dark-neutral-background">
        <div class="relative w-full max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-2 bg-dark-neutral-card/50 rounded-2xl shadow-2xl backdrop-blur-lg overflow-hidden border border-white/10">
            
            <!-- Panel Decorativo Izquierdo -->
            <div class="hidden lg:flex flex-col items-center justify-center p-12 bg-dark-primary/20">
                <img src="{{ asset('images/utn-logo.svg') }}" alt="Logo SiGeAc" class="w-2/3 max-w-[200px] animate-float">
                <div class="text-center mt-6">
                    <h2 class="text-3xl font-bold text-white">Únete a la Comunidad</h2>
                    <p class="text-white/80 mt-2">Crea tu perfil de alumno para empezar a gestionar tu carrera académica.</p>
                </div>
            </div>

            <!-- Panel del Formulario Derecho -->
            <div class="p-8 sm:p-12">
                <h2 class="text-center text-3xl font-bold tracking-tight text-white">Crear una Cuenta</h2>

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="mt-8 space-y-4">
                    @csrf
                    
                    <!-- Previsualización y Subida de Foto -->
                    <div x-data="{ photoPreview: null }" class="flex flex-col items-center">
                        <input type="file" name="photo" id="photo" class="hidden" required
                               @change="const reader = new FileReader();
                                       reader.onload = (e) => { photoPreview = e.target.result };
                                       reader.readAsDataURL($event.target.files[0]);">
                        
                        <label for="photo" class="cursor-pointer">
                            <div x-show="!photoPreview" class="w-24 h-24 rounded-full bg-white/10 flex items-center justify-center text-white/50 border-2 border-dashed border-white/20 hover:bg-white/20 hover:border-white/40 transition">
                                <x-lucide-camera class="w-10 h-10"/>
                            </div>
                            <div x-show="photoPreview" class="w-24 h-24 rounded-full border-2 border-white/20">
                                <img :src="photoPreview" alt="Previsualización" class="w-full h-full rounded-full object-cover">
                            </div>
                        </label>
                        <span class="mt-2 text-sm text-white/70">Foto de Perfil (Obligatoria)</span>
                        <x-input-error :messages="$errors->get('photo')" class="mt-2 text-center" />
                    </div>

                    <!-- Campos del Formulario -->
                    <x-text-input class="w-full" name="name" :value="old('name')" required placeholder="Nombre Completo" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    
                    <x-text-input class="w-full" name="email" type="email" :value="old('email')" required placeholder="Correo Electrónico" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    
                    <x-text-input class="w-full" name="dni" :value="old('dni')" required placeholder="DNI (sin puntos)" />
                    <x-input-error :messages="$errors->get('dni')" class="mt-1" />

                    <x-text-input class="w-full" name="password" type="password" required placeholder="Contraseña" autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    
                    <x-text-input class="w-full" name="password_confirmation" type="password" required placeholder="Confirmar Contraseña" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />

                    <x-text-input class="w-full" name="phone" :value="old('phone')" placeholder="Teléfono (Opcional)" />
                    <x-text-input class="w-full" name="professional_url" type="url" :value="old('professional_url')" placeholder="URL LinkedIn/GitHub (Opcional)" />

                    <!-- Botón de Envío -->
                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center !py-3 text-base">Registrarse</x-primary-button>
                    </div>

                    <!-- Enlace a Login -->
                    <p class="text-center text-sm text-white/70">
                        ¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="font-semibold underline hover:text-white">Inicia sesión</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>