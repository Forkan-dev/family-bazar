import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist';
import '../css/app.css';
import '../css/custom-ui.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Initialize theme on load
function initializeTheme() {
    const theme = localStorage.getItem('theme') || 'system';
    const prefersDark = window.matchMedia(
        '(prefers-color-scheme: dark)',
    ).matches;

    const root = document.documentElement;
    root.classList.remove('light', 'dark');

    if (theme === 'system') {
        root.classList.add(prefersDark ? 'dark' : 'light');
    } else {
        root.classList.add(theme);
    }
}

// Initialize theme immediately
if (typeof window !== 'undefined') {
    initializeTheme();
}

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
