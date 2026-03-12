<script setup>
import { Head } from "@inertiajs/vue3";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});
</script>

<template>
    <Head title="Perfil" />

    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
        <Breadcrumb />

        <h1 class="mb-6 text-2xl font-bold tracking-tight text-stone-900 dark:text-white">
            Perfil
        </h1>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                />
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800">
                <UpdatePasswordForm />
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800 md:col-span-2">
                <DeleteUserForm />
            </div>
        </div>
    </div>
</template>
