<script setup>
import Form from "./Partials/Form.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";

const props = defineProps({
    name: String,
    schedule: Object,
});

const form = useForm({
    formatted_month: props.schedule.formatted_month,
    congregation: props.schedule.congregation,
});

const submit = () => {
    form.put(route("schedules.update", props.schedule.id), {
        preserveScroll: true,
        onSuccess: () => {},
        onError: () => {
            if (form.errors.formatted_month) {
                form.reset("formatted_month");
            }
            if (form.errors.congregation) {
                form.reset("congregation");
            }
        },
    });
};
</script>

<template>
    <Head :title="name" />

    <div class="mx-auto max-w-7xl gap-6 py-6 sm:px-6 lg:px-8">
        <Form :data="schedule" :form="form" :submit="submit" />
    </div>
</template>
