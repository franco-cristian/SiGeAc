<div {{ $attributes->merge(['class' => 'flex flex-col items-center text-center']) }}>
    <!-- Fila Superior: Isotipo + "UTN" -->
    <div class="flex items-center">
        <!-- Isotipo SVG -->
        <svg class="h-12 w-auto text-secondary dark:text-white" viewBox="0 0 595.3 699.4" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="m246.6 0h102v190.8c80.8-22.4 140.4-96.7 140.4-184.4h106.3c0 146.5-106.8 268.9-246.6 293.2v4.4h233.9v104.2h-214.4c130 31.8 227 149.5 227 289.1h-106.2c0-87.7-59.6-162-140.3-184.4v186.5h-102v-186.5c-80.7 22.4-140.3 96.7-140.3 184.4h-106.4c0-139.6 97-257.3 227-289.1h-214.2v-104.2h233.9v-4.4c-139.9-24.3-246.7-146.7-246.7-293.2h106.3c0 87.7 59.6 162 140.3 184.4z" />
        </svg>
        <!-- Texto "UTN" -->
        <span class="ml-4 text-6xl font-black tracking-wider text-secondary dark:text-white">UTN</span>
    </div>
    <!-- Fila Inferior: Texto completo -->
    <div class="mt-2 leading-tight">
        <p class="font-semibold text-neutral-text dark:text-white/80">UNIVERSIDAD TECNOLÓGICA NACIONAL</p>
    </div>
</div>