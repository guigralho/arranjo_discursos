<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";

const form = useForm({
    name: "",
    email: "",
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

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";

export default {
    layout: GuestLayout,
};
</script>

<template>
    <Head title="Register" />

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="name" value="Nome" />

            <TextInput
                id="name"
                v-model="form.name"
                autocomplete="name"
                autofocus
                class="mt-1 block w-full"
                required
                type="text"
            />

            <InputError :message="form.errors.name" class="mt-2" />
        </div>

        <div class="mt-4">
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                v-model="form.email"
                autocomplete="username"
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
                autocomplete="new-password"
                class="mt-1 block w-full"
                required
                type="password"
            />

            <InputError :message="form.errors.password" class="mt-2" />
        </div>

        <div class="mt-4">
            <InputLabel for="password_confirmation" value="Confirme a senha" />

            <TextInput
                id="password_confirmation"
                v-model="form.password_confirmation"
                autocomplete="new-password"
                class="mt-1 block w-full"
                required
                type="password"
            />

            <InputError
                :message="form.errors.password_confirmation"
                class="mt-2"
            />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <Link
                :href="route('login')"
                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
            >
                Já possui um cadastro?
            </Link>

            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                class="ml-4"
            >
                Cadastrar
            </PrimaryButton>
        </div>
    </form>
</template>
