<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/inertia-vue3";
import SaveButton from "@/Components/Buttons/SaveButton.vue";

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.value.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Informação do Perfil
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Atualize as informações de perfil e endereço de e-mail da sua
                conta.
            </p>
        </header>

        <form
            class="mt-6 space-y-6"
            @submit.prevent="form.patch(route('profile.update'))"
        >
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

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    v-model="form.email"
                    autocomplete="email"
                    class="mt-1 block w-full"
                    required
                    type="email"
                />

                <InputError :message="form.errors.email" class="mt-2" />
            </div>

            <div
                v-if="props.mustVerifyEmail && user.email_verified_at === null"
            >
                <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                    Seu endereço de e-mail não foi verificado.
                    <Link
                        :href="route('verification.send')"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                        method="post"
                    >
                        Clique aqui para reenviar o e-mail de verificação.
                    </Link>
                </p>

                <div
                    v-show="props.status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    Um novo link de verificação foi enviado para seu endereço de
                    e-mail.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <SaveButton :disabled="form.processing">Salvar</SaveButton>

                <Transition
                    class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Salvo.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
