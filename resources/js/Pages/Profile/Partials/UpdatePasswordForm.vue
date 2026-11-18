<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import FormField from "@/Components/Ui/FormField.vue";
import Button from "@/Components/Ui/Button.vue";
import ProfileSection from "./ProfileSection.vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const updatePassword = () => {
    form.put(route("password.update"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <ProfileSection id="profile-password" description="Use uma senha longa e difícil de adivinhar." title="Senha">
        <form class="space-y-5" novalidate @submit.prevent="updatePassword">
            <FormField id="current-password" v-slot="{ field }" :error="form.errors.current_password" label="Senha atual">
                <input ref="currentPasswordInput" v-model="form.current_password" autocomplete="current-password" class="field h-10" type="password" v-bind="field" />
            </FormField>
            <FormField id="new-password" v-slot="{ field }" :error="form.errors.password" label="Nova senha">
                <input ref="passwordInput" v-model="form.password" autocomplete="new-password" class="field h-10" type="password" v-bind="field" />
            </FormField>
            <FormField id="new-password-confirmation" v-slot="{ field }" :error="form.errors.password_confirmation" label="Confirmar nova senha">
                <input v-model="form.password_confirmation" autocomplete="new-password" class="field h-10" type="password" v-bind="field" />
            </FormField>
            <div class="flex items-center gap-3">
                <Button :loading="form.processing" loading-text="Salvando…" type="submit">Atualizar senha</Button>
                <p aria-live="polite" class="text-sm text-pine-800 dark:text-pine-300">
                    <span v-if="form.recentlySuccessful">Senha atualizada.</span>
                </p>
            </div>
        </form>
    </ProfileSection>
</template>
