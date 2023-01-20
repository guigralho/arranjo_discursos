<script setup>
import Form from "./Partials/Form.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import SpeechsList from "@/Pages/Speaker/Partials/SpeechsList.vue";

const props = defineProps({
    name: String,
    speaker: Object,
    mySpeeches: Object,
    speeches: Object,
});

const form = useForm({
    privilege: props.speaker.privilege,
    name: props.speaker.name,
    phone: props.speaker.phone,
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
            if (form.errors.phone) {
                form.reset("phone");
            }
        },
    });
};
</script>

<template>
    <Head :title="name" />

    <div
        class="mx-auto grid max-w-7xl grid-cols-none gap-6 py-6 px-4 sm:grid-cols-2 sm:px-6 lg:px-8"
    >
        <Form :data="speaker" :form="form" :submit="submit" />
        <SpeechsList
            :my-speeches="mySpeeches"
            :speaker="speaker"
            :speeches="speeches"
        />
    </div>
</template>
