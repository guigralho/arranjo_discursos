<script setup>
import { computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Button from "@/Components/Ui/Button.vue";

defineOptions({ layout: null });

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(() => props.status === "verification-link-sent");
</script>

<template>
    <GuestLayout
        description="Enviamos um link de verificação para o seu e-mail. Clique nele para começar. Se não recebeu, podemos enviar outro."
        title="Verifique seu e-mail"
    >
        <Head title="Verificar e-mail" />

        <p v-if="verificationLinkSent" class="mb-6 rounded-md bg-pine-50 px-3 py-2 text-sm font-medium text-pine-900 dark:bg-pine-950 dark:text-pine-100" role="status">
            Um novo link de verificação foi enviado para o e-mail informado no cadastro.
        </p>

        <form class="flex items-center justify-between gap-4" @submit.prevent="submit">
            <Button :loading="form.processing" loading-text="Enviando…" type="submit">Reenviar e-mail</Button>
            <Link :href="route('logout')" as="button" class="text-sm font-medium text-stone-600 hover:underline dark:text-stone-400" method="post">
                Sair
            </Link>
        </form>
    </GuestLayout>
</template>
