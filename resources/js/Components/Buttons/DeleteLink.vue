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
        class="gap-1 rounded-md border border-transparent bg-red-800 px-4 py-2 text-center text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
    >
        <slot />
    </Component>
</template>
