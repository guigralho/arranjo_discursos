<script setup>
import { nextTick, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Button from "@/Components/Ui/Button.vue";
import Dialog from "@/Components/Ui/Dialog.vue";
import FormField from "@/Components/Ui/FormField.vue";
import ProfileSection from "./ProfileSection.vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const closeDialog = () => {
    confirmingUserDeletion.value = false;
    form.reset();
    form.clearErrors();
};

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeDialog(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <ProfileSection
        id="profile-delete"
        description="Remove sua conta e o acesso ao sistema. Esta ação não pode ser desfeita."
        title="Excluir conta"
    >
        <Button variant="danger-ghost" class="-ml-4" @click="confirmUserDeletion">Excluir minha conta…</Button>

        <Dialog :show="confirmingUserDeletion" title="Excluir sua conta?" @close="closeDialog">
            <p>Todos os seus dados de acesso serão removidos permanentemente. Digite sua senha para confirmar.</p>
            <form id="delete-account-form" class="mt-4" novalidate @submit.prevent="deleteUser">
                <FormField id="delete-account-password" v-slot="{ field }" :error="form.errors.password" label="Senha">
                    <input ref="passwordInput" v-model="form.password" autocomplete="current-password" class="field h-10" type="password" v-bind="field" />
                </FormField>
            </form>
            <template #actions>
                <Button :disabled="form.processing" variant="secondary" @click="closeDialog">Cancelar</Button>
                <Button :loading="form.processing" form="delete-account-form" loading-text="Excluindo…" type="submit" variant="danger">
                    Excluir conta
                </Button>
            </template>
        </Dialog>
    </ProfileSection>
</template>
