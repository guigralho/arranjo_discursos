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
    <input
        ref="input"
        :value="modelValue"
        class="w-full rounded-xl border-stone-300 bg-white text-sm shadow-sm transition-colors placeholder:text-stone-400 focus:border-teal-500 focus:ring-teal-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-teal-500 dark:focus:ring-teal-500"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
