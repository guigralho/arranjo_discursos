<script setup>
import { Link } from "@inertiajs/inertia-vue3";

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
                ? 'cursor-not-allowed bg-gray-600'
                : 'bg-gray-200 hover:bg-gray-400 focus:bg-gray-700 active:bg-gray-900',
        ]"
        :href="href"
        :type="type"
        class="gap-1 rounded-md border border-transparent px-4 py-2 text-center text-xs font-semibold uppercase tracking-widest text-gray-800 transition duration-150 ease-in-out hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-400 dark:hover:text-gray-800 sm:w-fit"
    >
        <slot />
    </Component>
</template>
