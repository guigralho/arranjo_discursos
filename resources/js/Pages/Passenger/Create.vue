<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import Form from "./Partials/Form.vue";
import PageHeader from "@/Components/Ui/PageHeader.vue";

defineProps({
    name: String,
});

const form = useForm({
    name: "",
    doc: "",
    phone: "",
});

const submit = () => {
    form.post(route("passengers.store"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="name" />

    <PageHeader :back-href="route('passengers.index')" back-label="Passageiros" title="Novo passageiro" />

    <Form :cancel-href="route('passengers.index')" :form="form" :submit="submit" submit-label="Criar passageiro" />
</template>
