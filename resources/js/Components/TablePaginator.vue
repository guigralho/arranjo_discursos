<template>
    <div
        class="flex items-center justify-between border-gray-200 bg-white px-4 py-3 dark:bg-gray-800 sm:px-6"
    >
        <div
            :class="list.links[0].url ? 'justify-between' : 'justify-end'"
            class="flex flex-1 items-center md:hidden"
        >
            <Link
                v-if="list.links[0].url"
                :href="list.links[0].url"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                >Anterior</Link
            >
            <Link
                v-if="list.links[list.links.length - 1].url"
                :href="list.links[list.links.length - 1].url"
                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                >Próximo</Link
            >
        </div>
        <div
            class="hidden sm:items-center sm:justify-between md:flex md:flex-1"
        >
            <div>
                <p class="text-sm text-gray-700 dark:text-gray-200">
                    Mostrando de
                    {{ " " }}
                    <span class="font-medium">{{ list.from }}</span>
                    {{ " " }}
                    até
                    {{ " " }}
                    <span class="font-medium">{{ list.to }}</span>
                    {{ " " }}
                    de
                    {{ " " }}
                    <span class="font-medium">{{ list.total }}</span>
                    {{ " " }}
                    resultados
                </p>
            </div>
            <div>
                <nav
                    aria-label="Pagination"
                    class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                >
                    <Link
                        v-for="(link, index) in list.links"
                        :class="getClass(link.active, index, list.links.length)"
                        :disabled="!link.url"
                        :href="link.url"
                        class="inline-flex items-center border px-4 py-2 text-sm font-medium focus:z-20 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
                        v-html="
                            link.label
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
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    list: {
        type: Object,
        default: null,
    },
});

function getClass(active, index, length) {
    let newClass = "bg-white border-gray-300 text-gray-500 hover:bg-gray-50 ";
    if (active) {
        newClass =
            "z-10 border-gray-500 bg-gray-100 text-grey-600 dark:bg-gray-700 dark:text-white";
    }

    if (index === 0) {
        newClass += "rounded-l-md ";
    }

    if (index === length - 1) {
        newClass += "rounded-r-md ";
    }

    return newClass;
}
</script>
