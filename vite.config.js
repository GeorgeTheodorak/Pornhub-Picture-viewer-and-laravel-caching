import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: '0.0.0.0', // This allows Vite to be accessed from outside the container
        port: 5173,      // Vite's default port
        hmr: {
            host: 'localhost',  // Host for Hot Module Replacement (HMR)
        },
    }
});
