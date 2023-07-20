import "./bootstrap";
import "../css/app.scss";
import { createInertiaApp } from "@/i-ui";
import { createApp, resolveComponent } from "@/inertia";

createInertiaApp({
    resolve: resolveComponent,
    setup({ el, App, props, plugin }) {
        return createApp({ App, props, plugin }).mount(el);
    }
});
