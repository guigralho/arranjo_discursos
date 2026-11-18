<script setup>
defineProps({
    title: { type: String, required: true },
    description: { type: String, default: null },
    id: { type: String, required: true },
});
</script>

<template>
    <section :aria-labelledby="id" class="grid gap-x-10 gap-y-5 py-8 first:pt-0 md:grid-cols-[14rem_minmax(0,1fr)]">
        <div>
            <h2 :id="id" class="font-medium text-stone-900 dark:text-stone-100">{{ title }}</h2>
            <p v-if="description" class="mt-1 text-sm text-stone-600 dark:text-stone-400">{{ description }}</p>
        </div>
        <div class="max-w-xl">
            <slot />
        </div>
    </section>
</template>
