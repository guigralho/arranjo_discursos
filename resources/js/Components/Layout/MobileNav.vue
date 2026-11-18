<script setup>
import { computed, ref } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from "@headlessui/vue";
import { ArrowRightStartOnRectangleIcon, EllipsisHorizontalIcon, UserCircleIcon } from "@heroicons/vue/24/outline";
import Wordmark from "@/Components/Layout/Wordmark.vue";
import ThemeToggle from "@/Components/Layout/ThemeToggle.vue";
import { useNavigation } from "@/composables/useNavigation";

const page = usePage();
const { flatItems, isCurrent } = useNavigation();
const isMoreOpen = ref(false);

const MAX_TABS = 4;
const tabs = computed(() => flatItems.value.slice(0, MAX_TABS));
const overflowItems = computed(() => flatItems.value.slice(MAX_TABS));
const isOverflowCurrent = computed(
    () => overflowItems.value.some((item) => isCurrent(item)) || page.url.startsWith("/profile")
);

router.on("navigate", () => (isMoreOpen.value = false));
</script>

<template>
    <div class="lg:hidden">
        <header
            class="sticky top-0 z-40 flex h-14 items-center justify-between border-b border-stone-200 bg-stone-50/90 px-4 backdrop-blur dark:border-stone-800 dark:bg-stone-950/90"
        >
            <Wordmark />
            <ThemeToggle />
        </header>

        <nav
            aria-label="Principal"
            class="pb-safe fixed inset-x-0 bottom-0 z-40 border-t border-stone-200 bg-white/95 backdrop-blur dark:border-stone-800 dark:bg-stone-900/95"
        >
            <ul class="mx-auto flex max-w-lg">
                <li v-for="item in tabs" :key="item.route" class="flex-1">
                    <Link
                        :aria-current="isCurrent(item) ? 'page' : undefined"
                        :class="isCurrent(item) ? 'text-pine-800 dark:text-pine-300' : 'text-stone-500 dark:text-stone-400'"
                        :href="route(item.route)"
                        class="flex h-16 flex-col items-center justify-center gap-1 text-[11px] font-medium"
                    >
                        <component :is="item.icon" aria-hidden="true" class="h-6 w-6" />
                        {{ item.name }}
                    </Link>
                </li>
                <li class="flex-1">
                    <button
                        :aria-expanded="isMoreOpen ? 'true' : 'false'"
                        :class="isOverflowCurrent ? 'text-pine-800 dark:text-pine-300' : 'text-stone-500 dark:text-stone-400'"
                        class="flex h-16 w-full flex-col items-center justify-center gap-1 text-[11px] font-medium"
                        type="button"
                        @click="isMoreOpen = true"
                    >
                        <EllipsisHorizontalIcon aria-hidden="true" class="h-6 w-6" />
                        Mais
                    </button>
                </li>
            </ul>
        </nav>

        <TransitionRoot :show="isMoreOpen" as="template">
            <Dialog as="div" class="relative z-50" @close="isMoreOpen = false">
                <TransitionChild
                    as="template"
                    enter="duration-150 ease-out"
                    enter-from="opacity-0"
                    leave="duration-100 ease-in"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-stone-950/40" />
                </TransitionChild>
                <div class="fixed inset-x-0 bottom-0">
                    <TransitionChild
                        as="template"
                        enter="duration-200 ease-out"
                        enter-from="translate-y-full"
                        leave="duration-150 ease-in"
                        leave-to="translate-y-full"
                    >
                        <DialogPanel
                            class="pb-safe rounded-t-2xl border-t border-stone-200 bg-white px-3 pt-2 dark:border-stone-800 dark:bg-stone-900"
                        >
                            <div aria-hidden="true" class="mx-auto mb-3 h-1 w-10 rounded-full bg-stone-300 dark:bg-stone-700" />
                            <DialogTitle class="sr-only">Mais opções</DialogTitle>
                            <div class="flex items-center gap-3 px-3 pb-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-stone-900 dark:text-stone-100">
                                        {{ page.props.auth.user.name }}
                                    </p>
                                    <p class="truncate text-xs text-stone-500 dark:text-stone-400">
                                        {{ page.props.auth.user.email }}
                                    </p>
                                </div>
                            </div>
                            <ul class="space-y-0.5 border-t border-stone-200 py-2 dark:border-stone-800">
                                <li v-for="item in overflowItems" :key="item.route">
                                    <Link
                                        :aria-current="isCurrent(item) ? 'page' : undefined"
                                        :href="route(item.route)"
                                        class="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm text-stone-800 hover:bg-stone-100 dark:text-stone-200 dark:hover:bg-stone-800"
                                    >
                                        <component :is="item.icon" aria-hidden="true" class="h-5 w-5 text-stone-500" />
                                        {{ item.name }}
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="route('profile.edit')"
                                        class="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm text-stone-800 hover:bg-stone-100 dark:text-stone-200 dark:hover:bg-stone-800"
                                    >
                                        <UserCircleIcon aria-hidden="true" class="h-5 w-5 text-stone-500" />
                                        Meu perfil
                                    </Link>
                                </li>
                                <li>
                                    <ThemeToggle with-label />
                                </li>
                            </ul>
                            <div class="border-t border-stone-200 py-2 dark:border-stone-800">
                                <Link
                                    :href="route('logout')"
                                    as="button"
                                    class="flex w-full items-center gap-3 rounded-md px-3 py-2.5 text-sm text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40"
                                    method="post"
                                >
                                    <ArrowRightStartOnRectangleIcon aria-hidden="true" class="h-5 w-5" />
                                    Sair
                                </Link>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>
