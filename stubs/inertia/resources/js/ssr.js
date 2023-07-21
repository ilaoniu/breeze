import { createInertiaApp } from "@/i-ui/inertia";
import createServer from "@/i-ui/inertia/server";
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
