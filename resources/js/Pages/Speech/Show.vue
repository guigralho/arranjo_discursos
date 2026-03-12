<script setup>
import Form from "./Partials/Form.vue";
import { Head, useForm } from "@inertiajs/vue3";
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

    <div class="mx-auto max-w-6xl space-y-6 py-6 px-4 sm:px-6 lg:px-8">
        <Form :form="form" :speech="speech" :submit="submit" />

        <div
            v-if="speech.speakers.length > 0"
            class="py-4"
        >
            <p class="mb-1 text-lg dark:text-white">Oradores</p>
            <div
                class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800"
            >
                <table class="min-w-full">
                    <thead>
                        <tr
                            class="border-b text-stone-800 dark:border-slate-800 dark:text-white"
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
                    <tbody class="divide-y divide-stone-100 dark:divide-slate-800">
                        <tr
                            v-for="item in speech.speakers"
                            :key="item.id"
                            class="text-stone-800 hover:bg-stone-100 dark:text-white dark:hover:bg-slate-800"
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
