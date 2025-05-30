import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ command, mode }) => ({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        manifest: mode !== 'development', // Solo generar manifiesto en producción
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
}));
