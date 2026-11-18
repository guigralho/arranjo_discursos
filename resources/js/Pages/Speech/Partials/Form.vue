<script setup>
import FormField from "@/Components/Ui/FormField.vue";
import Button from "@/Components/Ui/Button.vue";

defineProps({
    submit: Function,
    form: Object,
    submitLabel: { type: String, default: "Salvar" },
    cancelHref: { type: String, default: null },
});
</script>

<template>
    <form class="max-w-2xl" novalidate @submit.prevent="submit">
        <div class="grid gap-5 sm:grid-cols-[7rem_minmax(0,1fr)]">
            <FormField id="number" v-slot="{ field }" :error="form.errors.number" label="Número" required>
                <input v-model="form.number" class="field h-10 tabular-nums" inputmode="numeric" min="1" type="number" v-bind="field" />
            </FormField>
            <FormField id="theme" v-slot="{ field }" :error="form.errors.theme" label="Tema" required>
                <input v-model="form.theme" autocomplete="off" class="field h-10" type="text" v-bind="field" />
            </FormField>
        </div>
        <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <Button v-if="cancelHref" :href="cancelHref" variant="ghost">Cancelar</Button>
            <Button :loading="form.processing" loading-text="Salvando…" type="submit">{{ submitLabel }}</Button>
        </div>
    </form>
</template>
