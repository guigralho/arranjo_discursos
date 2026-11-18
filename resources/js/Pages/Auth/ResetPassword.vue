<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import FormField from "@/Components/Ui/FormField.vue";
import Button from "@/Components/Ui/Button.vue";

defineOptions({ layout: null });

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("password.store"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout title="Nova senha">
        <Head title="Nova senha" />

        <form class="space-y-5" novalidate @submit.prevent="submit">
            <FormField id="email" v-slot="{ field }" :error="form.errors.email" label="E-mail">
                <input v-model="form.email" autocomplete="username" class="field h-11" type="email" v-bind="field" />
            </FormField>
            <FormField id="password" v-slot="{ field }" :error="form.errors.password" label="Nova senha">
                <input v-model="form.password" autocomplete="new-password" autofocus class="field h-11" type="password" v-bind="field" />
            </FormField>
            <FormField id="password_confirmation" v-slot="{ field }" :error="form.errors.password_confirmation" label="Confirmar nova senha">
                <input v-model="form.password_confirmation" autocomplete="new-password" class="field h-11" type="password" v-bind="field" />
            </FormField>
            <Button :loading="form.processing" block class="h-11" loading-text="Salvando…" type="submit">Salvar nova senha</Button>
        </form>
    </GuestLayout>
</template>
