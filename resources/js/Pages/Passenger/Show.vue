<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import Form from "./Partials/Form.vue";
import PageHeader from "@/Components/Ui/PageHeader.vue";

const props = defineProps({
    name: String,
    passenger: Object,
});

const form = useForm({
    name: props.passenger.name,
    doc: props.passenger.doc,
    phone: props.passenger.phone,
});

const submit = () => {
    form.put(route("passengers.update", props.passenger.id), {
        preserveScroll: true,
        onSuccess: () => form.defaults(),
    });
};
</script>

<template>
    <Head :title="passenger.name" />

    <PageHeader :back-href="route('passengers.index')" :title="passenger.name" back-label="Passageiros" eyebrow="Passageiro" />

    <Form :form="form" :submit="submit" />
    <p v-if="passenger.user_updated" class="mt-6 max-w-2xl text-xs text-stone-500 dark:text-stone-400">
        Atualizado em {{ passenger.updated_at }} por {{ passenger.user_updated.name }}.
    </p>
</template>
