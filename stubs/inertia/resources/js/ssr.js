import { createInertiaApp } from "@/i-ui";
import createServer from "@/i-ui/inertia/vue/server";
import { renderToString } from "@vue/server-renderer";
import { createApp, resolveComponent } from "@/inertia";

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        resolve: resolveComponent,
        setup({ App, props, plugin }) {
            return createApp({ App, props, plugin });
        }
    })
);
