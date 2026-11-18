<script setup>
import { CheckIcon } from "@heroicons/vue/16/solid";

defineProps({
    modelValue: { type: Boolean, default: false },
});

const emit = defineEmits(["update:modelValue"]);
</script>

<template>
    <button
        :aria-pressed="modelValue ? 'true' : 'false'"
        :class="
            modelValue
                ? 'border-pine-700 bg-pine-50 text-pine-900 dark:border-pine-400 dark:bg-pine-950 dark:text-pine-100'
                : 'border-stone-300 bg-white text-stone-700 hover:border-stone-400 dark:border-stone-700 dark:bg-stone-900 dark:text-stone-300 dark:hover:border-stone-500'
        "
        class="inline-flex h-10 items-center gap-1.5 whitespace-nowrap rounded-md border px-3 text-sm font-medium transition-colors"
        type="button"
        @click="emit('update:modelValue', !modelValue)"
    >
        <CheckIcon v-if="modelValue" aria-hidden="true" class="h-4 w-4" />
        <slot />
    </button>
</template>
