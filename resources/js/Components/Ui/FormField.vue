<script setup>
import { computed } from "vue";

const props = defineProps({
    label: { type: String, required: true },
    id: { type: String, required: true },
    error: { type: String, default: null },
    hint: { type: String, default: null },
    required: { type: Boolean, default: false },
    /** Visually hide the label while keeping it for assistive tech. */
    hideLabel: { type: Boolean, default: false },
});

const hintId = computed(() => `${props.id}-hint`);
const errorId = computed(() => `${props.id}-error`);

const describedBy = computed(
    () =>
        [props.hint ? hintId.value : null, props.error ? errorId.value : null]
            .filter(Boolean)
            .join(" ") || undefined
);

/** Attributes to bind on the inner control: v-bind="field". */
const field = computed(() => ({
    id: props.id,
    "aria-describedby": describedBy.value,
    "aria-invalid": props.error ? "true" : undefined,
    "aria-required": props.required ? "true" : undefined,
}));
</script>

<template>
    <div class="min-w-0">
        <label
            :class="hideLabel ? 'sr-only' : 'mb-1.5 flex items-baseline gap-1'"
            :for="id"
            class="text-sm font-medium text-stone-800 dark:text-stone-200"
        >
            {{ label }}
            <span
                v-if="required"
                aria-hidden="true"
                class="text-clay-600 dark:text-clay-400"
                title="Obrigatório"
                >*</span
            >
        </label>
        <slot :field="field" />
        <p
            v-if="hint && !error"
            :id="hintId"
            class="mt-1.5 text-xs text-stone-500 dark:text-stone-400"
        >
            {{ hint }}
        </p>
        <p
            v-if="error"
            :id="errorId"
            class="mt-1.5 text-xs font-medium text-red-700 dark:text-red-400"
        >
            {{ error }}
        </p>
    </div>
</template>
