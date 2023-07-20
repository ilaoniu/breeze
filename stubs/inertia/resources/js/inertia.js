import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createSSRApp, h } from "vue";
import { ZiggyVue } from "ziggy";
import { Ziggy } from "@/ziggy/ziggy";

export async function resolveComponent(name) {
    const module = await resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue"));
    const page = module.default;
    return page;
}

export function createApp({ App, props, plugin }) {
    return createSSRApp({ render: () => h(App, props) })
        .use(plugin)
        .use(ZiggyVue, Ziggy);
}
