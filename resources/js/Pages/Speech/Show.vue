<script setup>
import Form from "./Partials/Form.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

const props = defineProps({
    name: String,
    speech: Object,
});

const form = useForm({
    number: props.speech.number,
    theme: props.speech.theme,
});

const submit = () => {
    form.put(route("speeches.update", props.speech.id), {
        preserveScroll: true,
        onSuccess: () => {},
        onError: () => {
            if (form.errors.theme) {
                form.reset("theme");
            }
            if (form.errors.number) {
                form.reset("number");
            }
        },
    });
};
</script>

<template>
    <Head :title="name" />

    <div class="mx-auto max-w-7xl space-y-6 py-6 px-4 sm:px-6 lg:px-8">
        <Form :form="form" :speech="speech" :submit="submit" />

        <div
            v-if="speech.speakers.length > 0"
            class="-mx-4 overflow-x-auto px-4 py-4 sm:-mx-8 sm:px-8"
        >
            <p class="mb-1 text-lg dark:text-gray-100">Oradores</p>
            <div
                class="inline-block min-w-full overflow-hidden rounded-lg shadow"
            >
                <table class="min-w-full table-fixed bg-white dark:bg-gray-800">
                    <thead>
                        <tr
                            class="font-weight-bold border-b text-gray-800 dark:border-gray-900 dark:text-gray-100"
                        >
                            <th
                                class="w-2/12 px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                <p class="flex items-center gap-2">
                                    Privilégio
                                </p>
                            </th>
                            <th
                                class="px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                Nome
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in speech.speakers"
                            :key="item.id"
                            class="font-weight-bold border-b text-gray-800 hover:bg-gray-100 dark:border-gray-900 dark:text-gray-100 dark:hover:bg-gray-700"
                        >
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.privilege }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
