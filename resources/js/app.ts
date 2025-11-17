import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// PrimeVue imports
import PrimeVue from 'primevue/config';
import lara from '@primevue/themes/lara';
import 'primeicons/primeicons.css'; // PrimeIcons

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Example TypeScript function
function greet(name: string): string {
    return `Hello, ${name}!`;
}

console.log(greet('TypeScript User'));

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: lara
                }
            }) // Use PrimeVue with theme
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
