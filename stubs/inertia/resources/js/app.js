import "./bootstrap";
import "../css/app.css";
import { createInertiaApp } from "@inertiajs/vue3";
import { createApp, resolveComponent } from "@/inertia";

createInertiaApp({
    resolve: resolveComponent,
    setup({ el, App, props, plugin }) {
        return createApp({ App, props, plugin }).mount(el);
    },
    progress: false
});
