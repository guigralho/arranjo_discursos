<script setup>
import { Link } from "@inertiajs/vue3";
import ThemeToggle from "@/Components/Layout/ThemeToggle.vue";

defineProps({
    title: { type: String, default: null },
    description: { type: String, default: null },
});
</script>

<template>
    <div class="min-h-screen bg-stone-50 dark:bg-stone-950 lg:grid lg:grid-cols-[minmax(0,5fr)_minmax(0,6fr)]">
        <!-- Brand panel -->
        <aside class="relative hidden flex-col justify-between overflow-hidden bg-pine-900 p-12 text-pine-50 lg:flex">
            <Link :href="route('dashboard')" class="inline-flex items-center gap-2.5 self-start">
                <span aria-hidden="true" class="flex h-8 w-8 items-center justify-center rounded-md bg-pine-50 font-display font-semibold text-pine-900">A</span>
                <span class="font-display text-lg font-medium">{{ $page.props.app.name }}</span>
            </Link>
            <div class="max-w-md">
                <p class="font-display text-[2.5rem] font-medium leading-[1.1] tracking-tight">
                    Quem vem, quem vai, e com qual tema.
                </p>
                <p class="mt-4 text-pine-200">
                    O arranjo mensal de discursos públicos entre congregações, organizado em um só lugar.
                </p>
            </div>
            <div aria-hidden="true" class="grid max-w-sm grid-cols-4 gap-px overflow-hidden rounded-md border border-pine-700/60 bg-pine-700/60 text-center text-xs">
                <div v-for="(day, index) in ['07', '14', '21', '28']" :key="day" class="bg-pine-900 px-2 py-3">
                    <span class="block font-display text-2xl text-pine-50">{{ day }}</span>
                    <span :class="index % 2 ? 'text-clay-300' : 'text-pine-300'" class="mt-1 block uppercase tracking-wide">
                        {{ index % 2 ? "envia" : "recebe" }}
                    </span>
                </div>
            </div>
        </aside>

        <!-- Form -->
        <main class="flex min-h-screen flex-col px-5 py-6 sm:px-10 lg:min-h-0">
            <div class="flex items-center justify-between">
                <Link :href="route('dashboard')" class="inline-flex items-center gap-2.5 lg:invisible">
                    <span aria-hidden="true" class="flex h-8 w-8 items-center justify-center rounded-md bg-pine-800 font-display font-semibold text-pine-50 dark:bg-pine-500 dark:text-pine-950">A</span>
                    <span class="font-display text-[17px] font-medium text-stone-900 dark:text-stone-50">{{ $page.props.app.name }}</span>
                </Link>
                <ThemeToggle />
            </div>
            <div class="flex flex-1 items-center justify-center py-10">
                <div class="w-full max-w-sm">
                    <h1 v-if="title" class="font-display text-3xl font-medium tracking-tight text-stone-900 dark:text-stone-50">{{ title }}</h1>
                    <p v-if="description" class="mt-2 text-sm text-stone-600 dark:text-stone-400">{{ description }}</p>
                    <div :class="title || description ? 'mt-8' : ''">
                        <slot />
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
