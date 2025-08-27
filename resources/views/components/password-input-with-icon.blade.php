<div x-data="{ show: false }" class="relative">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <x-lucide-lock class="h-5 w-5 text-neutral-text/50 dark:text-dark-neutral-text/50"/>
    </span>

    <input id="password" name="password" :type="show ? 'text' : 'password'" required autocomplete="new-password" placeholder="Contraseña"
           class="block w-full pl-10 pr-10 py-2.5 bg-black/5 dark:bg-white/10 text-secondary dark:text-white border-gray-300 dark:border-white/20 focus:ring-dark-primary focus:border-dark-primary placeholder:text-neutral-text/70 dark:placeholder:text-white/60 rounded-md shadow-sm">
    
    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 z-10 text-neutral-text/50 dark:text-dark-neutral-text/50 hover:text-primary dark:hover:text-dark-primary">
        <x-lucide-eye-off x-show="!show" class="h-5 w-5" />
        <x-lucide-eye x-show="show" class="h-5 w-5" />
    </button>
</div>