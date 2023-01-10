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
                : 'bg-green-700 hover:bg-green-900 focus:bg-green-700 active:bg-green-900'
        "
        :type="type"
        class="inline-flex items-center rounded-md border border-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
    >
        <slot />
    </button>
</template>
