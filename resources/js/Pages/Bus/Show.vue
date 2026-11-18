<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import Form from "./Partials/Form.vue";
import PageHeader from "@/Components/Ui/PageHeader.vue";

const props = defineProps({
    bus: Object,
    passengers: Array,
});

const form = useForm({
    passenger_id: props.bus.data.passenger_id,
    friday: props.bus.data.friday === 1,
    saturday: props.bus.data.saturday === 1,
    sunday: props.bus.data.sunday === 1,
    amount: props.bus.data.formatted_amount,
    obs: props.bus.data.obs,
});

const submit = () => {
    form.put(route("bus.update", props.bus.data.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="bus.data.passenger?.name ?? 'Reserva'" />

    <PageHeader :back-href="route('bus.index')" :title="bus.data.passenger?.name ?? 'Reserva'" back-label="Ônibus" eyebrow="Reserva" />

    <Form :cancel-href="route('bus.index')" :form="form" :passengers="passengers" :submit="submit" />
</template>
