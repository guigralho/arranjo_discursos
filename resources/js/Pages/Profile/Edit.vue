<script setup>
import { Head } from "@inertiajs/vue3";
import PageHeader from "@/Components/Ui/PageHeader.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});
</script>

<template>
    <Head title="Meu perfil" />

    <PageHeader title="Meu perfil" />

    <div class="divide-y divide-stone-200 dark:divide-stone-800">
        <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
        <UpdatePasswordForm />
        <DeleteUserForm />
    </div>
</template>
