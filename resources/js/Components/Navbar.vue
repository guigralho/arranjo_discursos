<template>
    <Disclosure
        v-slot="{ open, close }"
        as="header"
        class="sticky top-0 z-50 bg-white/80 backdrop-blur-md dark:bg-gray-800"
    >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-12 items-center justify-between gap-8">
                <!-- Logo -->
                <Link
                    :href="route('dashboard')"
                    class="flex flex-shrink-0 items-center gap-2"
                >
                    <BookOpenIcon
                        class="h-5 w-5 text-gray-800 dark:text-white"
                    />
                    <span
                        class="text-sm font-semibold text-gray-900 dark:text-white"
                    >
                        {{ $page.props.app.name }}
                    </span>
                </Link>

                <!-- Desktop nav -->
                <nav class="hidden flex-1 items-center gap-1 lg:flex">
                    <template v-for="item in navigation" :key="item.name">
                        <Link
                            v-if="item.visible"
                            :aria-current="item.current ? 'page' : undefined"
                            :class="[
                                item.current
                                    ? 'text-gray-900 dark:text-white'
                                    : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white',
                                'flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium transition-colors duration-150',
                            ]"
                            :href="item.href"
                        >
                            <component
                                :is="item.icon"
                                :class="[
                                    item.current ? 'opacity-100' : 'opacity-50',
                                    'h-3.5 w-3.5',
                                ]"
                            />
                            {{ item.name }}
                        </Link>
                    </template>
                </nav>

                <!-- Right side -->
                <div class="flex items-center gap-1">
                    <!-- Dark mode toggle -->
                    <button
                        class="rounded-full p-1.5 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                        type="button"
                        @click="toggleDark()"
                    >
                        <span class="sr-only">Alternar tema</span>
                        <MoonIcon v-if="!isDark" class="h-4 w-4" />
                        <SunIcon v-else class="h-4 w-4" />
                    </button>

                    <!-- User dropdown -->
                    <Menu as="div" class="relative">
                        <MenuButton
                            class="flex items-center gap-2 rounded-full py-1 pl-1 pr-3 text-sm text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            <div
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-200 text-[10px] font-bold text-gray-700 dark:bg-gray-700 dark:text-gray-200"
                            >
                                {{ userInitials }}
                            </div>
                            <span class="hidden text-xs font-medium sm:block">
                                {{ $page.props.auth.user.name.split(" ")[0] }}
                            </span>
                            <ChevronDownIcon
                                class="hidden h-3 w-3 opacity-50 sm:block"
                            />
                        </MenuButton>

                        <transition
                            enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <MenuItems
                                class="absolute right-0 z-10 mt-2 w-56 origin-top-right overflow-hidden rounded-2xl bg-white shadow-xl shadow-gray-200/60 ring-1 ring-black/[0.05] focus:outline-none dark:bg-gray-800 dark:shadow-none dark:ring-gray-700"
                            >
                                <!-- User info -->
                                <div class="px-4 py-3">
                                    <p
                                        class="text-xs text-gray-400 dark:text-gray-500"
                                    >
                                        Conectado como
                                    </p>
                                    <p
                                        class="mt-0.5 truncate text-sm font-medium text-gray-800 dark:text-gray-100"
                                    >
                                        {{ $page.props.auth.user.name }}
                                    </p>
                                </div>

                                <div
                                    class="border-t border-gray-100 py-1 dark:border-gray-700"
                                >
                                    <MenuItem v-slot="{ active }">
                                        <Link
                                            :class="[
                                                active
                                                    ? 'bg-gray-50 dark:bg-white/5'
                                                    : '',
                                                'flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 dark:text-gray-300',
                                            ]"
                                            :href="route('profile.edit')"
                                        >
                                            <UserIcon
                                                class="h-4 w-4 opacity-50"
                                            />
                                            Seu perfil
                                        </Link>
                                    </MenuItem>
                                </div>

                                <div
                                    class="border-t border-gray-100 py-1 dark:border-gray-700"
                                >
                                    <MenuItem v-slot="{ active }">
                                        <Link
                                            :class="[
                                                active
                                                    ? 'bg-gray-50 dark:bg-white/5'
                                                    : '',
                                                'flex w-full items-center gap-2.5 px-4 py-2 text-sm text-red-500',
                                            ]"
                                            :href="route('logout')"
                                            method="post"
                                            type="button"
                                        >
                                            <ArrowRightOnRectangleIcon
                                                class="h-4 w-4"
                                            />
                                            Sair
                                        </Link>
                                    </MenuItem>
                                </div>
                            </MenuItems>
                        </transition>
                    </Menu>

                    <!-- Mobile hamburger -->
                    <DisclosureButton
                        class="ml-1 rounded-md p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-700 lg:hidden"
                    >
                        <Bars3Icon v-if="!open" class="h-5 w-5" />
                        <XMarkIcon v-else class="h-5 w-5" />
                    </DisclosureButton>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <DisclosurePanel
            class="border-t border-gray-100 dark:border-gray-700 lg:hidden"
        >
            <div class="space-y-0.5 px-3 py-2">
                <template v-for="item in navigation" :key="item.name">
                    <DisclosureButton
                        v-if="item.visible"
                        :aria-current="item.current ? 'page' : undefined"
                        :class="[
                            item.current
                                ? 'bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white'
                                : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                            'flex w-full items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-medium',
                        ]"
                        :href="item.href"
                        as="a"
                    >
                        <component :is="item.icon" class="h-4 w-4 opacity-60" />
                        {{ item.name }}
                    </DisclosureButton>
                </template>
            </div>
        </DisclosurePanel>
    </Disclosure>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import {
    ArrowRightOnRectangleIcon,
    Bars3Icon,
    BookOpenIcon,
    CalendarDaysIcon,
    ChevronDownIcon,
    MicrophoneIcon,
    MoonIcon,
    Squares2X2Icon,
    SunIcon,
    TruckIcon,
    UserIcon,
    XMarkIcon
} from "@heroicons/vue/24/outline";
import { useDark, useToggle } from "@vueuse/core";
import { Link, router, usePage } from "@inertiajs/vue3";
import { computed, onMounted, ref } from "vue";

const isDark = useDark();
const toggleDark = useToggle(isDark);
const page = usePage();

const userInitials = computed(() => {
    const name = page.props.auth.user.name ?? "";
    return name
        .split(" ")
        .slice(0, 2)
        .map((n) => n[0])
        .join("")
        .toUpperCase();
});

const navigation = ref([]);

const setNavigation = () => {
    const isAdmin = page.props.auth.user.id === 1;
    const onlyBus = page.props.auth.user.id === 14;

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
            visible: !onlyBus,
        },
        {
            name: "Discursos",
            href: route("speeches.index"),
            current: route().current("speeches*"),
            icon: BookOpenIcon,
            visible: !onlyBus,
        },
        {
            name: "Oradores",
            href: route("speakers.index"),
            current: route().current("speakers*"),
            icon: MicrophoneIcon,
            visible: !onlyBus,
        },
        {
            name: "Ônibus",
            href: route("bus.index"),
            current: route().current("bus*"),
            icon: TruckIcon,
            visible: isAdmin || onlyBus,
        },
    ];
};

onMounted(() => {
    setNavigation();
});

router.on("navigate", () => {
    setNavigation();
});
</script>
