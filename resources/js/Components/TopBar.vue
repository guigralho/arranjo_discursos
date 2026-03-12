<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { useDark, useToggle } from "@vueuse/core";
import { computed } from "vue";
import {
    BookOpenIcon,
    MoonIcon,
    SunIcon,
} from "@heroicons/vue/24/outline";

const isDark = useDark();
const toggleDark = useToggle(isDark);
const page = usePage();

const userInitials = computed(() => {
    const name = page.props.auth.user.name ?? "";
    return name
        .split(" ")
        .slice(0, 2)
        .map((n) => n[0])
        .join("")
        .toUpperCase();
});
</script>

<template>
    <header class="sticky top-0 z-30 flex h-14 items-center justify-between border-b border-stone-200 bg-white/80 px-4 backdrop-blur-lg dark:border-slate-800 dark:bg-slate-900/80 md:hidden">
        <!-- Logo -->
        <Link :href="route('dashboard')" class="flex items-center gap-2.5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-600 text-white">
                <BookOpenIcon class="h-4 w-4" />
            </div>
            <span class="text-sm font-bold tracking-tight text-stone-900 dark:text-white">
                {{ $page.props.app.name }}
            </span>
        </Link>

        <!-- Right side -->
        <div class="flex items-center gap-2">
            <button
                class="flex h-8 w-8 items-center justify-center rounded-lg text-stone-400 transition-colors hover:bg-stone-100 dark:text-slate-500 dark:hover:bg-slate-800"
                @click="toggleDark()"
            >
                <MoonIcon v-if="!isDark" class="h-4 w-4" />
                <SunIcon v-else class="h-4 w-4 text-amber-400" />
            </button>

            <Link :href="route('profile.edit')">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-100 text-[10px] font-bold text-teal-700 dark:bg-teal-500/20 dark:text-teal-400">
                    {{ userInitials }}
                </div>
            </Link>
        </div>
    </header>
</template>