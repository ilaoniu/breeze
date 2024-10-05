import { defineConfig } from "ilaoniu-vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { watch } from "vite-plugin-watch";

export default defineConfig({
    resolve: {
        alias: {
            ziggy: "/vendor/tightenco/ziggy/dist/vue.es.js",
            highlight: "/node_modules/highlight.js/es"
        }
    },
    plugins: [
        laravel({
            input: "resources/js/app.js",
            ssr: "resources/js/ssr.js",
            refresh: true
        }),
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
