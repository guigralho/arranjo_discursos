<script setup>
import DangerButton from "@/Components/Buttons/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/Buttons/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-5">
        <header>
            <h2 class="text-base font-semibold text-stone-900 dark:text-white">
                Excluir conta
            </h2>
            <p class="mt-1 text-sm text-stone-500 dark:text-slate-400">
                Depois que sua conta for excluída, todos os seus recursos e dados serão excluídos permanentemente.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Excluir conta</DangerButton>

        <Modal :show="confirmingUserDeletion" max-width="md" @close="closeModal">
            <h2 class="text-lg font-semibold text-stone-900 dark:text-white">
                Tem certeza de que deseja excluir sua conta?
            </h2>

            <p class="mt-2 text-sm text-stone-600 dark:text-slate-400">
                Depois que sua conta for excluída, todos os seus recursos e dados serão excluídos permanentemente. Digite sua senha para confirmar.
            </p>

            <div class="mt-5">
                <InputLabel class="sr-only" for="password" value="Senha" />
                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    class="block w-full"
                    placeholder="Senha"
                    type="password"
                    @keyup.enter="deleteUser"
                />
                <InputError :message="form.errors.password" class="mt-1.5" />
            </div>

            <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <SecondaryButton @click="closeModal">
                    Cancelar
                </SecondaryButton>

                <DangerButton
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                    @click="deleteUser"
                >
                    Excluir conta
                </DangerButton>
            </div>
        </Modal>
    </section>
</template>
