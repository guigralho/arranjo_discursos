<script setup>
import FormField from "@/Components/Ui/FormField.vue";
import Button from "@/Components/Ui/Button.vue";
import { vPhoneMask } from "@/composables/vPhoneMask";

defineProps({
    submit: Function,
    form: Object,
    submitLabel: { type: String, default: "Salvar" },
    cancelHref: { type: String, default: null },
});
</script>

<template>
    <form novalidate @submit.prevent="submit">
        <div class="grid gap-5 sm:grid-cols-2">
            <FormField id="name" v-slot="{ field }" :error="form.errors.name" class="sm:col-span-2" label="Nome" required>
                <input v-model="form.name" autocomplete="off" class="field h-10" type="text" v-bind="field" />
            </FormField>
            <FormField id="privilege" v-slot="{ field }" :error="form.errors.privilege" label="Privilégio" required>
                <select v-model="form.privilege" class="field h-10" v-bind="field">
                    <option value="">Selecione…</option>
                    <option value="Ancião">Ancião</option>
                    <option value="Servo">Servo ministerial</option>
                </select>
            </FormField>
            <FormField id="phone" v-slot="{ field }" :error="form.errors.phone" hint="Usado para enviar o convite por WhatsApp." label="Telefone">
                <input
                    v-model="form.phone"
                    v-phone-mask
                    autocomplete="off"
                    class="field h-10 tabular-nums"
                    inputmode="tel"
                    placeholder="(00) 00000-0000"
                    type="tel"
                    v-bind="field"
                />
            </FormField>
        </div>
        <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <Button v-if="cancelHref" :href="cancelHref" variant="ghost">Cancelar</Button>
            <Button :loading="form.processing" loading-text="Salvando…" type="submit">{{ submitLabel }}</Button>
        </div>
    </form>
</template>
