<script setup>
import Sidebar from "@/Components/Sidebar.vue";
import TopBar from "@/Components/TopBar.vue";
import BottomNav from "@/Components/BottomNav.vue";
import SuccessAlert from "@/Components/SuccessAlert.vue";
import GlobalLoading from "@/Components/GlobalLoading.vue";
import { useSidebar } from "@/composables/useSidebar";

const { collapsed } = useSidebar();
</script>

<template>
    <div class="min-h-screen bg-stone-50 dark:bg-slate-950">
        <!-- Desktop sidebar -->
        <Sidebar />

        <!-- Mobile top bar -->
        <TopBar />

        <!-- Main content area -->
        <div
            :class="collapsed ? 'md:pl-[72px]' : 'md:pl-64'"
            class="transition-all duration-300"
        >
            <main class="pb-20 md:pb-0">
                <SuccessAlert />
                <slot />
            </main>
        </div>

        <!-- Mobile bottom nav -->
        <BottomNav />

        <!-- Global loading -->
        <GlobalLoading />
    </div>
</template>
