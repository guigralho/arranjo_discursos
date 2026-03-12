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
        class="inline-flex items-center justify-center rounded-lg bg-rose-50 p-2 text-rose-600 transition-colors hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20"
    >
        <slot />
    </Component>
</template>
