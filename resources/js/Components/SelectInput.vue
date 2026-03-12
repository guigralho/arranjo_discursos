<script setup>
import { onMounted, ref } from "vue";

defineProps(["modelValue"]);

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select
        ref="input"
        :value="modelValue"
        class="rounded-xl border-stone-300 bg-white text-sm shadow-sm transition-colors focus:border-teal-500 focus:ring-teal-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:focus:border-teal-500 dark:focus:ring-teal-500"
        @change="$emit('update:modelValue', $event.target.value)"
    >
        <slot />
    </select>
</template>
