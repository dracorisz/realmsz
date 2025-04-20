import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { library } from '@fortawesome/fontawesome-svg-core';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

// Register Font Awesome icons
library.add(fab, fas, far);

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Global GSAP defaults
gsap.defaults({
    ease: "power2.out",
    duration: 0.5
});

const appName = import.meta.env.VITE_APP_NAME || 'RealmsZ';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast, {
                position: "top-right",
                transition: "Vue-Toastification__slideBlurred",
                maxToasts: 20,
                newestOnTop: true,
            })
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el);
    },
    progress: {
        color: '#ff2233',
    },
});

