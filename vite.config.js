import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/admin-dashboard.css',
                'resources/js/admin-dashboard.js',
                'resources/js/sweet-alert.js',
                'resources/css/gallery.css',
                'resources/js/gallery.js',
                'resources/css/tiket.css',
                'resources/js/tiket.js',
                'resources/css/lokasi.css',
                'resources/js/lokasi.js',
                'resources/css/auth.css',
                'resources/js/auth.js',
                'resources/js/validation.js',
                'resources/js/chatbot.js',
                'resources/images/icons/Baku.png',
                'resources/css/chatbot.css',
                'resources/js/infowisata.js',
                'resources/css/infowisata.css',
            ],
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
