<script setup>
import Modal from "./Modal.vue";
import LoadingButton from "./LoadingButton.vue";
import { router } from "@inertiajs/vue3";
import { useLoading } from "@/composables/useLoading";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    deleteUrl: {
        type: String,
        default: null,
    },
    subtitle: {
        type: String,
        default: "Tem certeza que deseja excluir esse registro?",
    },
    toDelete: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["close"]);

const { isLoadingKey, withLoading } = useLoading();

const deleteRow = async () => {
    await withLoading(async () => {
        return new Promise((resolve, reject) => {
            router.delete(props.deleteUrl, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                onSuccess: () => {
                    emit("close");
                    resolve();
                },
                onError: () => {
                    reject();
                },
            });
        });
    }, "delete");
};
</script>
<template>
    <Modal :show="show" max-width="sm" @close="emit('close')">
        <div class="text-center sm:text-left">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 dark:bg-rose-500/10 sm:mx-0">
                <ExclamationTriangleIcon class="h-6 w-6 text-rose-600 dark:text-rose-400" />
            </div>
            <h3 class="text-lg font-semibold text-stone-900 dark:text-white">
                Excluir registro
            </h3>
            <p class="mt-2 text-sm text-stone-600 dark:text-slate-400">
                {{ subtitle }}
            </p>
            <p v-if="toDelete" class="mt-1 text-sm font-medium text-stone-900 dark:text-white">
                {{ toDelete }}
            </p>
        </div>
        <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button
                :disabled="isLoadingKey('delete')"
                class="w-full rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-700 transition-colors hover:bg-stone-50 disabled:opacity-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 sm:w-auto"
                @click="emit('close')"
            >
                Cancelar
            </button>
            <LoadingButton
                :loading="isLoadingKey('delete')"
                color="red"
                loading-text="Excluindo..."
                text="Excluir"
                @click="deleteRow"
            />
        </div>
    </Modal>
</template>
