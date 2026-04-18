import { defineConfig, loadEnv } from "vite";
import tailwindcss from "@tailwindcss/vite";
import laravel from "laravel-vite-plugin";
import inertia from "@inertiajs/vite";
import vue from "@vitejs/plugin-vue";
import { watch } from "vite-plugin-watch";

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), "");

    return {
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
            inertia({
                ssr: {
                    port: parseInt(env.INERTIA_SSR_PORT || "13714")
                }
            }),
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
    };
});
