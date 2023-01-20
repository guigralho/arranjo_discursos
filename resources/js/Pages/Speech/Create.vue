<script setup>
import Form from "./Partials/Form.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";

const form = useForm({
    number: "",
    theme: "",
});

const submit = () => {
    form.post(route("speeches.store"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.theme) {
                form.reset("theme");
            }
            if (form.errors.number) {
                form.reset("number");
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

    <div class="mx-auto max-w-7xl space-y-6 py-6 px-4 sm:px-6 lg:px-8">
        <Form :form="form" :submit="submit" />
    </div>
</template>
