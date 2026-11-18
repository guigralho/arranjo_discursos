<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import Form from "./Partials/Form.vue";
import PageHeader from "@/Components/Ui/PageHeader.vue";

defineProps({
    name: String,
});

const form = useForm({
    formatted_month: "",
    congregation: "",
    day: "",
    hour: "",
    address: "",
    contact: "",
    phone: "",
});

const submit = () => {
    form.post(route("schedules.store"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head :title="name" />

    <PageHeader :back-href="route('schedules.index')" back-label="Arranjos" title="Novo arranjo">
        <template #meta>
            <span>Depois de criar, você define quem recebemos e quem enviamos em cada data.</span>
        </template>
    </PageHeader>

    <Form :cancel-href="route('schedules.index')" :form="form" :submit="submit" submit-label="Criar arranjo" />
</template>
