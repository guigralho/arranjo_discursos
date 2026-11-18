<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import Form from "./Partials/Form.vue";
import PageHeader from "@/Components/Ui/PageHeader.vue";

defineProps({
    name: String,
});

const form = useForm({
    number: "",
    theme: "",
});

const submit = () => {
    form.post(route("speeches.store"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="name" />

    <PageHeader :back-href="route('speeches.index')" back-label="Discursos" title="Novo discurso" />

    <Form :cancel-href="route('speeches.index')" :form="form" :submit="submit" submit-label="Criar discurso" />
</template>
