import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class', // Habilita el modo oscuro basado en clases

    theme: {
        extend: {
            colors: {
                // Modo Light
                primary: '#3B82F6',
                secondary: '#0F172A',
                accent: {
                    success: '#14B8A6',
                    creative: '#0EA5E9',
                    error: '#EF4444',
                },
                neutral: {
                    background: '#F9FAFB',
                    card: '#FFFFFF',
                    'card-transparent': 'rgba(255, 255, 255, 0.7)',
                    text: '#64748B',
                },

                // Modo Dark (los nombres son los mismos para facilidad de uso)
                'dark-primary': '#60A5FA',
                'dark-secondary': '#F1F5F9',
                'dark-accent': {
                    success: '#2DD4BF',
                    creative: '#38BDF8',
                    error: '#F87171',
                },
                'dark-neutral': {
                    background: '#1E293B',
                    card: '#334155',
                    text: '#94A3B8',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};