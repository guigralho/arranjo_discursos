<template>
    <button
        :class="[
            baseClasses,
            variant === 'primary' ? primaryClasses : secondaryClasses,
            { 'opacity-50 cursor-not-allowed': loading }
        ]"
        :disabled="loading || disabled"
        :type="type"
        @click="$emit('click', $event)"
    >
        <svg
            v-if="loading"
            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
    </button>
</template>

<script setup>
defineEmits(['click'])

defineProps({
    loading: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary'].includes(value)
    },
    type: {
        type: String,
        default: 'button'
    },
    text: {
        type: String,
        default: ''
    },
    loadingText: {
        type: String,
        default: 'Carregando...'
    }
})

const baseClasses = 'w-full flex rounded-md border border-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-fit sm:grow-0'
const primaryClasses = 'text-white bg-green-700 hover:bg-green-900 focus:bg-green-700 active:bg-green-900'
const secondaryClasses = 'text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
</script>
