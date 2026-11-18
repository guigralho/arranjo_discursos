<script setup>
defineProps({
    title: { type: String, required: true },
    description: { type: String, default: null },
    icon: { type: [Object, Function], default: null },
    compact: { type: Boolean, default: false },
});
</script>

<template>
    <div
        :class="compact ? 'px-4 py-8' : 'px-6 py-14'"
        class="flex flex-col items-center text-center"
    >
        <div
            v-if="icon"
            class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-stone-100 text-stone-500 dark:bg-stone-800 dark:text-stone-400"
        >
            <component :is="icon" aria-hidden="true" class="h-5 w-5" />
        </div>
        <p class="font-medium text-stone-900 dark:text-stone-100">{{ title }}</p>
        <p
            v-if="description"
            class="mt-1 max-w-sm text-sm text-stone-600 dark:text-stone-400"
        >
            {{ description }}
        </p>
        <div v-if="$slots.default" class="mt-4 flex flex-wrap justify-center gap-2">
            <slot />
        </div>
    </div>
</template>
