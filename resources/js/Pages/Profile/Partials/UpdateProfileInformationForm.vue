<script setup>
import { Link, useForm, usePage } from "@inertiajs/vue3";
import FormField from "@/Components/Ui/FormField.vue";
import Button from "@/Components/Ui/Button.vue";
import ProfileSection from "./ProfileSection.vue";

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    meeting_day: user.meeting_day ?? "",
});
</script>

<template>
    <ProfileSection
        id="profile-info"
        description="Seu nome, e-mail de acesso e o dia em que sua congregação se reúne."
        title="Dados da conta"
    >
        <form class="space-y-5" novalidate @submit.prevent="form.patch(route('profile.update'), { preserveScroll: true })">
            <FormField id="profile-name" v-slot="{ field }" :error="form.errors.name" label="Nome" required>
                <input v-model="form.name" autocomplete="name" class="field h-10" type="text" v-bind="field" />
            </FormField>
            <FormField id="profile-email" v-slot="{ field }" :error="form.errors.email" label="E-mail" required>
                <input v-model="form.email" autocomplete="email" class="field h-10" type="email" v-bind="field" />
            </FormField>
            <FormField
                id="profile-meeting-day"
                v-slot="{ field }"
                :error="form.errors.meeting_day"
                hint="Define as datas em que recebemos oradores nos novos arranjos."
                label="Dia da nossa reunião"
            >
                <select v-model="form.meeting_day" class="field h-10 sm:w-56" v-bind="field">
                    <option value="">Selecione…</option>
                    <option value="6">Sábado</option>
                    <option value="0">Domingo</option>
                </select>
            </FormField>

            <div
                v-if="props.mustVerifyEmail && user.email_verified_at === null"
                class="rounded-md bg-clay-50 px-3 py-2.5 text-sm text-clay-900 dark:bg-clay-950 dark:text-clay-100"
            >
                Seu e-mail ainda não foi verificado.
                <Link :href="route('verification.send')" as="button" class="font-medium underline" method="post">
                    Reenviar e-mail de verificação
                </Link>
                <p v-if="props.status === 'verification-link-sent'" class="mt-1 font-medium">
                    Um novo link de verificação foi enviado para seu e-mail.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <Button :loading="form.processing" loading-text="Salvando…" type="submit">Salvar</Button>
                <p aria-live="polite" class="text-sm text-pine-800 dark:text-pine-300">
                    <span v-if="form.recentlySuccessful">Salvo.</span>
                </p>
            </div>
        </form>
    </ProfileSection>
</template>
