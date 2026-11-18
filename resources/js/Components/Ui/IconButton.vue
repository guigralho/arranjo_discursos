<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    /** Accessible name; also shown as a native tooltip. */
    label: { type: String, required: true },
    icon: { type: [Object, Function], required: true },
    href: { type: String, default: null },
    external: { type: Boolean, default: false },
    tone: {
        type: String,
        default: "neutral",
        validator: (value) => ["neutral", "danger"].includes(value),
    },
    type: { type: String, default: "button" },
    disabled: { type: Boolean, default: false },
});

const toneClasses = {
    neutral:
        "text-stone-500 hover:bg-stone-100 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-stone-800 dark:hover:text-stone-100",
    danger: "text-stone-500 hover:bg-red-50 hover:text-red-700 dark:text-stone-400 dark:hover:bg-red-950/50 dark:hover:text-red-400",
};

const component = computed(() => {
    if (!props.href) {
        return "button";
    }
    return props.external ? "a" : Link;
});
</script>

<template>
    <component
        :is="component"
        :aria-label="label"
        :class="toneClasses[tone]"
        :disabled="!href ? disabled : undefined"
        :href="href ?? undefined"
        :title="label"
        :type="!href ? type : undefined"
        class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md transition-colors duration-150 disabled:pointer-events-none disabled:opacity-50"
    >
        <component :is="icon" aria-hidden="true" class="h-[18px] w-[18px]" />
    </component>
</template>
