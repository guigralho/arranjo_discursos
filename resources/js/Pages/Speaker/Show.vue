<script setup>
import Form from "./Partials/Form.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";

const props = defineProps({
    name: String,
    speaker: Object,
});

const form = useForm({
    name: props.speaker.name,
    privilege: props.speaker.privilege,
});

const submit = () => {
    form.put(route("speakers.update", props.speaker.id), {
        preserveScroll: true,
        onSuccess: () => {},
        onError: () => {
            if (form.errors.privilege) {
                form.reset("privilege");
            }
            if (form.errors.name) {
                form.reset("name");
            }
        },
    });
};
</script>

<template>
    <Head :title="name" />

    <div class="mx-auto max-w-7xl space-y-6 py-6 sm:px-6 lg:px-8">
        <Form :data="speaker" :form="form" :submit="submit" />
    </div>
</template>
