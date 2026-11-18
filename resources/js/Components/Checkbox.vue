<script setup>
import { computed } from "vue";

const emit = defineEmits(["update:checked", "update:modelValue"]);

const props = defineProps({
    /** Supports both `v-model:checked` (legacy) and `v-model`. */
    checked: {
        type: [Array, Boolean],
        default: undefined,
    },
    modelValue: {
        type: [Array, Boolean],
        default: undefined,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.modelValue !== undefined ? props.modelValue : props.checked ?? false;
    },

    set(val) {
        emit("update:checked", val);
        emit("update:modelValue", val);
    },
});
</script>

<template>
    <input
        v-model="proxyChecked"
        :value="value"
        class="h-4 w-4 rounded border-stone-400 bg-white text-pine-700 focus:ring-pine-600 focus:ring-offset-white dark:border-stone-600 dark:bg-stone-900 dark:text-pine-500 dark:focus:ring-pine-400 dark:focus:ring-offset-stone-950"
        type="checkbox"
    />
</template>
