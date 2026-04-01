import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/menu-create.js', 'resources/js/slot.js', 'resources/js/meal-record.js'],
            refresh: true,
        }),
    ],
});
