<script setup>
import Form from "./Partials/Form.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";

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
        onError: () => {
            if (form.errors.formatted_month) {
                form.reset("formatted_month");
            }
            if (form.errors.congregation) {
                form.reset("congregation");
            }
            if (form.errors.day) {
                form.reset("day");
            }
            if (form.errors.hour) {
                form.reset("hour");
            }
            if (form.errors.address) {
                form.reset("address");
            }
            if (form.errors.contact) {
                form.reset("contact");
            }
            if (form.errors.phone) {
                form.reset("phone");
            }
        },
    });
};

defineProps({
    name: String,
});
</script>

<template>
    <Head :title="name" />

    <div class="mx-auto max-w-7xl gap-6 py-6 sm:px-6 lg:px-8">
        <Form :form="form" :submit="submit" />
    </div>
</template>
