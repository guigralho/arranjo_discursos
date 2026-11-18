<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import Form from "./Partials/Form.vue";
import PageHeader from "@/Components/Ui/PageHeader.vue";

defineProps({
    name: String,
});

const form = useForm({
    privilege: "",
    name: "",
    phone: "",
});

const submit = () => {
    form.post(route("speakers.store"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="name" />

    <PageHeader :back-href="route('speakers.index')" back-label="Oradores" title="Novo orador">
        <template #meta>
            <span>Depois de salvar, adicione os temas que ele preparou.</span>
        </template>
    </PageHeader>

    <div class="max-w-2xl">
        <Form :cancel-href="route('speakers.index')" :form="form" :submit="submit" submit-label="Criar orador" />
    </div>
</template>
