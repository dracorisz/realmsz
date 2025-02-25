import "./bootstrap";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { route, ZiggyVue } from "../../vendor/tightenco/ziggy";
import outclick from "./Utils/outclick.js";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const appName = window.document.getElementsByTagName("title")[0]?.innerText;

createInertiaApp({
  title: (title) => ` ${title} &#183; ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue")),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(Toast, {
        position: "top-right",
        transition: "Vue-Toastification__slideBlurred",
        maxToasts: 20,
        newestOnTop: true,
      })
      .use(plugin)
      .use(ZiggyVue, Ziggy)
      .directive("outclick", outclick)
      .mount(el);
  },
  progress: {
    color: "#00509c",
  },
});

