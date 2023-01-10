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
                ? 'cursor-not-allowed bg-gray-600'
                : 'bg-gray-200 hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900'
        "
        :type="type"
        class="inline-flex items-center rounded-md border border-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-800 transition duration-150 ease-in-out hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-400 dark:hover:text-gray-800"
    >
        <slot />
    </button>
</template>
