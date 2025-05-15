import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { library } from "@fortawesome/fontawesome-svg-core";
import { fab } from "@fortawesome/free-brands-svg-icons";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import ScrollTrigger from "gsap/ScrollTrigger";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import gsap from "gsap";

library.add(fab, fas, far);

gsap.registerPlugin(ScrollTrigger);
gsap.defaults({
  ease: "power2.out",
  duration: 0.5,
});

const appName = import.meta.env.VITE_APP_NAME || "RealmsZ";

// Toast notification configuration
const toastOptions = {
  position: "top-right",
  timeout: {
    success: 3000,
    error: 5000,
    warning: 4000,
    info: 3000,
    default: 3000
  },
  maxToasts: 5,
  newestOnTop: true,
  transition: "Vue-Toastification__fade",
  hideProgressBar: false,
  closeOnClick: true,
  pauseOnFocusLoss: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.6,
  showCloseButtonOnHover: false,
  closeButton: "button",
  icon: true,
  rtl: false
};

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue")),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(Toast, toastOptions)
      .component("font-awesome-icon", FontAwesomeIcon)
      .mount(el);
  },
  progress: {
    color: "#c4b5fd",
  },
});

