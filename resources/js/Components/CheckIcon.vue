<script setup>
defineProps({
    checked: {
        type: [Array, Boolean],
        default: false,
    },
});
</script>

<template>
    <span :class="checked ? 'text-green-500' : 'text-red-500'">
        <font-awesome-icon
            v-if="checked"
            icon="fa-solid fa-circle-check"
            size="lg"
        />
        <font-awesome-icon v-else icon="fa-solid fa-times-circle" size="lg" />
    </span>
</template>
