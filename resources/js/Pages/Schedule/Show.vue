<script setup>
import Form from "./Partials/Form.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import ScheduleSpeechesForm from "@/Pages/Schedule/Partials/ScheduleSpeechesForm.vue";

const props = defineProps({
    name: String,
    schedule: Object,
    receive: Object,
    send: Object,
    speeches: Object,
    speakers: Object,
});

const form = useForm({
    formatted_month: props.schedule.month_year,
    congregation: props.schedule.congregation,
    day: props.schedule.day,
    hour: props.schedule.hour,
    address: props.schedule.address,
    contact: props.schedule.contact,
    phone: props.schedule.phone,
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
</script>

<template>
    <Head :title="name" />

    <div class="mx-auto max-w-7xl gap-6 px-4 py-6 sm:px-6 lg:px-8">
        <Form :data="schedule" :form="form" :submit="submit" />

        <ScheduleSpeechesForm
            :dates="schedule.to_receive"
            :save-route="route('schedules.save-receive', props.schedule.id)"
            :schedule-id="props.schedule.id"
            :speeches="speeches"
            title="Receber"
        />
        <ScheduleSpeechesForm
            :dates="schedule.to_send"
            :save-route="route('schedules.save-send', props.schedule.id)"
            :schedule="schedule"
            :schedule-id="props.schedule.id"
            :speakers="speakers"
            :speeches="speeches"
            title="Enviar"
        />
    </div>
</template>
