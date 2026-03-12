<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import {
    BookOpenIcon,
    CalendarDaysIcon,
    MicrophoneIcon,
    Squares2X2Icon,
    TruckIcon,
} from "@heroicons/vue/24/outline";
import {
    BookOpenIcon as BookOpenIconSolid,
    CalendarDaysIcon as CalendarDaysIconSolid,
    MicrophoneIcon as MicrophoneIconSolid,
    Squares2X2Icon as Squares2X2IconSolid,
    TruckIcon as TruckIconSolid,
} from "@heroicons/vue/24/solid";
import { computed, onMounted, ref } from "vue";

const page = usePage();
const isAdmin = computed(() => page.props.auth.user.id === 1);

const navigation = ref([]);

const setNavigation = () => {
    const items = [
        {
            name: "Início",
            href: route("dashboard"),
            current: route().current("dashboard*"),
            icon: Squares2X2Icon,
            iconActive: Squares2X2IconSolid,
            visible: true,
        },
        {
            name: "Arranjos",
            href: route("schedules.index"),
            current: route().current("schedules*"),
            icon: CalendarDaysIcon,
            iconActive: CalendarDaysIconSolid,
            visible: true,
        },
        {
            name: "Discursos",
            href: route("speeches.index"),
            current: route().current("speeches*"),
            icon: BookOpenIcon,
            iconActive: BookOpenIconSolid,
            visible: true,
        },
        {
            name: "Oradores",
            href: route("speakers.index"),
            current: route().current("speakers*"),
            icon: MicrophoneIcon,
            iconActive: MicrophoneIconSolid,
            visible: true,
        },
        {
            name: "Transporte",
            href: route("bus.index"),
            current: route().current("bus*") || route().current("passengers*"),
            icon: TruckIcon,
            iconActive: TruckIconSolid,
            visible: isAdmin.value,
        },
    ];

    navigation.value = items.filter((i) => i.visible);
};

onMounted(() => setNavigation());
router.on("navigate", () => setNavigation());
</script>

<template>
    <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-stone-200 bg-white/90 backdrop-blur-lg dark:border-slate-800 dark:bg-slate-900/90 md:hidden">
        <div class="flex items-center justify-around px-2 pb-safe">
            <Link
                v-for="item in navigation"
                :key="item.name"
                :href="item.href"
                class="relative flex flex-1 flex-col items-center gap-0.5 py-2 pt-2.5"
            >
                <div
                    :class="item.current
                        ? 'bg-teal-100 dark:bg-teal-500/15'
                        : 'bg-transparent'"
                    class="flex h-8 w-14 items-center justify-center rounded-2xl transition-all duration-200"
                >
                    <component
                        :is="item.current ? item.iconActive : item.icon"
                        :class="item.current
                            ? 'text-teal-600 dark:text-teal-400'
                            : 'text-stone-400 dark:text-slate-500'"
                        class="h-5 w-5 transition-colors duration-150"
                    />
                </div>
                <span
                    :class="item.current
                        ? 'text-teal-700 font-semibold dark:text-teal-400'
                        : 'text-stone-500 dark:text-slate-500'"
                    class="text-[10px] leading-tight transition-colors duration-150"
                >
                    {{ item.name }}
                </span>
            </Link>
        </div>
    </nav>
</template>