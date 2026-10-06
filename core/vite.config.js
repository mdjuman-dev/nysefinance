import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

// The web root is the project root (one level above core/), so built
// assets go to ../build and are served at /build. The hot file stays in
// core/public/hot where Laravel's Vite helper looks for it.
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            publicDirectory: '..',
            buildDirectory: 'build',
            hotFile: 'public/hot',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: { base: null, includeAbsolute: false },
            },
        }),
        tailwindcss(),
    ],
    build: { emptyOutDir: true },
    resolve: {
        alias: { '@': '/resources/js' },
    },
});
