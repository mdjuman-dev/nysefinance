import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { installSpaLinks } from './utils/spa';
import SiteLayout from './Layouts/SiteLayout.vue';
import UserLayout from './Layouts/UserLayout.vue';

createInertiaApp({
    title: (title) => {
        const site = document.querySelector('meta[name="site-name"]')?.content || 'NyseFinance';
        return title ? `${site} - ${title}` : site;
    },
    resolve: async (name) => {
        // Lazy per-page chunks: heavy pages (trade, futures) only load when visited.
        const pages = import.meta.glob('./Pages/**/*.vue');
        const page = await pages[`./Pages/${name}.vue`]();
        page.default.layout ??= name.startsWith('User/') ? UserLayout : SiteLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        // Plain <a> links to Vue pages become Inertia visits (no full reload).
        const publicHome = !!props.initialPage.props.site?.publicHome;
        installSpaLinks(() => publicHome);

        // CSRF: send X-CSRF-TOKEN on every Inertia request (the X-XSRF-TOKEN cookie
        // header cannot be verified here) and keep the meta tag in sync with the
        // token the server shares, since login/forms can rotate it.
        const meta = document.querySelector('meta[name="csrf-token"]');
        const syncToken = (token) => token && meta && (meta.content = token);
        syncToken(props.initialPage.props.csrf);
        router.on('before', (e) => {
            e.detail.visit.headers = { ...e.detail.visit.headers, 'X-CSRF-TOKEN': meta?.content };
        });
        router.on('navigate', (e) => syncToken(e.detail.page.props.csrf));

        createApp({ render: () => h(App, props) }).use(plugin).mount(el);
    },
    progress: { color: '#3fd46e' },
});
