<template>
    <div
        class="flex items-center justify-between border-t border-stone-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900 sm:px-6"
    >
        <!-- Mobile -->
        <div
            :class="list.links[0].url ? 'justify-between' : 'justify-end'"
            class="flex flex-1 items-center md:hidden"
        >
            <Link
                v-if="list.links[0].url"
                :href="list.links[0].url"
                class="rounded-xl border border-stone-300 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-colors hover:bg-stone-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
            >
                Anterior
            </Link>
            <Link
                v-if="list.links[list.links.length - 1].url"
                :href="list.links[list.links.length - 1].url"
                class="rounded-xl border border-stone-300 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-colors hover:bg-stone-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
            >
                Próximo
            </Link>
        </div>

        <!-- Desktop -->
        <div class="hidden sm:items-center sm:justify-between md:flex md:flex-1">
            <div>
                <p class="text-sm text-stone-600 dark:text-slate-400">
                    Mostrando de
                    {{ " " }}
                    <span class="font-semibold text-stone-900 dark:text-white">{{ list.from }}</span>
                    {{ " " }}
                    até
                    {{ " " }}
                    <span class="font-semibold text-stone-900 dark:text-white">{{ list.to }}</span>
                    {{ " " }}
                    de
                    {{ " " }}
                    <span class="font-semibold text-stone-900 dark:text-white">{{ list.total }}</span>
                    {{ " " }}
                    resultados
                </p>
            </div>
            <div>
                <nav
                    aria-label="Pagination"
                    class="isolate inline-flex gap-1"
                >
                    <Link
                        v-for="(link, index) in list.links"
                        :key="index"
                        :class="getClass(link.active, index, list.links.length)"
                        :href="link.url ?? '#'"
                        class="inline-flex items-center rounded-lg px-3 py-1.5 text-sm font-medium transition-colors focus:z-20"
                        v-html="
                            (link.label ?? '')
                                .replaceAll('Next', '')
                                .replaceAll('Previous', '')
                        "
                    />
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    list: {
        type: Object,
        default: null,
    },
});

function getClass(active, index, length) {
    if (active) {
        return "bg-teal-600 text-white dark:bg-teal-500";
    }
    return "text-stone-600 hover:bg-stone-100 dark:text-slate-400 dark:hover:bg-slate-800";
}
</script>
