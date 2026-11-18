<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Checkbox from "@/Components/Checkbox.vue";
import FormField from "@/Components/Ui/FormField.vue";
import Button from "@/Components/Ui/Button.vue";

defineOptions({ layout: null });

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout description="Acesse para organizar o arranjo do mês." title="Entrar">
        <Head title="Entrar" />

        <p v-if="status" class="mb-6 rounded-md bg-pine-50 px-3 py-2 text-sm font-medium text-pine-900 dark:bg-pine-950 dark:text-pine-100" role="status">
            {{ status }}
        </p>

        <form class="space-y-5" novalidate @submit.prevent="submit">
            <FormField id="email" v-slot="{ field }" :error="form.errors.email" label="E-mail">
                <input v-model="form.email" autocomplete="username" autofocus class="field h-11" type="email" v-bind="field" />
            </FormField>
            <FormField id="password" v-slot="{ field }" :error="form.errors.password" label="Senha">
                <input v-model="form.password" autocomplete="current-password" class="field h-11" type="password" v-bind="field" />
            </FormField>
            <div class="flex items-center justify-between gap-4">
                <label class="flex items-center gap-2 text-sm text-stone-700 dark:text-stone-300">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    Manter conectado
                </label>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm font-medium text-pine-800 hover:underline dark:text-pine-300"
                >
                    Esqueceu a senha?
                </Link>
            </div>
            <Button :loading="form.processing" block class="h-11" loading-text="Entrando…" type="submit">Entrar</Button>
        </form>
    </GuestLayout>
</template>
