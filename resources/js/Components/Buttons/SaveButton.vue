<script setup>
defineProps({
    type: {
        type: String,
        default: "submit",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <button
        :class="
            disabled
                ? 'cursor-not-allowed bg-stone-300 dark:bg-slate-700'
                : 'bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600'
        "
        :type="type"
        class="w-full rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 sm:w-auto"
    >
        <slot />
    </button>
</template>
