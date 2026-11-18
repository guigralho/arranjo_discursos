<script setup>
import { useDark, useToggle } from "@vueuse/core";
import { MoonIcon, SunIcon } from "@heroicons/vue/20/solid";

defineProps({
    withLabel: { type: Boolean, default: false },
});

const isDark = useDark();
const toggleDark = useToggle(isDark);
</script>

<template>
    <button
        :aria-label="withLabel ? undefined : isDark ? 'Usar tema claro' : 'Usar tema escuro'"
        :class="withLabel ? 'w-full gap-3 px-3 py-2.5 text-sm' : 'h-9 w-9 justify-center'"
        :title="withLabel ? undefined : isDark ? 'Usar tema claro' : 'Usar tema escuro'"
        class="flex items-center rounded-md text-stone-600 transition-colors hover:bg-stone-200/60 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-stone-800 dark:hover:text-stone-100"
        type="button"
        @click="toggleDark()"
    >
        <SunIcon v-if="isDark" aria-hidden="true" class="h-5 w-5" />
        <MoonIcon v-else aria-hidden="true" class="h-5 w-5" />
        <span v-if="withLabel">{{ isDark ? "Tema claro" : "Tema escuro" }}</span>
    </button>
</template>
