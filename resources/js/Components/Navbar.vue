<template>
    <Disclosure v-slot="{ open }" as="nav" class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <div
                    class="absolute inset-y-0 left-0 flex items-center sm:hidden"
                >
                    <!-- Mobile menu button-->
                    <DisclosureButton
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                    >
                        <span class="sr-only">Open main menu</span>
                        <Bars3Icon
                            v-if="!open"
                            aria-hidden="true"
                            class="block h-6 w-6"
                        />
                        <XMarkIcon
                            v-else
                            aria-hidden="true"
                            class="block h-6 w-6"
                        />
                    </DisclosureButton>
                </div>
                <div
                    class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start"
                >
                    <div class="flex flex-shrink-0 items-center">
                        <h1 class="text-xl font-bold text-gray-400">
                            {{ $inertia.page.props.app.name }}
                        </h1>
                    </div>
                    <div class="hidden sm:ml-6 sm:block">
                        <div class="flex space-x-4">
                            <div v-for="item in navigation" :key="item.name">
                                <Link
                                    v-if="
                                        (item.name === 'Ônibus' &&
                                            $inertia.page.props.auth.user.id ===
                                                1) ||
                                        item.name !== 'Ônibus'
                                    "
                                    :aria-current="
                                        item.current ? 'page' : undefined
                                    "
                                    :class="[
                                        item.current
                                            ? 'bg-gray-900 text-white'
                                            : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                                        'rounded-md px-3 py-2 text-sm font-medium',
                                    ]"
                                    :href="item.href"
                                    >{{ item.name }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0"
                >
                    <button
                        class="rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                        type="button"
                        @click="toggleDark()"
                    >
                        <span class="sr-only">Dark mode</span>
                        <MoonIcon
                            v-if="!isDark"
                            aria-hidden="true"
                            class="h-6 w-6"
                        />
                        <SunIcon
                            v-if="isDark"
                            aria-hidden="true"
                            class="h-6 w-6"
                        />
                    </button>

                    <!-- Profile dropdown -->
                    <Menu as="div" class="relative ml-3">
                        <div>
                            <MenuButton
                                class="flex rounded-full bg-gray-800 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                            >
                                <span class="sr-only">Open user menu</span>
                                <UserCircleIcon
                                    aria-hidden="true"
                                    class="h-6 w-6"
                                />
                            </MenuButton>
                        </div>
                        <transition
                            enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <MenuItems
                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            >
                                <p
                                    class="block px-4 py-2 text-sm text-gray-700"
                                >
                                    Olá,
                                    {{ $inertia.page.props.auth.user.name }}
                                </p>
                                <MenuItem v-slot="{ active }">
                                    <Link
                                        :class="[
                                            active ? 'bg-gray-100' : '',
                                            'block px-4 py-2 text-sm text-gray-700',
                                        ]"
                                        :href="route('profile.edit')"
                                    >
                                        Seu perfil
                                    </Link>
                                </MenuItem>
                                <MenuItem v-slot="{ active }">
                                    <Link
                                        :class="[
                                            active ? 'bg-gray-100' : '',
                                            'block px-4 py-2 text-sm text-gray-700',
                                        ]"
                                        :href="route('logout')"
                                        method="post"
                                        type="button"
                                        >Sair
                                    </Link>
                                </MenuItem>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </div>
        </div>

        <DisclosurePanel class="sm:hidden">
            <div class="space-y-1 px-2 pt-2 pb-3">
                <DisclosureButton
                    v-for="item in navigation"
                    :key="item.name"
                    :aria-current="item.current ? 'page' : undefined"
                    :class="[
                        item.current
                            ? 'bg-gray-900 text-white'
                            : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                        'block rounded-md px-3 py-2 text-base font-medium',
                    ]"
                    :href="item.href"
                    as="a"
                    >{{ item.name }}
                </DisclosureButton>
            </div>
        </DisclosurePanel>
    </Disclosure>
</template>

<script setup>
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from "@headlessui/vue";
import {
    Bars3Icon,
    MoonIcon,
    SunIcon,
    UserCircleIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { useDark, useToggle } from "@vueuse/core";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";

const isDark = useDark();
const toggleDark = useToggle(isDark);

let navigation = ref([]);

const setNavigation = () => {
    navigation.value = [
        {
            name: "Dashboard",
            href: route("dashboard"),
            current: route().current("dashboard*"),
        },
        {
            name: "Arranjos",
            href: route("schedules.index"),
            current: route().current("schedules*"),
        },
        {
            name: "Discursos",
            href: route("speeches.index"),
            current: route().current("speeches*"),
        },
        {
            name: "Oradores",
            href: route("speakers.index"),
            current: route().current("speakers*"),
        },
        {
            name: "Ônibus",
            href: route("bus.index"),
            current: route().current("bus*"),
        },
    ];
};

Inertia.on("navigate", (event) => {
    setNavigation();
});
</script>
