<script setup>
import { Link } from "@inertiajs/vue3";
import { BookOpenIcon } from "@heroicons/vue/24/outline";
</script>

<template>
    <div class="flex min-h-screen flex-col items-center justify-center bg-stone-50 px-4 dark:bg-slate-950">
        <!-- Decorative background -->
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -right-40 -top-40 h-96 w-96 rounded-full bg-teal-100/50 blur-3xl dark:bg-teal-900/20" />
            <div class="absolute -bottom-40 -left-40 h-96 w-96 rounded-full bg-amber-100/40 blur-3xl dark:bg-amber-900/10" />
        </div>

        <div class="relative w-full max-w-sm">
            <!-- Logo -->
            <div class="mb-8 flex flex-col items-center gap-3">
                <Link href="/" class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-teal-600 text-white shadow-lg shadow-teal-600/25">
                        <BookOpenIcon class="h-6 w-6" />
                    </div>
                </Link>
                <h1 class="text-xl font-bold tracking-tight text-stone-900 dark:text-white">
                    {{ $page.props.app.name }}
                </h1>
            </div>

            <!-- Card -->
            <div class="rounded-2xl bg-white p-8 shadow-xl shadow-stone-200/50 ring-1 ring-stone-100 dark:bg-slate-900 dark:shadow-none dark:ring-slate-800">
                <slot />
            </div>
        </div>
    </div>
</template>