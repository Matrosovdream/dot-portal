import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'node:path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/spa/main.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/spa'),
        },
    },
    css: {
        preprocessorOptions: {
            // Use Sass's modern compiler API (silences the legacy-JS-API deprecation).
            scss: { api: 'modern-compiler' },
        },
    },
});
