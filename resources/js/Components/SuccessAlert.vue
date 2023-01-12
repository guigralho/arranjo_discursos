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
                class="bg-green-50 px-5 py-3 dark:bg-green-700 sm:rounded-lg"
                @click="showNot = null"
            >
                <p class="text-bold text-emerald-800 dark:text-white">
                    <font-awesome-icon icon="fa-solid fa-circle-check" />
                    {{ showNot.value }}
                </p>
            </div>
        </div>
    </Transition>
</template>
