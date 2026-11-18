<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import Form from "./Partials/Form.vue";
import PreparedSpeeches from "./Partials/PreparedSpeeches.vue";
import PageHeader from "@/Components/Ui/PageHeader.vue";
import PhoneLink from "@/Components/Ui/PhoneLink.vue";

const props = defineProps({
    name: String,
    speaker: Object,
    mySpeeches: Object,
    speeches: Array,
});

const form = useForm({
    privilege: props.speaker.privilege,
    name: props.speaker.name,
    phone: props.speaker.phone,
});

const submit = () => {
    form.put(route("speakers.update", props.speaker.id), {
        preserveScroll: true,
        onSuccess: () => form.defaults(),
    });
};
</script>

<template>
    <Head :title="speaker.name" />

    <PageHeader :back-href="route('speakers.index')" :eyebrow="speaker.privilege" :title="speaker.name" back-label="Oradores">
        <template v-if="speaker.phone" #meta>
            <PhoneLink :phone="speaker.phone" />
        </template>
    </PageHeader>

    <div class="grid gap-10 lg:grid-cols-[minmax(0,22rem)_minmax(0,1fr)] lg:gap-12">
        <section aria-labelledby="profile-title">
            <h2 id="profile-title" class="mb-4 text-base font-semibold text-stone-900 dark:text-stone-100">Dados do orador</h2>
            <Form :form="form" :submit="submit" />
            <p class="mt-6 text-xs text-stone-500 dark:text-stone-400">
                Criado em {{ speaker.created_at }}<template v-if="speaker.user_created"> por {{ speaker.user_created.name }}</template>.
                <template v-if="speaker.user_updated">Atualizado em {{ speaker.updated_at }} por {{ speaker.user_updated.name }}.</template>
            </p>
        </section>

        <PreparedSpeeches :my-speeches="mySpeeches" :speaker="speaker" :speeches="speeches" />
    </div>
</template>
