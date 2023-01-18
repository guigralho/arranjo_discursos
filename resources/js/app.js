import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";

import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faChevronLeft,
    faChevronRight,
    faCircleCheck,
    faEdit,
    faMoon,
    faSort,
    faSortAsc,
    faSortDesc,
    faSpinner,
    faSun,
    faTrash,
    faUserSecret,
} from "@fortawesome/free-solid-svg-icons";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { faWhatsapp } from "@fortawesome/free-brands-svg-icons";

library.add(
    faUserSecret,
    faMoon,
    faSun,
    faChevronLeft,
    faChevronRight,
    faEdit,
    faTrash,
    faCircleCheck,
    faSpinner,
    faSortAsc,
    faSortDesc,
    faSort,
    faWhatsapp
);

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );
        page.then((module) => {
            console.log(module.default.layout);
            module.default.layout =
                module.default.layout === undefined
                    ? AuthenticatedLayout
                    : module.default.layout;
        });
        return page;
    },
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .component("font-awesome-icon", FontAwesomeIcon)
            .mount(el);
    },
});

InertiaProgress.init({ color: "#fff", showSpinner: true });
