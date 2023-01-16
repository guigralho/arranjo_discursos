<script setup>
import { computed, ref, watch } from "vue";
import { usePage } from "@inertiajs/inertia-vue3";

let showNot = ref("");

let notification = computed(() => {
    return usePage().props.value.flash.message;
});

watch(notification, (val) => {
    showNot.value = val;

    setTimeout(() => {
        showNot.value = null;
    }, 3000);
});
</script>

<template>
    <Transition
        class="transition duration-300 ease-in-out"
        enter-from-class="opacity-0"
        leave-to-class="opacity-0"
    >
        <div v-if="showNot" class="mx-auto max-w-7xl py-3 sm:px-3 lg:px-8">
            <div
                class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700 dark:bg-gray-800 dark:text-green-400"
                role="alert"
                @click="showNot = null"
            >
                <font-awesome-icon icon="fa-solid fa-circle-check" />
                {{ showNot.value }}
            </div>
        </div>
    </Transition>
</template>
