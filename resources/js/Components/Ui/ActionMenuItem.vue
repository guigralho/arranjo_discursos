<script setup>
import { computed } from "vue";
import { MenuItem } from "@headlessui/vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: { type: String, default: null },
    external: { type: Boolean, default: false },
    icon: { type: [Object, Function], default: null },
    tone: { type: String, default: "neutral" },
});

const emit = defineEmits(["select"]);

const component = computed(() => {
    if (!props.href) {
        return "button";
    }
    return props.external ? "a" : Link;
});
</script>

<template>
    <MenuItem v-slot="{ active }">
        <component
            :is="component"
            :class="[
                active ? 'bg-stone-100 dark:bg-stone-800' : '',
                tone === 'danger' ? 'text-red-700 dark:text-red-400' : 'text-stone-800 dark:text-stone-200',
            ]"
            :href="href ?? undefined"
            :type="href ? undefined : 'button'"
            class="flex w-full items-center gap-2.5 rounded-md px-2.5 py-2 text-left text-sm"
            @click="emit('select')"
        >
            <component :is="icon" v-if="icon" aria-hidden="true" class="h-4 w-4 shrink-0 opacity-70" />
            <slot />
        </component>
    </MenuItem>
</template>
