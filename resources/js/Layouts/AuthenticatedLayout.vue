<script setup>
import Sidebar from "@/Components/Layout/Sidebar.vue";
import MobileNav from "@/Components/Layout/MobileNav.vue";
import Toast from "@/Components/Ui/Toast.vue";
</script>

<template>
    <div class="min-h-screen bg-stone-50 dark:bg-stone-950">
        <a
            class="sr-only z-[80] rounded-md bg-white px-3 py-2 text-sm font-medium focus:not-sr-only focus:fixed focus:left-3 focus:top-3 dark:bg-stone-900"
            href="#conteudo"
            >Pular para o conteúdo</a
        >
        <Sidebar />
        <MobileNav />

        <div class="lg:pl-60">
            <main
                id="conteudo"
                class="mx-auto w-full max-w-6xl px-4 pb-28 pt-6 sm:px-6 sm:pt-8 lg:px-10 lg:pb-16 lg:pt-10"
                tabindex="-1"
            >
                <slot />
            </main>
        </div>

        <Toast />
    </div>
</template>
