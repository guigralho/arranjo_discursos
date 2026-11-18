import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import {
    BookOpenIcon,
    CalendarDaysIcon,
    HomeIcon,
    TicketIcon,
    UserGroupIcon,
    UsersIcon,
} from "@heroicons/vue/24/outline";
import { useAccess } from "@/composables/useAccess";

/** Navigation model shared by the desktop sidebar and the mobile tab bar. */
export function useNavigation() {
    const { canSeeArrangements, canSeeBus } = useAccess();
    const page = usePage();

    const sections = computed(() =>
        [
            {
                label: "Arranjo",
                items: [
                    { name: "Início", route: "dashboard", match: "dashboard", icon: HomeIcon, visible: true },
                    { name: "Arranjos", route: "schedules.index", match: "schedules.*", icon: CalendarDaysIcon, visible: canSeeArrangements.value },
                    { name: "Oradores", route: "speakers.index", match: "speakers.*", icon: UserGroupIcon, visible: canSeeArrangements.value },
                    { name: "Discursos", route: "speeches.index", match: "speeches.*", icon: BookOpenIcon, visible: canSeeArrangements.value },
                ],
            },
            {
                label: "Viagem",
                items: [
                    { name: "Ônibus", route: "bus.index", match: "bus.*", icon: TicketIcon, visible: canSeeBus.value },
                    { name: "Passageiros", route: "passengers.index", match: "passengers.*", icon: UsersIcon, visible: canSeeBus.value },
                ],
            },
        ]
            .map((section) => ({ ...section, items: section.items.filter((item) => item.visible) }))
            .filter((section) => section.items.length > 0)
    );

    const flatItems = computed(() => sections.value.flatMap((section) => section.items));

    /** Reactive to Inertia navigation: compares the page url with the item's path. */
    const isCurrent = (item) => {
        const currentPath = page.url.split("?")[0].split("#")[0].replace(/\/+$/, "") || "/";
        const itemPath = new URL(route(item.route), window.location.origin).pathname.replace(/\/+$/, "") || "/";
        return itemPath === "/" ? currentPath === "/" : currentPath === itemPath || currentPath.startsWith(`${itemPath}/`);
    };

    return { sections, flatItems, isCurrent };
}
