<script setup>
import { useForm } from "@inertiajs/inertia-vue3";

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    passengers: Object,
});

const form = useForm({
    passenger_id: "",
    friday: 0,
    saturday: 0,
    sunday: 0,
    amount: "",
    obs: "",
});

const emit = defineEmits(["close"]);

const submit = () => {
    form.post(route("bus.store"), {
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
