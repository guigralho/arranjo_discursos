<script setup>
import { Link } from "@inertiajs/vue3";
import { ArrowLeftIcon } from "@heroicons/vue/16/solid";

defineProps({
    title: { type: String, required: true },
    eyebrow: { type: String, default: null },
    /** Optional back destination shown above the title. */
    backHref: { type: String, default: null },
    backLabel: { type: String, default: "Voltar" },
});
</script>

<template>
    <header class="mb-6 sm:mb-8">
        <Link
            v-if="backHref"
            :href="backHref"
            class="-ml-1 mb-3 inline-flex items-center gap-1 rounded px-1 text-sm text-stone-500 transition-colors hover:text-stone-900 dark:text-stone-400 dark:hover:text-stone-100"
        >
            <ArrowLeftIcon aria-hidden="true" class="h-4 w-4" />
            {{ backLabel }}
        </Link>
        <div class="flex flex-wrap items-end justify-between gap-x-6 gap-y-4">
            <div class="min-w-0">
                <p v-if="eyebrow" class="eyebrow mb-1.5">{{ eyebrow }}</p>
                <h1
                    class="break-words font-display text-[1.75rem] font-medium leading-tight tracking-tight text-stone-900 dark:text-stone-50 sm:text-4xl"
                >
                    {{ title }}
                </h1>
                <div
                    v-if="$slots.meta"
                    class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-stone-600 dark:text-stone-400"
                >
                    <slot name="meta" />
                </div>
            </div>
            <div v-if="$slots.actions" class="flex flex-wrap items-center gap-2">
                <slot name="actions" />
            </div>
        </div>
    </header>
</template>
