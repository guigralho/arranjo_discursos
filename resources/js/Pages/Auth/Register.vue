<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

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

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";

export default {
    layout: GuestLayout,
};
</script>

<template>
    <Head title="Register" />

    <form @submit.prevent="submit" class="space-y-5">
        <div>
            <InputLabel for="name" value="Nome" />
            <TextInput
                id="name"
                v-model="form.name"
                autocomplete="name"
                autofocus
                class="mt-1.5 block w-full"
                required
                type="text"
            />
            <InputError :message="form.errors.name" class="mt-1.5" />
        </div>

        <div>
            <InputLabel for="email" value="Email" />
            <TextInput
                id="email"
                v-model="form.email"
                autocomplete="username"
                class="mt-1.5 block w-full"
                required
                type="email"
            />
            <InputError :message="form.errors.email" class="mt-1.5" />
        </div>

        <div>
            <InputLabel for="congregation_id" value="Congregação" />
            <TextInput
                id="congregation_id"
                v-model="form.congregation_id"
                class="mt-1.5 block w-full"
                required
                type="text"
            />
            <InputError :message="form.errors.congregation_id" class="mt-1.5" />
        </div>

        <div>
            <InputLabel for="password" value="Senha" />
            <TextInput
                id="password"
                v-model="form.password"
                autocomplete="new-password"
                class="mt-1.5 block w-full"
                required
                type="password"
            />
            <InputError :message="form.errors.password" class="mt-1.5" />
        </div>

        <div>
            <InputLabel for="password_confirmation" value="Confirme a senha" />
            <TextInput
                id="password_confirmation"
                v-model="form.password_confirmation"
                autocomplete="new-password"
                class="mt-1.5 block w-full"
                required
                type="password"
            />
            <InputError :message="form.errors.password_confirmation" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-between pt-1">
            <Link
                :href="route('login')"
                class="text-sm font-medium text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300"
            >
                Já possui um cadastro?
            </Link>

            <button
                :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                :disabled="form.processing"
                class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:bg-teal-500 dark:hover:bg-teal-600 dark:focus:ring-offset-slate-900"
                type="submit"
            >
                Cadastrar
            </button>
        </div>
    </form>
</template>
