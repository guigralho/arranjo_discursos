<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/inertia-vue3";
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-gray-100 pt-6 dark:bg-gray-900 sm:pt-0"
    >
        <div>
            <Link href="/">
                <!--                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />-->
                <h1 class="text-3xl font-bold text-gray-400">
                    {{ $inertia.page.props.app.name }}
                </h1>
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden rounded-lg bg-white px-6 py-4 shadow-md dark:bg-gray-800 sm:max-w-md"
        >
            <slot />
        </div>
    </div>
</template>
