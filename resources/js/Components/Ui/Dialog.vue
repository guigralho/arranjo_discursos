<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from "@headlessui/vue";
import { XMarkIcon } from "@heroicons/vue/20/solid";

defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, required: true },
    maxWidth: { type: String, default: "max-w-md" },
});

const emit = defineEmits(["close"]);
</script>

<template>
    <TransitionRoot :show="show" appear as="template">
        <Dialog as="div" class="relative z-[70]" @close="emit('close')">
            <TransitionChild
                as="template"
                enter="duration-150 ease-out"
                enter-from="opacity-0"
                leave="duration-100 ease-in"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-stone-950/40 dark:bg-black/60" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-0 sm:items-center sm:p-6">
                    <TransitionChild
                        as="template"
                        enter="duration-150 ease-out"
                        enter-from="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-[0.98]"
                        leave="duration-100 ease-in"
                        leave-to="opacity-0"
                    >
                        <DialogPanel
                            :class="maxWidth"
                            class="pb-safe relative w-full rounded-t-xl border border-stone-200 bg-white p-5 shadow-float dark:border-stone-800 dark:bg-stone-900 sm:rounded-lg sm:p-6"
                        >
                            <button
                                aria-label="Fechar"
                                class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded text-stone-400 hover:bg-stone-100 hover:text-stone-700 dark:hover:bg-stone-800 dark:hover:text-stone-200"
                                type="button"
                                @click="emit('close')"
                            >
                                <XMarkIcon aria-hidden="true" class="h-5 w-5" />
                            </button>
                            <DialogTitle
                                as="h2"
                                class="pr-8 font-display text-xl font-medium text-stone-900 dark:text-stone-50"
                            >
                                {{ title }}
                            </DialogTitle>
                            <div class="mt-3 text-sm text-stone-700 dark:text-stone-300">
                                <slot />
                            </div>
                            <div
                                v-if="$slots.actions"
                                class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end"
                            >
                                <slot name="actions" />
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
