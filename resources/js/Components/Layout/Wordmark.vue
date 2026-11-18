<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link :href="route('dashboard')" class="group inline-flex items-center gap-2.5 rounded-md">
        <span
            aria-hidden="true"
            class="flex h-8 w-8 items-center justify-center rounded-md bg-pine-800 font-display text-base font-semibold text-pine-50 dark:bg-pine-500 dark:text-pine-950"
            >A</span
        >
        <span class="font-display text-[17px] font-medium leading-none tracking-tight text-stone-900 dark:text-stone-50">
            {{ $page.props.app.name }}
        </span>
    </Link>
</template>
