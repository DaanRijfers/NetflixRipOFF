import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173, // Explicitly set the port
        strictPort: true, // Fail if the port is already in use
        hmr: {
            host: 'localhost', // Ensure hot module reload works
        },
    },
});
