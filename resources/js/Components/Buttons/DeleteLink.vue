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
        class="flex-shrink-0 rounded-md bg-red-800 px-3 py-1.5 text-xs font-semibold tracking-widest text-white shadow-md hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-sky-200"
    >
        <slot />
    </Component>
</template>
