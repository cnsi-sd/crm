import { defineConfig } from 'vite';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import laravel from 'laravel-vite-plugin';


export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/hyper.js',
                'resources/js/layout.js',
                'resources/js/tickets/ticket.js',
                'resources/js/tickets/showTicket.js',
                'resources/js/revival.js',
                'resources/scss/app.scss',
                'resources/scss/icons.scss',
                'resources/js/tags.js',
                'resources/js/tinymce.js',
                'resources/js/savNotes.js',
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'vendor/tinymce/tinymce',
                    dest: ''
                }
            ]
        })
    ],
});
