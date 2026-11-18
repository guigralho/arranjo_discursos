<script setup>
import { computed } from "vue";

const props = defineProps({
    phone: { type: String, default: null },
});

const href = computed(() => {
    const digits = String(props.phone ?? "").replace(/\D/g, "");
    if (!digits) {
        return null;
    }
    return `tel:${digits.length <= 11 ? `+55${digits}` : `+${digits}`}`;
});
</script>

<template>
    <a
        v-if="href"
        :href="href"
        class="tabular-nums text-stone-800 underline decoration-stone-300 underline-offset-2 hover:decoration-stone-600 dark:text-stone-200 dark:decoration-stone-600 dark:hover:decoration-stone-300"
        >{{ phone }}</a
    >
    <span v-else class="text-stone-400 dark:text-stone-500">—</span>
</template>
