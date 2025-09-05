<script setup>
import Modal from "./Modal.vue";
import LoadingButton from "./LoadingButton.vue";
import PrimaryButton from "./Buttons/PrimaryButton.vue";
import { Inertia } from "@inertiajs/inertia";
import { useLoading } from "@/composables/useLoading";

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
            Inertia.delete(props.deleteUrl, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                onSuccess: () => {
                    emit("close");
                    resolve();
                },
                onError: () => {
                    reject();
                }
            });
        });
    }, 'delete');
};
</script>
<template>
    <Modal :show="show" max-width="md" @close="emit('close')">
        <p class="text-xl font-bold dark:text-gray-400">Atenção!</p>
        <p class="mt-6 dark:text-gray-400">
            {{ subtitle }}
        </p>
        <p class="text-sm dark:text-gray-400">
            <i>{{ toDelete }}</i>
        </p>
        <div class="mt-6 flex flex-row justify-evenly space-x-2">
            <LoadingButton
                :loading="isLoadingKey('delete')"
                class="flex-1 uppercase bg-red-600 hover:bg-red-700"
                text="Sim"
                loading-text="Excluindo..."
                @click="deleteRow"
            />
            <PrimaryButton 
                class="flex-1" 
                :disabled="isLoadingKey('delete')"
                @click="emit('close')"
            >
                <p>Não</p>
            </PrimaryButton>
        </div>
    </Modal>
</template>
