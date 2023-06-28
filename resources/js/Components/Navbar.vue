<template>
    <Disclosure v-slot="{ open }" as="nav" class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <div
                    class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start"
                >
                    <div class="flex flex-shrink-0 items-center">
                        <h1 class="text-xl font-bold text-gray-400">
                            {{ $inertia.page.props.app.name }}
                        </h1>
                    </div>
                    <div class="hidden sm:ml-6 lg:block">
                        <div class="flex space-x-4">
                            <Link
                                key="Dashboard"
                                :class="[
                                    route().current('dashboard*')
                                        ? 'bg-gray-900 text-white'
                                        : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                                    'rounded-md px-3 py-2 text-sm font-medium',
                                ]"
                                :href="route('dashboard')"
                            >
                                Dashboard
                            </Link>
                            <Link
                                v-for="item in navigation"
                                :key="item.name"
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
                                >{{ item.name }}</Link
                            >
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
                                <MenuItem v-slot="{ active }">
                                    <Link
                                        :class="[
                                            active ? 'bg-gray-100' : '',
                                            'block px-4 py-2 text-sm text-gray-700',
                                        ]"
                                        :href="route('profile.edit')"
                                    >
                                        Your Profile
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
                                        >Sign out
                                    </Link>
                                </MenuItem>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </div>
        </div>
    </Disclosure>

    <div
        class="fixed bottom-0 left-0 z-50 h-16 w-full bg-white dark:bg-gray-800 lg:hidden"
    >
        <div class="mx-auto grid h-full max-w-lg grid-cols-4 font-medium">
            <Link
                v-for="item in navigation"
                :key="item.name"
                :aria-current="item.current ? 'page' : undefined"
                :class="[
                    item.current ? 'bg-gray-900' : 'text-gray-300',
                    'group inline-flex flex-col items-center justify-center px-5 text-sm dark:text-gray-400 ',
                ]"
                :href="item.href"
                type="button"
            >
                <font-awesome-icon
                    :class="[item.current ? 'text-white' : 'text-gray-300']"
                    :icon="item.icon"
                    size="lg"
                />
                <span :class="[item.current ? 'text-white' : 'text-gray-300']">
                    {{ item.name }}
                </span>
            </Link>
        </div>
    </div>
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
    XMarkIcon,
    MoonIcon,
    SunIcon,
    UserCircleIcon,
} from "@heroicons/vue/24/outline";
import { useDark, useToggle } from "@vueuse/core";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const isDark = useDark();
const toggleDark = useToggle(isDark);

let navigation = ref([]);

const setNavigation = () => {
    navigation.value = [
        {
            name: "Arranjos",
            href: route("schedules.index"),
            current: route().current("schedules*"),
            icon: "fa-solid fa-list",
        },
        {
            name: "Discursos",
            href: route("speeches.index"),
            current: route().current("speeches*"),
            icon: "fa-solid fa-file-lines",
        },
        {
            name: "Oradores",
            href: route("speakers.index"),
            current: route().current("speakers*"),
            icon: "fa-solid fa-list",
        },
        {
            name: "Ônibus",
            href: route("bus.index"),
            current: route().current("bus*"),
            icon: "fa-solid fa-list",
        },
    ];
};

Inertia.on("navigate", (event) => {
    setNavigation();
});
</script>
