<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { UserGroupIcon } from "@heroicons/vue/20/solid";
import Form from "./Partials/Form.vue";
import PageHeader from "@/Components/Ui/PageHeader.vue";
import EmptyState from "@/Components/Ui/EmptyState.vue";

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
        onSuccess: () => form.defaults(),
    });
};
</script>

<template>
    <Head :title="`Discurso ${speech.number}`" />

    <PageHeader :back-href="route('speeches.index')" :eyebrow="`Discurso nº ${speech.number}`" :title="speech.theme" back-label="Discursos" />

    <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <section aria-labelledby="edit-title">
            <h2 id="edit-title" class="mb-4 text-base font-semibold text-stone-900 dark:text-stone-100">Dados do discurso</h2>
            <Form :form="form" :submit="submit" />
            <p class="mt-6 text-xs text-stone-500 dark:text-stone-400">
                Criado em {{ speech.created_at }}<template v-if="speech.user_created"> por {{ speech.user_created.name }}</template>.
                <template v-if="speech.user_updated">Atualizado em {{ speech.updated_at }} por {{ speech.user_updated.name }}.</template>
            </p>
        </section>

        <section aria-labelledby="speakers-title">
            <h2 id="speakers-title" class="mb-4 flex items-baseline justify-between text-base font-semibold text-stone-900 dark:text-stone-100">
                Quem já preparou
                <span class="text-sm font-normal tabular-nums text-stone-500">{{ speech.speakers.length }}</span>
            </h2>
            <ul
                v-if="speech.speakers.length"
                class="divide-y divide-stone-100 rounded-lg border border-stone-200 bg-white dark:divide-stone-800 dark:border-stone-800 dark:bg-stone-900"
            >
                <li v-for="speaker in speech.speakers" :key="speaker.id">
                    <Link
                        :href="route('speakers.show', speaker.id)"
                        class="flex items-center justify-between gap-3 px-4 py-3 text-sm hover:bg-stone-50 dark:hover:bg-stone-800/50"
                    >
                        <span class="min-w-0 truncate font-medium text-stone-900 dark:text-stone-100">{{ speaker.name }}</span>
                        <span class="shrink-0 text-xs text-stone-500 dark:text-stone-400">{{ speaker.privilege }}</span>
                    </Link>
                </li>
            </ul>
            <div v-else class="rounded-lg border border-dashed border-stone-300 dark:border-stone-700">
                <EmptyState
                    :icon="UserGroupIcon"
                    compact
                    description="Adicione este tema na ficha de um orador."
                    title="Nenhum orador preparou"
                />
            </div>
        </section>
    </div>
</template>
