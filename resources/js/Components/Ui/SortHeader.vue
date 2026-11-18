<script setup>
import { computed } from "vue";
import { ChevronDownIcon, ChevronUpDownIcon, ChevronUpIcon } from "@heroicons/vue/16/solid";

const props = defineProps({
    label: { type: String, required: true },
    field: { type: String, required: true },
    /** Current sort state from the page filters. */
    orderField: { type: String, default: null },
    orderDir: { type: String, default: null },
    align: { type: String, default: "left" },
});

const emit = defineEmits(["sort"]);

const isActive = computed(() => props.orderField === props.field && !!props.orderDir);

const ariaSort = computed(() => {
    if (!isActive.value) {
        return "none";
    }
    return props.orderDir === "asc" ? "ascending" : "descending";
});
</script>

<template>
    <th :aria-sort="ariaSort" :class="align === 'right' ? 'text-right' : 'text-left'" scope="col">
        <button
            :class="[
                isActive ? 'text-stone-900 dark:text-stone-100' : '',
                align === 'right' ? 'flex-row-reverse' : '',
            ]"
            class="group -mx-1.5 inline-flex items-center gap-1 rounded px-1.5 py-1 hover:text-stone-900 dark:hover:text-stone-100"
            type="button"
            @click="emit('sort', field)"
        >
            {{ label }}
            <ChevronUpIcon v-if="isActive && orderDir === 'asc'" aria-hidden="true" class="h-4 w-4" />
            <ChevronDownIcon
                v-else-if="isActive && orderDir === 'desc'"
                aria-hidden="true"
                class="h-4 w-4"
            />
            <ChevronUpDownIcon
                v-else
                aria-hidden="true"
                class="h-4 w-4 opacity-0 transition-opacity group-hover:opacity-60 group-focus-visible:opacity-60"
            />
        </button>
    </th>
</template>
