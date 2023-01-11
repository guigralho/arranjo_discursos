<script setup>
import { Link } from "@inertiajs/inertia-vue3";

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
        class="flex-shrink-0 rounded-lg bg-red-800 px-4 py-2 text-center text-xs font-semibold uppercase tracking-widest text-white shadow-md hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-red-200"
    >
        <slot />
    </Component>
</template>
