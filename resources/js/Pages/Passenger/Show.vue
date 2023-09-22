<script setup>
import Form from "./Partials/Form.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";

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
        onSuccess: () => {},
        onError: () => {
            if (form.errors.name) {
                form.reset("name");
            }
            if (form.errors.doc) {
                form.reset("doc");
            }
            if (form.errors.phone) {
                form.reset("phone");
            }
        },
    });
};
</script>

<template>
    <Head :title="name" />

    <div class="mx-auto max-w-7xl space-y-6 py-6 px-4 sm:px-6 lg:px-8">
        <Form :form="form" :passenger="passenger" :submit="submit" />
    </div>
</template>
