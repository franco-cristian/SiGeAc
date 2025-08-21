@props(['name', 'title'])

<div 
    x-data="{ show: false, name: '{{ $name }}' }"
    x-show="show"
    x-on:open-modal.window="show = ($event.detail.name === name)"
    x-on:close-modal.window="show = false"
    x-on:keydown.escape.window="show = false"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4" 
    style="display: none;"
>
    <div @click.away="show = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
        @if(isset($title))
            <h3 class="text-xl font-bold text-secondary dark:text-dark-secondary">{{ $title }}</h3>
        @endif
        
        <div class="mt-4">
            {{ $slot }}
        </div>
    </div>
</div>