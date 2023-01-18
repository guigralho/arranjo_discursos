<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm, Link } from "@inertiajs/inertia-vue3";

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

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";

export default {
    layout: GuestLayout,
};
</script>

<template>
    <Head title="Forgot Password" />

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        Esqueceu sua senha? Sem problemas. Basta nos informar seu endereço de
        e-mail e enviaremos um e-mail com um link de redefinição de senha que
        permitirá que você escolha um novo.
    </div>

    <div
        v-if="status"
        class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
    >
        {{ status }}
    </div>

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                v-model="form.email"
                autocomplete="username"
                autofocus
                class="mt-1 block w-full"
                required
                type="email"
            />

            <InputError :message="form.errors.email" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-between">
            <Link
                :href="route('login')"
                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
            >
                Voltar
            </Link>
            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Enviar link de recuperação
            </PrimaryButton>
        </div>
    </form>
</template>
