<script setup>
import Modal from "./Modal.vue";
import DeleteButton from "./Buttons/DeleteLink.vue";
import PrimaryButton from "./Buttons/PrimaryButton.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";

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

let disabledDeleteButton = ref(false);

const deleteRow = () => {
    Inertia.delete(props.deleteUrl, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: () => {
            emit("close");
            disabledDeleteButton.value = false;
        },
        onBefore: () => {
            disabledDeleteButton.value = true;
        },
    });
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
            <DeleteButton
                :disabled="disabledDeleteButton"
                class="flex-1"
                method="delete"
                @click="deleteRow"
            >
                <p v-if="!disabledDeleteButton">Sim</p>
                <p v-if="disabledDeleteButton">Aguarde...</p>
            </DeleteButton>
            <PrimaryButton class="flex-1" @click="emit('close')">
                <p>Não</p>
            </PrimaryButton>
        </div>
    </Modal>
</template>
