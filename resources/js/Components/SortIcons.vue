<script setup>
defineProps({
    orderDir: String,
    updateIcon: Boolean,
});
</script>

<template>
    <font-awesome-icon
        :icon="`fa-solid fa-sort${
            orderDir !== undefined && updateIcon ? '-' + orderDir : ''
        }`"
    />
</template>
