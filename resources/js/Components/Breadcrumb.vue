<template>
    <header
        v-if="breadcrumbs.length > 0"
        class="bg-white shadow dark:bg-gray-800"
    >
        <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
            <nav class="w-full rounded-md">
                <ol class="list-reset flex">
                    <li v-for="page in breadcrumbs">
                        <div>
                            <span v-if="page === '/'" class="mx-2 text-gray-500"
                                >/</span
                            >
                            <Link
                                v-else
                                :class="{ 'font-bold': page.current }"
                                :href="page.url"
                                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                                >{{ page.title }}</Link
                            >
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </header>
</template>

<script setup>
import { usePage } from "@inertiajs/inertia-vue3";
import { computed } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

// Insert an element between all elements, insertBetween([1, 2, 3], '/') results in [1, '/', 2, '/', 3]
const insertBetween = (items, insertion) => {
    return items.flatMap((value, index, array) =>
        array.length - 1 !== index ? [value, insertion] : value
    );
};

const breadcrumbs = computed(() =>
    insertBetween(usePage().props.value.breadcrumbs || [], "/")
);
</script>
