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
        <div class="flex items-center">
            <svg
                v-if="loading"
                class="-ml-1 mr-2 h-4 w-4 animate-spin text-white"
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
    "justify-items-center gap-1 rounded-md border border-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 sm:w-fit ";

const colorClasses = {
    gray: "text-white bg-gray-600 hover:bg-gray-700 focus:bg-gray-600 active:bg-gray-700 focus:ring-gray-500",
    green: "text-white bg-green-700 hover:bg-green-900 focus:bg-green-700 active:bg-green-900 focus:ring-green-500",
    blue: "text-white bg-blue-600 hover:bg-blue-700 focus:bg-blue-600 active:bg-blue-700 focus:ring-blue-500",
    red: "text-white bg-red-600 hover:bg-red-800 focus:bg-red-600 active:bg-red-700 focus:ring-red-500",
    yellow: "text-gray-900 bg-yellow-500 hover:bg-yellow-600 focus:bg-yellow-500 active:bg-yellow-600 focus:ring-yellow-400",
    indigo: "text-white bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-600 active:bg-indigo-700 focus:ring-indigo-500",
    purple: "text-white bg-purple-600 hover:bg-purple-700 focus:bg-purple-600 active:bg-purple-700 focus:ring-purple-500",
    pink: "text-white bg-pink-600 hover:bg-pink-700 focus:bg-pink-600 active:bg-pink-700 focus:ring-pink-500",
};
</script>
