<script setup>
import Navbar from "@/Components/Navbar.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import SuccessAlert from "@/Components/SuccessAlert.vue";
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <Navbar />

            <Breadcrumb />

            <!-- Page Content -->
            <main>
                <SuccessAlert />
                <slot />
            </main>
        </div>
    </div>
</template>
