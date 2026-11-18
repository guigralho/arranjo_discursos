<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import Spinner from "@/Components/Ui/Spinner.vue";

const props = defineProps({
    variant: {
        type: String,
        default: "primary",
        validator: (value) =>
            ["primary", "secondary", "ghost", "danger", "danger-ghost"].includes(value),
    },
    size: {
        type: String,
        default: "md",
        validator: (value) => ["sm", "md"].includes(value),
    },
    type: { type: String, default: "button" },
    href: { type: String, default: null },
    method: { type: String, default: null },
    /** Render a plain anchor (downloads, external links) instead of an Inertia Link. */
    external: { type: Boolean, default: false },
    loading: { type: Boolean, default: false },
    loadingText: { type: String, default: null },
    disabled: { type: Boolean, default: false },
    block: { type: Boolean, default: false },
});

const variantClasses = {
    primary:
        "bg-pine-700 text-white shadow-sm hover:bg-pine-800 active:bg-pine-900 dark:bg-pine-500 dark:text-pine-950 dark:hover:bg-pine-400",
    secondary:
        "border border-stone-300 bg-white text-stone-800 shadow-sm hover:bg-stone-50 active:bg-stone-100 dark:border-stone-700 dark:bg-stone-900 dark:text-stone-100 dark:hover:bg-stone-800",
    ghost: "text-stone-700 hover:bg-stone-100 active:bg-stone-200 dark:text-stone-300 dark:hover:bg-stone-800",
    danger: "bg-red-700 text-white shadow-sm hover:bg-red-800 active:bg-red-900 dark:bg-red-600 dark:hover:bg-red-500",
    "danger-ghost":
        "text-red-700 hover:bg-red-50 active:bg-red-100 dark:text-red-400 dark:hover:bg-red-950/50",
};

const sizeClasses = {
    sm: "h-8 gap-1.5 px-3 text-[13px]",
    md: "h-10 gap-2 px-4 text-sm",
};

const isDisabled = computed(() => props.disabled || props.loading);

const classes = computed(() => [
    "inline-flex shrink-0 select-none items-center justify-center whitespace-nowrap rounded-md font-medium transition-colors duration-150",
    "disabled:pointer-events-none disabled:opacity-60 aria-disabled:pointer-events-none aria-disabled:opacity-60",
    variantClasses[props.variant],
    sizeClasses[props.size],
    props.block ? "w-full" : "",
]);

const component = computed(() => {
    if (!props.href) {
        return "button";
    }
    return props.external ? "a" : Link;
});

const componentAttributes = computed(() => {
    if (!props.href) {
        return { type: props.type, disabled: isDisabled.value };
    }
    if (props.external) {
        return { href: props.href, "aria-disabled": isDisabled.value || undefined };
    }
    return {
        href: props.href,
        method: props.method ?? undefined,
        as: props.method && props.method !== "get" ? "button" : undefined,
        "aria-disabled": isDisabled.value || undefined,
    };
});
</script>

<template>
    <component :is="component" :class="classes" v-bind="componentAttributes">
        <Spinner v-if="loading" class="h-4 w-4" />
        <slot v-else name="icon" />
        <span v-if="loading && loadingText">{{ loadingText }}</span>
        <slot v-else />
    </component>
</template>
