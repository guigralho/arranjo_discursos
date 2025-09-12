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
    faAngleDown,
    faAngleUp,
    faArrowLeft,
    faBus,
    faChevronLeft,
    faChevronRight,
    faCircleCheck,
    faCircleInfo,
    faEdit,
    faFileArrowDown,
    faFileLines,
    faList,
    faMoon,
    faSort,
    faSortAsc,
    faSortDesc,
    faSpinner,
    faSun,
    faTimesCircle,
    faTrash,
    faUsers,
    faUserSecret,
} from "@fortawesome/free-solid-svg-icons";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { faWhatsapp } from "@fortawesome/free-brands-svg-icons";
import { faCircleUser } from "@fortawesome/free-regular-svg-icons";

library.add(
    faUserSecret,
    faMoon,
    faSun,
    faChevronLeft,
    faChevronRight,
    faEdit,
    faTrash,
    faCircleCheck,
    faTimesCircle,
    faSpinner,
    faSortAsc,
    faSortDesc,
    faSort,
    faWhatsapp,
    faAngleUp,
    faAngleDown,
    faCircleUser,
    faUsers,
    faFileArrowDown,
    faCircleInfo,
    faList,
    faBus,
    faFileLines,
    faArrowLeft
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

InertiaProgress.init({
    color: "#3B82F6",
    showSpinner: false,
    delay: 100,
    includeCSS: true,
});
