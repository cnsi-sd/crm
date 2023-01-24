import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/hyper.js',
                'resources/js/layout.js',
                'resources/js/tickets/ticket.js',
                'resources/scss/app.scss',
                'resources/scss/icons.scss',
            ],
            refresh: true,
        }),
    ],
});
