import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                    'resources/js/app.js', 
                    'resources/js/auth.js',
                    'resources/js/validation.js',
                    'resources/js/chatbot.js',
                    'resources/images/icons/Baku.png',
                    'resources/css/chatbot.css'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {   
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
