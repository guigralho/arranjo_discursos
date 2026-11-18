<script setup>
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ArrowRightStartOnRectangleIcon, ChevronUpDownIcon, UserCircleIcon } from "@heroicons/vue/20/solid";
import Wordmark from "@/Components/Layout/Wordmark.vue";
import ThemeToggle from "@/Components/Layout/ThemeToggle.vue";
import { useNavigation } from "@/composables/useNavigation";

const page = usePage();
const { sections, isCurrent } = useNavigation();

const user = computed(() => page.props.auth.user);
const initials = computed(() =>
    (user.value?.name ?? "")
        .split(" ")
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0])
        .join("")
        .toUpperCase()
);
</script>

<template>
    <aside
        class="fixed inset-y-0 left-0 z-40 hidden w-60 flex-col border-r border-stone-200 bg-stone-100/70 dark:border-stone-800 dark:bg-stone-900/60 lg:flex"
    >
        <div class="flex h-16 items-center px-5">
            <Wordmark />
        </div>

        <nav aria-label="Principal" class="flex-1 space-y-6 overflow-y-auto px-3 py-4">
            <div v-for="section in sections" :key="section.label">
                <p class="eyebrow mb-1.5 px-3">{{ section.label }}</p>
                <ul class="space-y-0.5">
                    <li v-for="item in section.items" :key="item.route">
                        <Link
                            :aria-current="isCurrent(item) ? 'page' : undefined"
                            :class="
                                isCurrent(item)
                                    ? 'bg-white font-medium text-stone-900 shadow-sm ring-1 ring-stone-200 dark:bg-stone-800 dark:text-stone-50 dark:ring-stone-700'
                                    : 'text-stone-600 hover:bg-stone-200/60 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-stone-800/70 dark:hover:text-stone-100'
                            "
                            :href="route(item.route)"
                            class="flex items-center gap-3 rounded-md px-3 py-2 text-sm transition-colors"
                        >
                            <component
                                :is="item.icon"
                                :class="isCurrent(item) ? 'text-pine-700 dark:text-pine-400' : ''"
                                aria-hidden="true"
                                class="h-5 w-5 shrink-0"
                            />
                            {{ item.name }}
                        </Link>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="border-t border-stone-200 p-3 dark:border-stone-800">
            <div class="flex items-center gap-1">
                <Menu as="div" class="relative min-w-0 flex-1">
                    <MenuButton
                        class="flex w-full items-center gap-2.5 rounded-md p-1.5 text-left hover:bg-stone-200/60 dark:hover:bg-stone-800"
                    >
                        <span
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-stone-300 text-xs font-semibold text-stone-800 dark:bg-stone-700 dark:text-stone-100"
                            >{{ initials }}</span
                        >
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-medium text-stone-900 dark:text-stone-100">{{ user.name }}</span>
                            <span class="block truncate text-xs text-stone-500 dark:text-stone-400">{{ user.email }}</span>
                        </span>
                        <ChevronUpDownIcon aria-hidden="true" class="h-4 w-4 shrink-0 text-stone-400" />
                    </MenuButton>
                    <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="opacity-0 translate-y-1"
                        leave-active-class="transition duration-75 ease-in"
                        leave-to-class="opacity-0"
                    >
                        <MenuItems
                            class="absolute bottom-full left-0 mb-2 w-full rounded-lg border border-stone-200 bg-white p-1 shadow-float focus:outline-none dark:border-stone-700 dark:bg-stone-900"
                        >
                            <MenuItem v-slot="{ active }">
                                <Link
                                    :class="active ? 'bg-stone-100 dark:bg-stone-800' : ''"
                                    :href="route('profile.edit')"
                                    class="flex items-center gap-2.5 rounded-md px-2.5 py-2 text-sm text-stone-800 dark:text-stone-200"
                                >
                                    <UserCircleIcon aria-hidden="true" class="h-4 w-4 opacity-70" />
                                    Meu perfil
                                </Link>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                                <Link
                                    :class="active ? 'bg-stone-100 dark:bg-stone-800' : ''"
                                    :href="route('logout')"
                                    as="button"
                                    class="flex w-full items-center gap-2.5 rounded-md px-2.5 py-2 text-sm text-red-700 dark:text-red-400"
                                    method="post"
                                >
                                    <ArrowRightStartOnRectangleIcon aria-hidden="true" class="h-4 w-4" />
                                    Sair
                                </Link>
                            </MenuItem>
                        </MenuItems>
                    </transition>
                </Menu>
                <ThemeToggle />
            </div>
        </div>
    </aside>
</template>
