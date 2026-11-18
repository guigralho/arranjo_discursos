<script setup>
import { Switch, SwitchGroup, SwitchLabel } from "@headlessui/vue";

defineProps({
    modelValue: { type: Boolean, default: false },
    label: { type: String, required: true },
});

const emit = defineEmits(["update:modelValue"]);
</script>

<template>
    <SwitchGroup as="div" class="inline-flex items-center gap-2.5">
        <Switch
            :class="modelValue ? 'bg-pine-700 dark:bg-pine-500' : 'bg-stone-300 dark:bg-stone-700'"
            :model-value="modelValue"
            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full transition-colors duration-150"
            @update:model-value="emit('update:modelValue', $event)"
        >
            <span
                :class="modelValue ? 'translate-x-4' : 'translate-x-0.5'"
                aria-hidden="true"
                class="pointer-events-none mt-0.5 inline-block h-4 w-4 rounded-full bg-white shadow transition-transform duration-150"
            />
        </Switch>
        <SwitchLabel class="cursor-pointer text-sm text-stone-700 dark:text-stone-300">{{ label }}</SwitchLabel>
    </SwitchGroup>
</template>
