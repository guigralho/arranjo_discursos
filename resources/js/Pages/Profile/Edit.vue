<script setup>
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});
</script>

<template>
    <div class="py-12">
        <div
            class="mx-auto grid max-w-7xl grid-cols-1 gap-6 sm:px-6 md:grid-cols-2 lg:px-8"
        >
            <div
                class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8"
            >
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <div
                class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8"
            >
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div
                class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8 md:col-span-2"
            >
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </div>
</template>
