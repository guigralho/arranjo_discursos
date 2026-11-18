<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import FormField from "@/Components/Ui/FormField.vue";
import Button from "@/Components/Ui/Button.vue";

defineOptions({ layout: null });

const form = useForm({
    name: "",
    email: "",
    congregation_id: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout description="Cadastre-se para organizar os arranjos da sua congregação." title="Criar conta">
        <Head title="Criar conta" />

        <form class="space-y-5" novalidate @submit.prevent="submit">
            <FormField id="name" v-slot="{ field }" :error="form.errors.name" label="Nome" required>
                <input v-model="form.name" autocomplete="name" autofocus class="field h-11" type="text" v-bind="field" />
            </FormField>
            <FormField id="email" v-slot="{ field }" :error="form.errors.email" label="E-mail" required>
                <input v-model="form.email" autocomplete="username" class="field h-11" type="email" v-bind="field" />
            </FormField>
            <FormField id="congregation_id" v-slot="{ field }" :error="form.errors.congregation_id" label="Congregação">
                <input v-model="form.congregation_id" autocomplete="off" class="field h-11" type="text" v-bind="field" />
            </FormField>
            <FormField id="password" v-slot="{ field }" :error="form.errors.password" label="Senha" required>
                <input v-model="form.password" autocomplete="new-password" class="field h-11" type="password" v-bind="field" />
            </FormField>
            <FormField id="password_confirmation" v-slot="{ field }" :error="form.errors.password_confirmation" label="Confirmar senha" required>
                <input v-model="form.password_confirmation" autocomplete="new-password" class="field h-11" type="password" v-bind="field" />
            </FormField>
            <Button :loading="form.processing" block class="h-11" loading-text="Criando…" type="submit">Criar conta</Button>
            <p class="text-center text-sm text-stone-600 dark:text-stone-400">
                Já tem conta?
                <Link :href="route('login')" class="font-medium text-pine-800 hover:underline dark:text-pine-300">Entrar</Link>
            </p>
        </form>
    </GuestLayout>
</template>
