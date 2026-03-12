<template>
    <nav
        v-if="breadcrumbs.length > 0"
        class="mb-1"
    >
        <ol class="flex items-center gap-1.5 text-sm">
            <li v-for="(page, index) in breadcrumbs" :key="index">
                <span v-if="page === '/'" class="text-stone-300 dark:text-slate-600">/</span>
                <Link
                    v-else
                    :href="page.url ?? '#'"
                    :class="page.current
                        ? 'font-semibold text-stone-900 dark:text-white'
                        : 'text-stone-500 hover:text-stone-700 dark:text-slate-400 dark:hover:text-slate-200'"
                    class="transition-colors"
                >
                    {{ page.title }}
                </Link>
            </li>
        </ol>
    </nav>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const insertBetween = (items, insertion) => {
    return items.flatMap((value, index, array) =>
        array.length - 1 !== index ? [value, insertion] : value
    );
};

const breadcrumbs = computed(() =>
    insertBetween(usePage().props.breadcrumbs || [], "/")
);
</script>
