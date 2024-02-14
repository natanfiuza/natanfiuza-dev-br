import { createApp, h } from 'vue';

import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { InertiaProgress } from '@inertiajs/progress';

InertiaProgress.init();

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    title: (title) => (title ? `${title} - NatanFiuza.dev` : "NatanFiuza.dev"),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component("InertiaHead", Head)
            .component("InertiaLink", Link)
            .mount(el);
    },
});
