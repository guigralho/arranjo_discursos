<script setup>
import Form from "./Partials/Form.vue";
import { useForm } from "@inertiajs/inertia-vue3";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    bus: Object,
    passengers: Object,
});

const form = useForm({
    passenger_id: props.bus.data.passenger_id,
    friday: props.bus.data.friday === 1,
    saturday: props.bus.data.saturday === 1,
    sunday: props.bus.data.sunday === 1,
    amount: props.bus.data.formatted_amount,
    obs: props.bus.data.obs,
});

const submit = () => {
    form.put(route("bus.update", props.bus.data.id), {
        preserveScroll: true,
        onSuccess: () => {},
        onError: () => {
            if (form.errors.passenger_id) {
                form.reset("passenger_id");
            }
        },
    });
};
</script>

<template>
    <div class="mx-auto grid max-w-7xl gap-6 py-6 px-4 sm:px-6 lg:px-8">
        <Form :form="form" :passengers="passengers" :submit="submit" />
    </div>
</template>
