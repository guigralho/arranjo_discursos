<script setup>
import Form from "./Partials/Form.vue";
import {useForm} from "@inertiajs/inertia-vue3";

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    passengers: Object
});

const form = useForm({
    passenger_id: "",
    friday: "",
    saturday: "",
    sunday: "",
    amount: "",
    obs: "",
});

const emit = defineEmits(["close"]);

const submit = () => {
    form.post(route("bus.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            emit('close')
        },
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
        <Form :form="form" :passengers="passengers" :submit="submit"/>
    </div>
</template>
