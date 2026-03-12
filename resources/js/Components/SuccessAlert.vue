<script setup>
import { computed, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { CheckCircleIcon, XMarkIcon } from "@heroicons/vue/24/outline";

let showNot = ref("");

let notification = computed(() => {
    return usePage().props.flash.message;
});

watch(notification, (val) => {
    showNot.value = val;

    setTimeout(() => {
        showNot.value = null;
    }, 4000);
});
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-2 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-2 opacity-0"
    >
        <div
            v-if="showNot"
            class="fixed bottom-24 right-4 z-50 md:bottom-6 md:right-6"
        >
            <div
                class="flex items-center gap-3 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-medium text-white shadow-lg shadow-emerald-600/25"
                role="alert"
                @click="showNot = null"
            >
                <CheckCircleIcon class="h-5 w-5 flex-shrink-0" />
                <span>{{ showNot.value }}</span>
                <button class="ml-2 rounded-lg p-0.5 hover:bg-emerald-500" @click.stop="showNot = null">
                    <XMarkIcon class="h-4 w-4" />
                </button>
            </div>
        </div>
    </Transition>
</template>
