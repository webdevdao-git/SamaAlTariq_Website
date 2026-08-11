import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/portal.js'],
            refresh: true,
            /**
             * Fonts are downloaded at build time and served from our own origin
             * rather than linked from a third party — one less external request
             * on a shared host, and no font CDN in the critical path.
             *
             * Manrope and Cormorant Garamond are the faces the Figma file uses.
             * Playfair Display stands in for "Juana Alt Medium" (Latinotype,
             * commercial licence); see resources/css/app.css.
             *
             * Manrope is now loaded from the supplied local assets in
             * resources/fonts, so it does not need to be downloaded from Bunny.
             */
            fonts: [
                bunny('Playfair Display', { weights: [400, 500, 600] }),
                bunny('Cormorant Garamond', { weights: [400, 600] }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
