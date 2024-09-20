import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import Vueform from '@vueform/vueform'
import vueformConfig from './../../vueform.config'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createNotivue } from 'notivue';
import 'notivue/notification.css'; // Only needed if using built-in notifications
import 'notivue/animations.css'; // Only needed if using built-in animations

const notivue = createNotivue({
    position: 'top-right',
    limit: 4,
    enqueue: true,
    notifications: {
        global: {
            duration: 3000,
        },
    }
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(notivue)
            .use(Vueform, vueformConfig)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
