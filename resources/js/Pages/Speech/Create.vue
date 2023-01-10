<script setup>
import Form from "./Partials/Form.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

const themeInput = ref(null);
const numberInput = ref(null);

const form = useForm({
    number: "",
    theme: "",
});

const submit = () => {
    form.post(route("speeches.store"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset("theme");
                themeInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset("number");
                numberInput.value.focus();
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

    <div class="mx-auto max-w-7xl space-y-6 py-6 sm:px-6 lg:px-8">
        <Form :form="form" :submit="submit" />
    </div>
</template>
