<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <Component
        :is="href ? Link : 'button'"
        :href="href"
        class="inline-flex items-center justify-center rounded-lg bg-teal-50 p-2 text-teal-600 transition-colors hover:bg-teal-100 dark:bg-teal-500/10 dark:text-teal-400 dark:hover:bg-teal-500/20"
    >
        <slot />
    </Component>
</template>
