<script setup>
import Form from "./Partials/Form.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

const props = defineProps({
    name: String,
    speech: Object,
});

const form = useForm({
    number: props.speech.number,
    theme: props.speech.theme,
});

const submit = () => {
    form.put(route("speeches.update", props.speech.id), {
        preserveScroll: true,
        onSuccess: () => {},
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
</script>

<template>
    <Head :title="name" />

    <div class="mx-auto max-w-7xl space-y-6 py-6 px-4 sm:px-6 lg:px-8">
        <Form :form="form" :speech="speech" :submit="submit" />
    </div>
</template>
