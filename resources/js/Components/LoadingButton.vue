<template>
    <button
        :class="[
            block ? 'flex-1' : 'flex-1 sm:flex-none',
            baseClasses,
            colorClasses[color],
            { 'cursor-not-allowed opacity-50': loading },
        ]"
        :disabled="loading || disabled"
        :type="type"
        @click="$emit('click', $event)"
    >
        <div class="flex items-center justify-center gap-2">
            <svg
                v-if="loading"
                class="h-4 w-4 animate-spin"
                fill="none"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    fill="currentColor"
                ></path>
            </svg>
            <slot>{{ loading ? loadingText : text }}</slot>
        </div>
    </button>
</template>

<script setup>
defineEmits(["click"]);

defineProps({
    loading: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    color: {
        type: String,
        default: "gray",
        validator: (value) =>
            [
                "gray",
                "green",
                "blue",
                "red",
                "yellow",
                "indigo",
                "purple",
                "pink",
                "teal",
            ].includes(value),
    },
    variant: {
        type: String,
        default: "primary",
        validator: (value) => ["primary", "secondary"].includes(value),
    },
    type: {
        type: String,
        default: "button",
    },
    text: {
        type: String,
        default: "",
    },
    loadingText: {
        type: String,
        default: "Carregando...",
    },
    block: {
        type: String,
        default: null,
    },
});

const baseClasses =
    "rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-900 sm:w-fit ";

const colorClasses = {
    gray: "text-white bg-stone-600 hover:bg-stone-700 focus:ring-stone-500",
    green: "text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500",
    blue: "text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500",
    red: "text-white bg-rose-600 hover:bg-rose-700 focus:ring-rose-500",
    yellow: "text-stone-900 bg-amber-400 hover:bg-amber-500 focus:ring-amber-400",
    indigo: "text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500",
    purple: "text-white bg-purple-600 hover:bg-purple-700 focus:ring-purple-500",
    pink: "text-white bg-pink-600 hover:bg-pink-700 focus:ring-pink-500",
    teal: "text-white bg-teal-600 hover:bg-teal-700 focus:ring-teal-500 dark:bg-teal-500 dark:hover:bg-teal-600",
};
</script>
