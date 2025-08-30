<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SiGeAc') }} - Registro</title>
    <!-- Fonts y Scripts (igual que en el login) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet" />
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="relative min-h-screen w-full flex items-center justify-center p-4 bg-neutral-background dark:bg-gradient-to-br dark:from-secondary dark:to-dark-neutral-background">
        
        <div id="particles-js" class="absolute inset-0 z-0"></div>
        <div x-data="{ darkMode: document.documentElement.classList.contains('dark') }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" class="absolute top-5 right-5 z-20">
            <button @click="darkMode = !darkMode; document.documentElement.classList.toggle('dark')" class="p-2 rounded-full bg-black/10 dark:bg-white/10 text-secondary dark:text-dark-secondary backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <x-lucide-sun x-show="!darkMode" class="h-6 w-6" />
                <x-lucide-moon x-show="darkMode" class="h-6 w-6" />
            </button>
        </div>

        <div class="relative z-10 w-full max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 bg-neutral-card/60 dark:bg-dark-neutral-card/40 rounded-2xl shadow-2xl backdrop-blur-lg overflow-hidden border border-gray-200/50 dark:border-white/10">
            
            <!-- Panel Izquierdo Decorativo con 12 animaciones -->
            <div class="flex relative flex-col items-center justify-center p-8 sm:p-12 bg-primary/10 dark:bg-dark-primary/20 overflow-hidden">
                <div class="absolute inset-0 opacity-10 dark:opacity-20">
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[10%] left-[20%] w-16 h-16 animate-float-complex text-primary dark:text-white" style="animation-delay: -2s;" />
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[50%] left-[80%] w-12 h-12 animate-float-complex text-primary dark:text-white" style="animation-delay: -4s;" />
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute bottom-[10%] left-[50%] w-20 h-20 animate-float-complex text-primary dark:text-white" style="animation-delay: 0s;" />
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[33%] right-[15%] w-8 h-8 animate-float-complex text-primary dark:text-white" style="animation-delay: -6s;" />
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute bottom-[33%] left-[10%] w-14 h-14 animate-float-complex text-primary dark:text-white" style="animation-delay: -8s;" />
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[75%] right-[50%] w-10 h-10 animate-float-complex text-primary dark:text-white" style="animation-delay: -1s;" />
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute bottom-[60%] left-[30%] w-16 h-16 animate-float-complex text-primary dark:text-white" style="animation-delay: -5s;" />
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[20%] right-[30%] w-12 h-12 animate-float-complex text-primary dark:text-white" style="animation-delay: -7s;" />
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute bottom-[20%] right-[20%] w-10 h-10 animate-float-complex text-primary dark:text-white" style="animation-delay: -3s;" />
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[80%] left-[15%] w-14 h-14 animate-float-complex text-primary dark:text-white" style="animation-delay: -9s;" />
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute top-[5%] right-[5%] w-10 h-10 animate-float-complex text-primary dark:text-white" style="animation-delay: -1.5s;" />
                    <img src="{{ asset('images/utn-icon.svg') }}" alt="" class="absolute bottom-[5%] left-[5%] w-12 h-12 animate-float-complex text-primary dark:text-white" style="animation-delay: -3.5s;" />
                </div>
                <div class="relative z-10 text-center">
                    <x-auth.login-logo class="block w-2/3 max-w-[250px] mx-auto"/>
                    <div class="mt-6">
                        <h2 class="text-2xl font-bold text-secondary dark:text-white">Únete a la Comunidad</h2>
                        <p class="text-neutral-text dark:text-white/80 mt-2 text-sm">
                            Crea tu perfil para empezar a gestionar tu carrera académica.
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-8 sm:p-12 overflow-y-auto" style="max-height: 90vh;">
                <h2 class="text-center text-2xl font-bold tracking-tight text-secondary dark:text-dark-secondary">
                    Crear una Cuenta
                </h2>
                <form x-data="{ photoPreview: null, photoError: '' }" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="mt-6 space-y-4" @submit="if (!photoPreview) { photoError = 'La foto de perfil es obligatoria.'; $event.preventDefault(); } else { photoError = ''; }">
                    @csrf
                    <!-- Foto de Perfil -->
                    <div class="flex flex-col items-center">
                        <input type="file" name="photo" id="photo" class="hidden" @change="const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; photoError = ''; }; reader.readAsDataURL($event.target.files[0]);">
                        <label for="photo" class="cursor-pointer">
                            <div x-show="!photoPreview" class="w-24 h-24 rounded-full bg-black/10 dark:bg-white/10 flex items-center justify-center text-black/50 dark:text-white/50 border-2 border-dashed border-black/20 dark:border-white/20 hover:bg-black/20 dark:hover:bg-white/20 hover:border-black/40 dark:hover:border-white/40 transition">
                                <x-lucide-camera class="w-10 h-10"/>
                            </div>
                            <div x-show="photoPreview" class="w-24 h-24 rounded-full border-2 border-white/20">
                                <img :src="photoPreview" alt="Previsualización" class="w-full h-full rounded-full object-cover">
                            </div>
                        </label>
                        <span class="mt-2 text-sm text-neutral-text dark:text-dark-secondary">Foto de Perfil (Obligatoria)</span>
                        <span class="mt-2 text-xs text-neutral-text dark:text-dark-secondary">JPG, PNG, WEBP. Máx 2MB.</span>
                        <span x-text="photoError" class="mt-2 text-sm text-red-600"></span>
                        <x-input-error :messages="$errors->get('photo')" class="mt-2 text-center" />
                    </div>

                    <!-- Campos con Iconos y Validaciones -->
                    <div>
                        <x-text-input-with-icon icon="user" name="name" :value="old('name')" required placeholder="Nombre Completo" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div>
                        <x-text-input-with-icon icon="mail" name="email" type="email" :value="old('email')" required placeholder="Correo Electrónico" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                    <div>
                        <x-text-input-with-icon icon="hash" name="dni" type="text" :value="old('dni')" required placeholder="DNI (8 dígitos sin puntos)" pattern="[0-9]{8}" title="El DNI debe contener 8 dígitos numéricos." />
                        <x-input-error :messages="$errors->get('dni')" class="mt-1" />
                    </div>
                    <div>
                        <x-text-input-with-icon icon="phone" name="phone" type="tel" :value="old('phone')" required placeholder="Teléfono (ej: 3704123456)" pattern="[0-9]{10,15}" title="Ingresa un número de teléfono válido (solo números)." />
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>
                    <div>
                        <x-text-input-with-icon icon="calendar" name="date_of_birth" type="date" :value="old('date_of_birth')" required />
                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-1" />
                    </div>
                    
                    <x-password-input-with-icon />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    
                    <!-- Confirmar Contraseña  -->
                    <div x-data="{ show: false }" class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-lucide-lock class="h-5 w-5 text-neutral-text/50 dark:text-dark-neutral-text/50"/>
                        </span>
                        <input id="password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" required placeholder="Confirmar Contraseña"
                               class="block w-full pl-10 pr-10 py-2.5 bg-black/5 dark:bg-white/10 text-secondary dark:text-white border-gray-300 dark:border-white/20 focus:ring-dark-primary focus:border-dark-primary placeholder:text-neutral-text/70 dark:placeholder:text-white/60 rounded-md shadow-sm">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 z-10 text-neutral-text/50 dark:text-dark-neutral-text/50 hover:text-primary dark:hover:text-dark-primary">
                            <x-lucide-eye-off x-show="!show" class="h-5 w-5" />
                            <x-lucide-eye x-show="show" class="h-5 w-5" />
                        </button>
                    </div>
                    
                    <p class="pt-2 text-sm text-neutral-text dark:text-dark-secondary">Enlaces Profesionales (Opcional):</p>
                    
                    <div>
                        <x-text-input-with-icon icon="github" name="github_username" :value="old('github_username')" placeholder="Usuario de GitHub" />
                        <x-input-error :messages="$errors->get('github_username')" class="mt-1" />
                    </div>
                    <div>
                        <x-text-input-with-icon icon="linkedin" name="linkedin_username" :value="old('linkedin_username')" placeholder="Usuario de LinkedIn" />
                        <x-input-error :messages="$errors->get('linkedin_username')" class="mt-1" />
                    </div>
                    <div>
                        <x-text-input-with-icon icon="globe" name="professional_url" type="url" :value="old('professional_url')" placeholder="URL de Portafolio o Sitio Web" />
                        <x-input-error :messages="$errors->get('professional_url')" class="mt-1" />
                    </div>
                    
                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center !py-3 text-base">Registrarse</x-primary-button>
                    </div>
                    <p class="text-center text-sm text-neutral-text dark:text-dark-secondary">¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="font-semibold underline hover:text-primary dark:hover:text-dark-primary">Inicia sesión</a></p>
                </form>
            </div>
        </div>
    </div>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts para Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const initParticles = () => {
                const particlesContainer = document.getElementById('particles-js');
                if (particlesContainer.firstChild) {
                    particlesContainer.removeChild(particlesContainer.firstChild);
                }
                const isDarkMode = document.documentElement.classList.contains('dark');
                particlesJS('particles-js', {
                    "particles": { "number": { "value": 120, "density": { "enable": true, "value_area": 800 } }, "color": { "value": isDarkMode ? "#ffffff" : "#0F172A" }, "shape": { "type": "circle" }, "opacity": { "value": 0.3, "random": true }, "size": { "value": 3, "random": true }, "line_linked": { "enable": true, "distance": 200, "color": isDarkMode ? "#ffffff" : "#0F172A", "opacity": 0.2, "width": 1 }, "move": { "enable": true, "speed": 4, "direction": "none", "straight": false } },
                    "interactivity": { "events": { "onhover": { "enable": true, "mode": "grab" }, "onclick": { "enable": true, "mode": "push" } }, "modes": { "grab": { "distance": 180, "line_linked": { "opacity": 0.5 } }, "push": { "particles_nb": 4 } } }
                });
            };
            
            initParticles();
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === "class") {
                        initParticles();
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });
        });
    </script>
</body>
</html>