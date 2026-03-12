<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        default: false,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="rounded border-stone-300 text-teal-600 shadow-sm focus:ring-teal-500 dark:border-slate-600 dark:bg-slate-800 dark:checked:bg-teal-500 dark:focus:ring-teal-500 dark:focus:ring-offset-slate-900"
    />
</template>
