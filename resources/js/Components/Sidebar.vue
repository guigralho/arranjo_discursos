<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { useDark, useToggle } from "@vueuse/core";
import { computed, onMounted, ref } from "vue";
import {
    ArrowRightOnRectangleIcon,
    BookOpenIcon,
    CalendarDaysIcon,
    ChevronDoubleLeftIcon,
    ChevronDoubleRightIcon,
    MicrophoneIcon,
    MoonIcon,
    Squares2X2Icon,
    SunIcon,
    TruckIcon,
    UserIcon,
} from "@heroicons/vue/24/outline";
import { useSidebar } from "@/composables/useSidebar";

const isDark = useDark();
const toggleDark = useToggle(isDark);
const page = usePage();
const { collapsed, toggle } = useSidebar();

const isAdmin = computed(() => page.props.auth.user.id === 1);

const userInitials = computed(() => {
    const name = page.props.auth.user.name ?? "";
    return name
        .split(" ")
        .slice(0, 2)
        .map((n) => n[0])
        .join("")
        .toUpperCase();
});

const firstName = computed(() => {
    return page.props.auth.user.name.split(" ")[0];
});

const navigation = ref([]);

const setNavigation = () => {
    navigation.value = [
        {
            name: "Dashboard",
            href: route("dashboard"),
            current: route().current("dashboard*"),
            icon: Squares2X2Icon,
            visible: true,
        },
        {
            name: "Arranjos",
            href: route("schedules.index"),
            current: route().current("schedules*"),
            icon: CalendarDaysIcon,
            visible: true,
        },
        {
            name: "Discursos",
            href: route("speeches.index"),
            current: route().current("speeches*"),
            icon: BookOpenIcon,
            visible: true,
        },
        {
            name: "Oradores",
            href: route("speakers.index"),
            current: route().current("speakers*"),
            icon: MicrophoneIcon,
            visible: true,
        },
        {
            name: "Transporte",
            href: route("bus.index"),
            current: route().current("bus*") || route().current("passengers*"),
            icon: TruckIcon,
            visible: isAdmin.value,
        },
    ];
};

onMounted(() => setNavigation());
router.on("navigate", () => setNavigation());
</script>

<template>
    <aside
        :class="collapsed ? 'w-[72px]' : 'w-64'"
        class="fixed inset-y-0 left-0 z-40 hidden flex-col border-r border-stone-200 bg-white transition-all duration-300 dark:border-slate-800 dark:bg-slate-900 md:flex"
    >
        <!-- Logo area -->
        <div class="flex h-16 items-center gap-3 border-b border-stone-200 px-5 dark:border-slate-800">
            <Link
                :href="route('dashboard')"
                class="flex items-center gap-3 overflow-hidden"
            >
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-teal-600 text-white">
                    <BookOpenIcon class="h-5 w-5" />
                </div>
                <span
                    v-if="!collapsed"
                    class="truncate text-sm font-bold tracking-tight text-stone-900 dark:text-white"
                >
                    {{ $page.props.app.name }}
                </span>
            </Link>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            <template v-for="item in navigation" :key="item.name">
                <Link
                    v-if="item.visible"
                    :href="item.href"
                    :class="[
                        item.current
                            ? 'bg-teal-50 text-teal-700 dark:bg-teal-500/10 dark:text-teal-400'
                            : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white',
                    ]"
                    :title="collapsed ? item.name : undefined"
                    class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-150"
                >
                    <component
                        :is="item.icon"
                        :class="[
                            item.current
                                ? 'text-teal-600 dark:text-teal-400'
                                : 'text-stone-400 group-hover:text-stone-600 dark:text-slate-500 dark:group-hover:text-slate-300',
                        ]"
                        class="h-5 w-5 flex-shrink-0 transition-colors duration-150"
                    />
                    <span v-if="!collapsed" class="truncate">{{ item.name }}</span>
                </Link>
            </template>
        </nav>

        <!-- Bottom section -->
        <div class="border-t border-stone-200 p-3 dark:border-slate-800">
            <!-- Theme toggle -->
            <button
                class="mb-2 flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-stone-600 transition-colors hover:bg-stone-100 dark:text-slate-400 dark:hover:bg-slate-800"
                @click="toggleDark()"
            >
                <MoonIcon v-if="!isDark" class="h-5 w-5 text-stone-400 dark:text-slate-500" />
                <SunIcon v-else class="h-5 w-5 text-amber-400" />
                <span v-if="!collapsed">{{ isDark ? 'Modo claro' : 'Modo escuro' }}</span>
            </button>

            <!-- Collapse toggle -->
            <button
                class="mb-2 flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-stone-600 transition-colors hover:bg-stone-100 dark:text-slate-400 dark:hover:bg-slate-800"
                @click="toggle()"
            >
                <ChevronDoubleLeftIcon v-if="!collapsed" class="h-5 w-5 text-stone-400 dark:text-slate-500" />
                <ChevronDoubleRightIcon v-else class="h-5 w-5 text-stone-400 dark:text-slate-500" />
                <span v-if="!collapsed">Recolher</span>
            </button>

            <!-- User -->
            <Link
                :href="route('profile.edit')"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors hover:bg-stone-100 dark:hover:bg-slate-800"
            >
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-teal-100 text-xs font-bold text-teal-700 dark:bg-teal-500/20 dark:text-teal-400">
                    {{ userInitials }}
                </div>
                <div v-if="!collapsed" class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-stone-900 dark:text-white">{{ firstName }}</p>
                    <p class="truncate text-xs text-stone-500 dark:text-slate-500">Ver perfil</p>
                </div>
            </Link>

            <!-- Logout -->
            <Link
                :href="route('logout')"
                class="mt-1 flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-rose-500 transition-colors hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10"
                method="post"
                type="button"
            >
                <ArrowRightOnRectangleIcon class="h-5 w-5" />
                <span v-if="!collapsed">Sair</span>
            </Link>
        </div>
    </aside>
</template>