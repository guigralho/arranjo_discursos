<script setup>
defineProps(['value']);
</script>

<template>
    <label class="block text-sm font-medium text-stone-700 dark:text-slate-300">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
