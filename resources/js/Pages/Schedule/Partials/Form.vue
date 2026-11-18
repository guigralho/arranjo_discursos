<script setup>
import FormField from "@/Components/Ui/FormField.vue";
import Button from "@/Components/Ui/Button.vue";
import { vDatepicker } from "@/composables/vDatepicker";
import { vPhoneMask } from "@/composables/vPhoneMask";

defineProps({
    submit: Function,
    form: Object,
    submitLabel: { type: String, default: "Salvar" },
    cancelHref: { type: String, default: null },
});

const monthPicker = { format: "mm/yyyy", startView: 1, pickLevel: 1 };
</script>

<template>
    <form class="max-w-3xl" novalidate @submit.prevent="submit">
        <div class="divide-y divide-stone-200 dark:divide-stone-800">
            <div aria-labelledby="section-arrangement" role="group" class="grid gap-x-8 gap-y-5 pb-8 md:grid-cols-[12rem_minmax(0,1fr)]">
                <div>
                    <span class="block md:pt-1">
                        <span id="section-arrangement" class="block font-medium text-stone-900 dark:text-stone-100">Arranjo</span>
                        <span class="mt-1 block text-sm text-stone-600 dark:text-stone-400">Mês e congregação com quem trocamos oradores.</span>
                    </span>
                </div>
                <div class="grid gap-5 sm:grid-cols-[10rem_minmax(0,1fr)]">
                    <FormField
                        id="formatted_month"
                        v-slot="{ field }"
                        :error="form.errors.formatted_month"
                        label="Mês"
                        required
                    >
                        <input
                            v-model="form.formatted_month"
                            v-datepicker="monthPicker"
                            autocomplete="off"
                            class="field h-10 tabular-nums"
                            inputmode="numeric"
                            placeholder="mm/aaaa"
                            type="text"
                            v-bind="field"
                        />
                    </FormField>
                    <FormField
                        id="congregation"
                        v-slot="{ field }"
                        :error="form.errors.congregation"
                        label="Congregação"
                        required
                    >
                        <input v-model="form.congregation" autocomplete="off" class="field h-10" type="text" v-bind="field" />
                    </FormField>
                </div>
            </div>

            <div aria-labelledby="section-meeting" role="group" class="grid gap-x-8 gap-y-5 py-8 md:grid-cols-[12rem_minmax(0,1fr)]">
                <div>
                    <span class="block md:pt-1">
                        <span id="section-meeting" class="block font-medium text-stone-900 dark:text-stone-100">Reunião deles</span>
                        <span class="mt-1 block text-sm text-stone-600 dark:text-stone-400">
                            O dia define as datas em que enviamos oradores.
                        </span>
                    </span>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <FormField id="day" v-slot="{ field }" :error="form.errors.day" label="Dia da reunião">
                        <select v-model="form.day" class="field h-10" v-bind="field">
                            <option value="">Selecione…</option>
                            <option value="Sábado">Sábado</option>
                            <option value="Domingo">Domingo</option>
                        </select>
                    </FormField>
                    <FormField id="hour" v-slot="{ field }" :error="form.errors.hour" label="Horário">
                        <input v-model="form.hour" autocomplete="off" class="field h-10" placeholder="ex.: 19h30" type="text" v-bind="field" />
                    </FormField>
                    <FormField id="address" v-slot="{ field }" :error="form.errors.address" class="sm:col-span-2" label="Endereço">
                        <input v-model="form.address" autocomplete="off" class="field h-10" type="text" v-bind="field" />
                    </FormField>
                </div>
            </div>

            <div aria-labelledby="section-contact" role="group" class="grid gap-x-8 gap-y-5 py-8 md:grid-cols-[12rem_minmax(0,1fr)]">
                <div>
                    <span class="block md:pt-1">
                        <span id="section-contact" class="block font-medium text-stone-900 dark:text-stone-100">Contato</span>
                        <span class="mt-1 block text-sm text-stone-600 dark:text-stone-400">Usado na mensagem de WhatsApp para os oradores.</span>
                    </span>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <FormField id="contact" v-slot="{ field }" :error="form.errors.contact" label="Responsável">
                        <input v-model="form.contact" autocomplete="off" class="field h-10" type="text" v-bind="field" />
                    </FormField>
                    <FormField id="phone" v-slot="{ field }" :error="form.errors.phone" label="Telefone">
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
            </div>
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-stone-200 pt-6 dark:border-stone-800 sm:flex-row sm:justify-end">
            <Button v-if="cancelHref" :href="cancelHref" variant="ghost">Cancelar</Button>
            <Button :loading="form.processing" loading-text="Salvando…" type="submit">{{ submitLabel }}</Button>
        </div>
    </form>
</template>
