<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import Form from "./Partials/Form.vue";
import PageHeader from "@/Components/Ui/PageHeader.vue";

defineProps({
    passengers: Array,
});

const form = useForm({
    passenger_id: "",
    friday: false,
    saturday: false,
    sunday: false,
    amount: "",
    obs: "",
});

const submit = () => {
    form.post(route("bus.store"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Nova reserva" />

    <PageHeader :back-href="route('bus.index')" back-label="Ônibus" title="Nova reserva" />

    <Form :cancel-href="route('bus.index')" :form="form" :passengers="passengers" :submit="submit" submit-label="Salvar reserva" />
</template>
