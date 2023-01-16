<script setup>
import Form from "./Partials/Form.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";

const form = useForm({
    formatted_month: "",
    congregation: "",
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
            console.log(form.errors);
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
