<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import FormField from "@/Components/Ui/FormField.vue";
import Button from "@/Components/Ui/Button.vue";

defineOptions({ layout: null });

const form = useForm({
    password: "",
});

const submit = () => {
    form.post(route("password.confirm"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout description="Esta é uma área protegida. Confirme sua senha para continuar." title="Confirmar senha">
        <Head title="Confirmar senha" />

        <form class="space-y-5" novalidate @submit.prevent="submit">
            <FormField id="password" v-slot="{ field }" :error="form.errors.password" label="Senha">
                <input v-model="form.password" autocomplete="current-password" autofocus class="field h-11" type="password" v-bind="field" />
            </FormField>
            <Button :loading="form.processing" block class="h-11" loading-text="Confirmando…" type="submit">Confirmar</Button>
        </form>
    </GuestLayout>
</template>
