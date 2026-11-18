<script setup>
import { computed } from "vue";
import { ArrowsUpDownIcon } from "@heroicons/vue/16/solid";

/**
 * Compact sort control for narrow screens. Shares the page's filter state
 * so search, toggles and pagination are preserved.
 */
const props = defineProps({
    /** [{ field: 'name', label: 'Nome' }] */
    options: { type: Array, required: true },
    orderField: { type: String, default: null },
    orderDir: { type: String, default: null },
    id: { type: String, default: "sort-menu" },
});

const emit = defineEmits(["change"]);

const choices = computed(() =>
    props.options.flatMap((option) => [
        { value: `${option.field}|asc`, label: `${option.label} ${option.ascLabel ?? "(A–Z)"}` },
        { value: `${option.field}|desc`, label: `${option.label} ${option.descLabel ?? "(Z–A)"}` },
    ])
);

const current = computed(() =>
    props.orderField && props.orderDir ? `${props.orderField}|${props.orderDir}` : ""
);

const onChange = (event) => {
    const [field, dir] = event.target.value ? event.target.value.split("|") : [null, null];
    emit("change", { orderField: field, orderDir: dir });
};
</script>

<template>
    <div class="relative">
        <label :for="id" class="sr-only">Ordenar por</label>
        <ArrowsUpDownIcon
            aria-hidden="true"
            class="pointer-events-none absolute left-3 top-3 h-4 w-4 text-stone-400"
        />
        <select :id="id" :value="current" class="field h-10 pl-9 pr-9" @change="onChange">
            <option value="">Ordem padrão</option>
            <option v-for="choice in choices" :key="choice.value" :value="choice.value">
                {{ choice.label }}
            </option>
        </select>
    </div>
</template>
