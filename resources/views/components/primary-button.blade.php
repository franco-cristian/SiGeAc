<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white dark:text-dark-secondary uppercase tracking-widest transition ease-in-out duration-150
                bg-gradient-to-r from-primary to-accent-creative hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary
                dark:from-dark-primary dark:to-dark-accent-creative dark:focus:ring-offset-dark-neutral-background'
    ]) }}>
    {{ $slot }}
</button>