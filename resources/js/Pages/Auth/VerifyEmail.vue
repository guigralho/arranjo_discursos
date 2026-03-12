<script setup>
import { computed } from "vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    status: String,
});

const form = useForm();

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent"
);
</script>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";

export default {
    layout: GuestLayout,
};
</script>

<template>
    <Head title="Email Verification" />

    <div class="mb-4 text-sm text-stone-600 dark:text-slate-400">
        Thanks for signing up! Before getting started, could you verify your
        email address by clicking on the link we just emailed to you? If you
        didn't receive the email, we will gladly send you another.
    </div>

    <div
        v-if="verificationLinkSent"
        class="mb-4 text-sm font-medium text-emerald-600 dark:text-emerald-400"
    >
        A new verification link has been sent to the email address you provided
        during registration.
    </div>

    <form @submit.prevent="submit">
        <div class="mt-4 flex items-center justify-between">
            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Resend Verification Email
            </PrimaryButton>

            <Link
                :href="route('logout')"
                as="button"
                class="rounded-md text-sm text-stone-600 underline hover:text-stone-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:text-slate-400 dark:hover:text-white dark:focus:ring-offset-slate-900"
                method="post"
                >Log Out</Link
            >
        </div>
    </form>
</template>
