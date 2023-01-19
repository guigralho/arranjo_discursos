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
    <Link
        :href="href"
        class="flex-shrink-0 rounded-md bg-sky-800 px-4 py-2 text-center text-xs font-semibold tracking-widest text-white shadow-md hover:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-sky-200"
    >
        <slot />
    </Link>
</template>
