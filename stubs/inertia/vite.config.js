import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";
import laravel from "laravel-vite-plugin";
import inertia from "@inertiajs/vite";
import vue from "@vitejs/plugin-vue";
import { watch } from "vite-plugin-watch";

export default defineConfig({
    resolve: {
        alias: {
            ziggy: "/vendor/tightenco/ziggy"
        }
    },
    plugins: [
        laravel({
            input: ["resources/js/app.js"],
            refresh: true
        }),
        inertia(),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false
                }
            }
        }),
        watch({
            pattern: "routes/*.php",
            command: "php artisan ziggy:generate ./resources/js/ziggy/ziggy.js",
            silent: true
        })
    ]
});
