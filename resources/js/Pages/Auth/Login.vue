<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

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

    <div v-if="status" class="mb-4 rounded-xl bg-emerald-50 p-3 text-sm font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
        {{ status }}
    </div>

    <form @submit.prevent="submit" class="space-y-5">
        <div>
            <InputLabel for="email" value="Email" />
            <TextInput
                id="email"
                v-model="form.email"
                autocomplete="email"
                autofocus
                class="mt-1.5 block w-full"
                required
                type="email"
            />
            <InputError :message="form.errors.email" class="mt-1.5" />
        </div>

        <div>
            <InputLabel for="password" value="Senha" />
            <TextInput
                id="password"
                v-model="form.password"
                autocomplete="current-password"
                class="mt-1.5 block w-full"
                required
                type="password"
            />
            <InputError :message="form.errors.password" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2">
                <Checkbox v-model:checked="form.remember" name="remember" />
                <span class="text-sm text-stone-600 dark:text-slate-400">Manter conectado</span>
            </label>

            <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="text-sm font-medium text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300"
            >
                Esqueceu sua senha?
            </Link>
        </div>

        <button
            :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
            :disabled="form.processing"
            class="w-full rounded-xl bg-teal-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:bg-teal-500 dark:hover:bg-teal-600 dark:focus:ring-offset-slate-900"
            type="submit"
        >
            Entrar
        </button>
    </form>
</template>
