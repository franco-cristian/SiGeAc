<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Información del Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Actualiza la información de tu perfil y tu dirección de correo electrónico.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Foto de Perfil Mejorada -->
        <div x-data="{ photoPreview: '{{ $user->photo_path ? route('users.photo', $user) : '' }}' }" class="flex flex-col items-center">
            <input type="file" name="photo" id="photo" class="hidden" 
                   @change="const reader = new FileReader();
                           reader.onload = (e) => { photoPreview = e.target.result };
                           reader.readAsDataURL($event.target.files[0]);">
            
            <label for="photo" class="cursor-pointer group">
                <div class="w-32 h-32 rounded-full border-2 border-dashed border-gray-300 dark:border-white/20 flex items-center justify-center relative overflow-hidden
                            group-hover:border-primary dark:group-hover:border-dark-primary transition-all duration-300">
                    <div x-show="!photoPreview" class="text-gray-500 dark:text-white/50">
                        <x-lucide-camera class="w-12 h-12"/>
                    </div>
                    <div x-show="photoPreview" class="w-full h-full">
                        <img :src="photoPreview" alt="Previsualización" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span class="text-white text-sm font-semibold">Cambiar Foto</span>
                    </div>
                </div>
            </label>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">JPG, PNG, WEBP. Máx 2MB.</p>
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <!-- Campos de Información -->
        <div>
            <x-input-label for="name" value="Nombre Completo" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Correo Electrónico" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
            {{-- ... (lógica de verificación de email de Breeze) ... --}}
        </div>

        <div>
            <x-input-label for="phone" value="Teléfono" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $user->phone)" required />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="date_of_birth" value="Fecha de Nacimiento" />
            <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" :value="old('date_of_birth', $user->date_of_birth?->format('Y-m-d'))" required />
            <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
        </div>

        <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 border-t border-gray-200 dark:border-gray-700 pt-4">
            Enlaces Profesionales
        </h3>

        <div>
            <x-input-label for="github_username" value="Usuario de GitHub" />
            <x-text-input-with-icon icon="github" id="github_username" name="github_username" class="mt-1 block w-full" :value="old('github_username', $github_username)" addon="github.com/" />
            <x-input-error class="mt-2" :messages="$errors->get('github_username')" />
        </div>
        
        <div>
            <x-input-label for="linkedin_username" value="Usuario de LinkedIn" />
            <x-text-input-with-icon icon="linkedin" id="linkedin_username" name="linkedin_username" class="mt-1 block w-full" :value="old('linkedin_username', $linkedin_username)" addon="linkedin.com/in/" />
            <x-input-error class="mt-2" :messages="$errors->get('linkedin_username')" />
        </div>

        <div>
            <x-input-label for="professional_url" value="Portafolio / Sitio Web" />
            <x-text-input-with-icon icon="globe" id="professional_url" name="professional_url" type="url" class="mt-1 block w-full" :value="old('professional_url', $user->professional_url)" />
            <x-input-error class="mt-2" :messages="$errors->get('professional_url')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Guardar Cambios</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
            @endif
        </div>
    </form>
</section>