<script setup>
import { MagnifyingGlassIcon, XMarkIcon } from "@heroicons/vue/16/solid";

defineProps({
    modelValue: { type: String, default: "" },
    label: { type: String, default: "Buscar" },
    placeholder: { type: String, default: "Buscar..." },
    id: { type: String, default: "search" },
});

const emit = defineEmits(["update:modelValue"]);
</script>

<template>
    <div class="relative min-w-0 flex-1">
        <label :for="id" class="sr-only">{{ label }}</label>
        <MagnifyingGlassIcon
            aria-hidden="true"
            class="pointer-events-none absolute left-3 top-3 h-4 w-4 text-stone-400"
        />
        <input
            :id="id"
            :placeholder="placeholder"
            :value="modelValue"
            autocomplete="off"
            class="field h-10 pl-9 pr-9"
            type="search"
            @input="emit('update:modelValue', $event.target.value)"
        />
        <button
            v-if="modelValue"
            aria-label="Limpar busca"
            class="absolute right-1 top-1 flex h-8 w-8 items-center justify-center rounded text-stone-400 hover:text-stone-700 dark:hover:text-stone-200"
            type="button"
            @click="emit('update:modelValue', '')"
        >
            <XMarkIcon aria-hidden="true" class="h-4 w-4" />
        </button>
    </div>
</template>
