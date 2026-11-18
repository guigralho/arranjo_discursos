<script setup>
import { computed, onUnmounted, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { CheckCircleIcon, XMarkIcon } from "@heroicons/vue/20/solid";

const page = usePage();
const visibleMessage = ref(null);
let hideTimer = null;

const flashMessage = computed(() => page.props.flash?.message);

const dismiss = () => {
    clearTimeout(hideTimer);
    visibleMessage.value = null;
};

watch(
    flashMessage,
    (message) => {
        if (!message?.value) {
            return;
        }
        visibleMessage.value = message;
        clearTimeout(hideTimer);
        hideTimer = setTimeout(dismiss, 4000);
    },
    { immediate: true }
);

onUnmounted(() => clearTimeout(hideTimer));
</script>

<template>
    <div
        aria-live="polite"
        class="pointer-events-none fixed inset-x-0 bottom-20 z-[60] flex justify-center px-4 lg:bottom-6 lg:justify-end lg:px-8"
        role="status"
    >
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-2 opacity-0"
            leave-active-class="transition duration-150 ease-in"
            leave-to-class="opacity-0"
        >
            <div
                v-if="visibleMessage"
                :key="visibleMessage.uuid"
                class="pointer-events-auto flex w-full max-w-sm items-center gap-3 rounded-lg bg-stone-900 py-2.5 pl-3.5 pr-2 text-sm text-stone-50 shadow-float dark:bg-stone-100 dark:text-stone-900"
            >
                <CheckCircleIcon aria-hidden="true" class="h-5 w-5 shrink-0 text-pine-300 dark:text-pine-700" />
                <p class="flex-1">{{ visibleMessage.value }}</p>
                <button
                    aria-label="Fechar aviso"
                    class="flex h-8 w-8 items-center justify-center rounded text-stone-400 hover:text-white dark:text-stone-500 dark:hover:text-stone-900"
                    type="button"
                    @click="dismiss"
                >
                    <XMarkIcon aria-hidden="true" class="h-4 w-4" />
                </button>
            </div>
        </transition>
    </div>
</template>
