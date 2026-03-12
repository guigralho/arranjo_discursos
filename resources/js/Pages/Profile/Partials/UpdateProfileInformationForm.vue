<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import SaveButton from "@/Components/Buttons/SaveButton.vue";
import SelectInput from "@/Components/SelectInput.vue";

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    meeting_day: user.meeting_day,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-base font-semibold text-stone-900 dark:text-white">
                Informação do Perfil
            </h2>
            <p class="mt-1 text-sm text-stone-500 dark:text-slate-400">
                Atualize as informações de perfil e endereço de e-mail da sua conta.
            </p>
        </header>

        <form
            class="mt-6 space-y-5"
            @submit.prevent="form.patch(route('profile.update'))"
        >
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
                    autocomplete="email"
                    class="mt-1.5 block w-full"
                    required
                    type="email"
                />
                <InputError :message="form.errors.email" class="mt-1.5" />
            </div>

            <div>
                <InputLabel for="day" value="Dia de reunião" />
                <SelectInput
                    id="day"
                    ref="dayInput"
                    v-model="form.meeting_day"
                    class="mt-1.5 block w-full"
                >
                    <option value="">Selecione...</option>
                    <option value="6">Sábado</option>
                    <option value="0">Domingo</option>
                </SelectInput>
                <InputError :message="form.errors.meeting_day" class="mt-1.5" />
            </div>

            <div
                v-if="props.mustVerifyEmail && user.email_verified_at === null"
            >
                <p class="mt-2 text-sm text-stone-700 dark:text-slate-300">
                    Seu endereço de e-mail não foi verificado.
                    <Link
                        :href="route('verification.send')"
                        as="button"
                        class="text-sm font-medium text-teal-600 underline hover:text-teal-700 dark:text-teal-400"
                        method="post"
                    >
                        Clique aqui para reenviar o e-mail de verificação.
                    </Link>
                </p>

                <div
                    v-show="props.status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-emerald-600 dark:text-emerald-400"
                >
                    Um novo link de verificação foi enviado para seu endereço de e-mail.
                </div>
            </div>

            <div class="flex items-center gap-3">
                <SaveButton :disabled="form.processing">Salvar</SaveButton>

                <Transition
                    class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-emerald-600 dark:text-emerald-400"
                    >
                        Salvo.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
