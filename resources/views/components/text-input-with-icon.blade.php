@props([
    'disabled' => false,
    'icon' => 'user', // El valor por defecto es solo el nombre del icono
    'addon' => null
])

<div class="relative">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <x-dynamic-component :component="'lucide-'.$icon" class="h-5 w-5 text-neutral-text/50 dark:text-dark-neutral-text/50"/>
    </span>

    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block w-full pl-10 pr-3 py-2.5 bg-black/5 dark:bg-white/10 text-secondary dark:text-white border-gray-300 dark:border-white/20 focus:ring-dark-primary focus:border-dark-primary placeholder:text-neutral-text/70 dark:placeholder:text-white/60 rounded-md shadow-sm']) !!}>

    @if($addon)
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <span class="text-neutral-text/50 dark:text-dark-neutral-text/50 sm:text-sm">{{ $addon }}</span>
        </div>
    @endif
</div>