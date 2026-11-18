<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/16/solid";

/**
 * Accepts a plain Laravel paginator ({ links, from, to, total, current_page, last_page })
 * or an API resource collection ({ links: {...}, meta: {...} }).
 */
const props = defineProps({
    list: {
        type: Object,
        default: null,
    },
});

const meta = computed(() => props.list?.meta ?? props.list ?? {});

const pageLinks = computed(() => {
    const links = Array.isArray(meta.value.links) ? meta.value.links : [];
    return links.slice(1, -1);
});

const previousUrl = computed(() => {
    const links = meta.value.links;
    return Array.isArray(links) ? links[0]?.url : null;
});

const nextUrl = computed(() => {
    const links = meta.value.links;
    return Array.isArray(links) ? links[links.length - 1]?.url : null;
});

const lastPage = computed(() => meta.value.last_page ?? 1);
const currentPage = computed(() => meta.value.current_page ?? 1);
</script>

<template>
    <nav
        v-if="meta.total > 0"
        aria-label="Paginação"
        class="flex items-center justify-between gap-4 border-t border-stone-200 px-4 py-3 text-sm dark:border-stone-800 sm:px-5"
    >
        <p class="text-stone-600 dark:text-stone-400">
            <span class="hidden sm:inline">
                <span class="tabular-nums text-stone-900 dark:text-stone-100">{{ meta.from }}–{{ meta.to }}</span>
                de
                <span class="tabular-nums text-stone-900 dark:text-stone-100">{{ meta.total }}</span>
            </span>
            <span class="sm:hidden">
                Página <span class="tabular-nums">{{ currentPage }}</span> de
                <span class="tabular-nums">{{ lastPage }}</span>
            </span>
        </p>

        <div v-if="lastPage > 1" class="flex items-center gap-1">
            <component
                :is="previousUrl ? Link : 'span'"
                :aria-disabled="!previousUrl || undefined"
                :href="previousUrl ?? undefined"
                aria-label="Página anterior"
                class="inline-flex h-9 w-9 items-center justify-center rounded-md text-stone-600 hover:bg-stone-100 aria-disabled:pointer-events-none aria-disabled:opacity-40 dark:text-stone-300 dark:hover:bg-stone-800"
                preserve-scroll
            >
                <ChevronLeftIcon aria-hidden="true" class="h-4 w-4" />
            </component>

            <template v-for="(link, index) in pageLinks" :key="index">
                <span
                    v-if="!link.url"
                    aria-hidden="true"
                    class="hidden h-9 w-6 items-center justify-center text-stone-400 md:inline-flex"
                    >…</span
                >
                <Link
                    v-else
                    :aria-current="link.active ? 'page' : undefined"
                    :aria-label="`Página ${link.label}`"
                    :class="
                        link.active
                            ? 'bg-stone-900 text-white dark:bg-stone-100 dark:text-stone-900'
                            : 'text-stone-700 hover:bg-stone-100 dark:text-stone-300 dark:hover:bg-stone-800'
                    "
                    :href="link.url"
                    class="hidden h-9 min-w-[2.25rem] items-center justify-center rounded-md px-2 tabular-nums md:inline-flex"
                    preserve-scroll
                    >{{ link.label }}</Link
                >
            </template>

            <component
                :is="nextUrl ? Link : 'span'"
                :aria-disabled="!nextUrl || undefined"
                :href="nextUrl ?? undefined"
                aria-label="Próxima página"
                class="inline-flex h-9 w-9 items-center justify-center rounded-md text-stone-600 hover:bg-stone-100 aria-disabled:pointer-events-none aria-disabled:opacity-40 dark:text-stone-300 dark:hover:bg-stone-800"
                preserve-scroll
            >
                <ChevronRightIcon aria-hidden="true" class="h-4 w-4" />
            </component>
        </div>
    </nav>
</template>
