<script setup>
import { ArrowDownLeftIcon, ArrowUpRightIcon } from "@heroicons/vue/16/solid";

defineProps({
    direction: {
        type: String,
        required: true,
        validator: (value) => ["receive", "send"].includes(value),
    },
    /** Hide the text on very compact layouts; it stays available to screen readers. */
    iconOnly: { type: Boolean, default: false },
});
</script>

<template>
    <span
        :class="
            direction === 'receive'
                ? 'bg-pine-50 text-pine-800 ring-pine-200 dark:bg-pine-950 dark:text-pine-200 dark:ring-pine-800'
                : 'bg-clay-50 text-clay-800 ring-clay-200 dark:bg-clay-950 dark:text-clay-200 dark:ring-clay-800'
        "
        class="inline-flex items-center gap-1 rounded px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset"
    >
        <component
            :is="direction === 'receive' ? ArrowDownLeftIcon : ArrowUpRightIcon"
            aria-hidden="true"
            class="h-3.5 w-3.5"
        />
        <span :class="iconOnly ? 'sr-only' : ''">{{ direction === "receive" ? "Recebemos" : "Enviamos" }}</span>
    </span>
</template>
