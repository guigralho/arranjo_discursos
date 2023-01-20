<script setup>
import Form from "./Partials/Form.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";

const form = useForm({
    privilege: "",
    name: "",
    phone: "",
});

const submit = () => {
    form.post(route("speakers.store"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.privilege) {
                form.reset("privilege");
            }
            if (form.errors.name) {
                form.reset("name");
            }
            if (form.errors.phone) {
                form.reset("phone");
            }
        },
    });
};

const back = () => {
    window.history.back();
};

defineProps({
    name: String,
});
</script>

<template>
    <Head :title="name" />

    <div class="mx-auto grid max-w-7xl gap-6 py-6 px-4 sm:px-6 lg:px-8">
        <Form :form="form" :submit="submit" />
    </div>
</template>
