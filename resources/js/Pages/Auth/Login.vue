<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";

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

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";

export default {
    layout: GuestLayout,
};
</script>

<template>
    <Head title="Log in" />

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
        {{ status }}
    </div>

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                v-model="form.email"
                autocomplete="email"
                autofocus
                class="mt-1 block w-full"
                required
                type="email"
            />

            <InputError :message="form.errors.email" class="mt-2" />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Senha" />

            <TextInput
                id="password"
                v-model="form.password"
                autocomplete="current-password"
                class="mt-1 block w-full"
                required
                type="password"
            />

            <InputError :message="form.errors.password" class="mt-2" />
        </div>

        <div class="mt-4 block">
            <label class="flex items-center">
                <Checkbox v-model:checked="form.remember" name="remember" />
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400"
                    >Manter conectado</span
                >
            </label>
        </div>

        <div class="mt-4 flex items-center justify-end">
            <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
            >
                Esqueceu sua senha?
            </Link>

            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :href="route('register')"
                class="ml-4"
            >
                Cadastrar-se
            </PrimaryButton>

            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                class="ml-4"
            >
                Entrar
            </PrimaryButton>
        </div>
    </form>
</template>
