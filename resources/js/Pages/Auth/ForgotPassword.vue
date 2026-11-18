<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import FormField from "@/Components/Ui/FormField.vue";
import Button from "@/Components/Ui/Button.vue";

defineOptions({ layout: null });

defineProps({
    status: String,
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <GuestLayout description="Informe seu e-mail e enviaremos um link para criar uma nova senha." title="Recuperar senha">
        <Head title="Recuperar senha" />

        <p v-if="status" class="mb-6 rounded-md bg-pine-50 px-3 py-2 text-sm font-medium text-pine-900 dark:bg-pine-950 dark:text-pine-100" role="status">
            {{ status }}
        </p>

        <form class="space-y-5" novalidate @submit.prevent="submit">
            <FormField id="email" v-slot="{ field }" :error="form.errors.email" label="E-mail">
                <input v-model="form.email" autocomplete="username" autofocus class="field h-11" type="email" v-bind="field" />
            </FormField>
            <Button :loading="form.processing" block class="h-11" loading-text="Enviando…" type="submit">Enviar link</Button>
            <p class="text-center text-sm">
                <Link :href="route('login')" class="font-medium text-pine-800 hover:underline dark:text-pine-300">Voltar para o login</Link>
            </p>
        </form>
    </GuestLayout>
</template>
