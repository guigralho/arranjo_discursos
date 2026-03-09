import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";

import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faAngleDown,
    faAngleUp,
    faArrowDown,
    faArrowLeft,
    faArrowUp,
    faBus,
    faCalendarXmark,
    faChevronLeft,
    faChevronRight,
    faCircleCheck,
    faCircleInfo,
    faClock,
    faEdit,
    faExclamationTriangle,
    faEye,
    faFileArrowDown,
    faFileLines,
    faInbox,
    faList,
    faMapMarkerAlt,
    faMicrophone,
    faMoon,
    faPaperPlane,
    faSort,
    faSortAsc,
    faSortDesc,
    faSpinner,
    faSun,
    faTimesCircle,
    faTrash,
    faUser,
    faUsers,
    faUserSecret,
} from "@fortawesome/free-solid-svg-icons";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { faWhatsapp } from "@fortawesome/free-brands-svg-icons";
import { faCalendar, faCircleUser } from "@fortawesome/free-regular-svg-icons";

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
    faArrowLeft,
    faCalendar,
    faCalendarXmark,
    faClock,
    faUser,
    faEye,
    faMicrophone,
    faPaperPlane,
    faMapMarkerAlt,
    faArrowDown,
    faInbox,
    faArrowUp,
    faExclamationTriangle
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
    progress: {
        color: "#3B82F6",
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .component("font-awesome-icon", FontAwesomeIcon)
            .mount(el);
    },
});
