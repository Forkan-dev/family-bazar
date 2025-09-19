import { createInertiaApp } from '@inertiajs/vue3';
import '@mdi/font/css/materialdesignicons.css';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import 'vuetify/dist/vuetify.min.css';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist';
import '../css/app.css';

const vuetify = createVuetify({
    theme: {
        defaultTheme: 'dark',
        variations: {
            colors: ['primary', 'secondary'],
            lighten: 2,
            darken: 2,
        },
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: '#1976D2',
                    'primary-darken-1': '#1565C0',
                    secondary: '#424242',
                    accent: '#82B1FF',
                    error: '#FF5252',
                    info: '#2196F3',
                    success: '#4CAF50',
                    warning: '#FB8C00',
                    background: '#FFFFFF',
                    surface: '#FFFFFF',
                    'surface-bright': '#FFFFFF',
                    'surface-light': '#EEEEEE',
                    'surface-variant': '#F5F5F5',
                    'on-surface': '#1C1B1F',
                    'on-surface-variant': '#49454F',
                    'primary-container': '#D0E4FF',
                    'on-primary-container': '#001D36',
                    'secondary-container': '#DDE3EA',
                    'on-secondary-container': '#101C20',
                },
            },
            dark: {
                dark: true,
                colors: {
                    primary: '#A8C7FA',
                    'primary-darken-1': '#7dabf8',
                    secondary: '#BCC7DC',
                    accent: '#FF4081',
                    error: '#FFB4AB',
                    info: '#A8C7FA',
                    success: '#A6D388',
                    warning: '#E8C547',
                    background: '#10141B',
                    surface: '#1D1B20',
                    'surface-bright': '#3B383E',
                    'surface-light': '#2B2930',
                    'surface-variant': '#49454F',
                    'on-surface': '#E6E0E9',
                    'on-surface-variant': '#CAC4D0',
                    'primary-container': '#004A77',
                    'on-primary-container': '#D0E4FF',
                    'secondary-container': '#3F4947',
                    'on-secondary-container': '#DDE3EA',
                },
            },
        },
    },
    components,
    directives,
});
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(vuetify)
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...

// initializeTheme();
