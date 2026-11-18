<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import FormField from "@/Components/Ui/FormField.vue";
import Button from "@/Components/Ui/Button.vue";
import Combobox from "@/Components/Ui/Combobox.vue";
import FilterChip from "@/Components/Ui/FilterChip.vue";
import { formatCurrency, parseMoney, vMoneyMask } from "@/composables/useMoney";

const props = defineProps({
    submit: Function,
    form: Object,
    passengers: Array,
    submitLabel: { type: String, default: "Salvar" },
    cancelHref: { type: String, default: null },
});

const page = usePage();
const fare = computed(() => Number(page.props.valor_onibus ?? 0));

const tripCount = computed(() => Number(!!props.form.friday) + Number(!!props.form.saturday) + Number(!!props.form.sunday));
const amountDue = computed(() => tripCount.value * fare.value);
const balance = computed(() => parseMoney(props.form.amount) - amountDue.value);
</script>

<template>
    <form class="max-w-2xl" novalidate @submit.prevent="submit">
        <div class="space-y-6">
            <FormField id="passenger_id" v-slot="{ field }" :error="form.errors.passenger_id" label="Passageiro" required>
                <Combobox
                    :id="field.id"
                    v-model="form.passenger_id"
                    :described-by="field['aria-describedby']"
                    :invalid="!!form.errors.passenger_id"
                    :options="passengers"
                    empty-value=""
                    placeholder="Buscar passageiro"
                />
            </FormField>

            <div aria-labelledby="days-label" role="group">
                <p id="days-label" class="mb-1.5 text-sm font-medium text-stone-800 dark:text-stone-200">Dias de viagem</p>
                <div class="flex flex-wrap gap-2">
                    <FilterChip v-model="form.friday">Sexta</FilterChip>
                    <FilterChip v-model="form.saturday">Sábado</FilterChip>
                    <FilterChip v-model="form.sunday">Domingo</FilterChip>
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-[12rem_minmax(0,1fr)] sm:items-end">
                <FormField id="amount" v-slot="{ field }" :error="form.errors.amount" label="Valor pago">
                    <div class="relative">
                        <span aria-hidden="true" class="pointer-events-none absolute left-3 top-2.5 text-sm text-stone-500">R$</span>
                        <input
                            v-model="form.amount"
                            v-money-mask
                            autocomplete="off"
                            class="field h-10 pl-9 text-right tabular-nums"
                            inputmode="numeric"
                            placeholder="0,00"
                            type="text"
                            v-bind="field"
                        />
                    </div>
                </FormField>
                <dl class="grid grid-cols-2 gap-4 rounded-md bg-stone-100 px-4 py-2.5 text-sm dark:bg-stone-900" aria-live="polite">
                    <div>
                        <dt class="text-xs text-stone-500 dark:text-stone-400">A pagar ({{ tripCount }}× {{ formatCurrency(fare) }})</dt>
                        <dd class="font-medium tabular-nums text-stone-900 dark:text-stone-100">{{ formatCurrency(amountDue) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-stone-500 dark:text-stone-400">Saldo</dt>
                        <dd
                            :class="balance < 0 ? 'text-red-700 dark:text-red-400' : 'text-pine-800 dark:text-pine-300'"
                            class="font-medium tabular-nums"
                        >
                            {{ formatCurrency(balance) }}
                        </dd>
                    </div>
                </dl>
            </div>

            <FormField id="obs" v-slot="{ field }" :error="form.errors.obs" label="Observação">
                <textarea v-model="form.obs" class="field min-h-[5rem] py-2" rows="3" v-bind="field" />
            </FormField>
        </div>

        <div class="mt-8 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <Button v-if="cancelHref" :href="cancelHref" variant="ghost">Cancelar</Button>
            <Button :loading="form.processing" loading-text="Salvando…" type="submit">{{ submitLabel }}</Button>
        </div>
    </form>
</template>
