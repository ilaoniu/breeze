import { NOOP } from "@/i-ui/utils/misc";
import { defaultDocument } from "@/i-ui/utils/client";
import { useEventListener } from "@/i-ui/utils/useEventListener";
import "../css/app.css";
import { createInertiaApp } from "@inertiajs/vue3";

// 解决 iOS Safari，input 等元素聚焦时，点击页面空白处，聚焦不消失的问题
useEventListener(defaultDocument, "click", NOOP, true);

createInertiaApp({
    progress: false
});
