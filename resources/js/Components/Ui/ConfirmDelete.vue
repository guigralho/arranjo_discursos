<script setup>
import { computed, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import Dialog from "@/Components/Ui/Dialog.vue";
import Button from "@/Components/Ui/Button.vue";

/**
 * Destructive confirmation. Sends DELETE to `deleteUrl` and closes on success.
 * When `confirmWord` is set the user must type it to enable the action.
 */
const props = defineProps({
    show: { type: Boolean, default: false },
    deleteUrl: { type: String, default: null },
    title: { type: String, default: "Excluir registro?" },
    subtitle: { type: String, default: "Esta ação não pode ser desfeita." },
    toDelete: { type: String, default: null },
    confirmLabel: { type: String, default: "Excluir" },
    confirmWord: { type: String, default: null },
});

const emit = defineEmits(["close"]);

const isDeleting = ref(false);
const typedWord = ref("");
const failed = ref(false);

watch(
    () => props.show,
    (isOpen) => {
        if (isOpen) {
            typedWord.value = "";
            failed.value = false;
        }
    }
);

const canConfirm = computed(
    () => !props.confirmWord || typedWord.value.trim().toUpperCase() === props.confirmWord
);

const confirmDeletion = () => {
    if (!canConfirm.value || !props.deleteUrl) {
        return;
    }
    isDeleting.value = true;
    failed.value = false;
    router.delete(props.deleteUrl, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: () => emit("close"),
        onError: () => (failed.value = true),
        onFinish: () => (isDeleting.value = false),
    });
};
</script>

<template>
    <Dialog :show="show" :title="title" @close="!isDeleting && emit('close')">
        <p>{{ subtitle }}</p>
        <p
            v-if="toDelete"
            class="mt-3 rounded-md bg-stone-100 px-3 py-2 font-medium text-stone-900 dark:bg-stone-800 dark:text-stone-100"
        >
            {{ toDelete }}
        </p>
        <div v-if="confirmWord" class="mt-4">
            <label class="mb-1.5 block text-sm font-medium" for="confirm-word">
                Digite <strong class="font-semibold">{{ confirmWord }}</strong> para confirmar
            </label>
            <input
                id="confirm-word"
                v-model="typedWord"
                autocomplete="off"
                class="field h-10"
                type="text"
                @keydown.enter.prevent="confirmDeletion"
            />
        </div>
        <p v-if="failed" class="mt-3 text-sm font-medium text-red-700 dark:text-red-400" role="alert">
            Não foi possível excluir. Tente novamente.
        </p>
        <template #actions>
            <Button :disabled="isDeleting" variant="secondary" @click="emit('close')">Cancelar</Button>
            <Button
                :disabled="!canConfirm"
                :loading="isDeleting"
                loading-text="Excluindo…"
                variant="danger"
                @click="confirmDeletion"
            >
                {{ confirmLabel }}
            </Button>
        </template>
    </Dialog>
</template>
