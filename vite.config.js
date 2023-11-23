import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/app.js',
            'resources/js/search.js',
            'resources/js/check.js',
            'resources/sass/app.scss',
        ]),
    ],
});
