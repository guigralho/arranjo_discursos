<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    type: {
        type: String,
        default: "submit",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    href: {
        type: String,
        default: null,
    },
    block: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <Component
        :is="href ? Link : 'button'"
        :class="[
            block ? 'flex-1' : 'flex-1 sm:flex-none',
            disabled
                ? 'cursor-not-allowed bg-stone-300 text-stone-500 dark:bg-slate-700 dark:text-slate-500'
                : 'bg-stone-200 text-stone-700 hover:bg-stone-300 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600',
        ]"
        :href="href"
        :type="type"
        class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-center text-sm font-semibold transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 sm:w-fit"
    >
        <slot />
    </Component>
</template>
