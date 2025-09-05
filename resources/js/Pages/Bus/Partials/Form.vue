<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Checkbox from "@/Components/Checkbox.vue";
import SaveButton from "@/Components/Buttons/SaveButton.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SearchableSelect from "@/Components/SearchableSelect.vue";

defineProps({
    submit: Function,
    form: Object,
    passengers: Object,
    data: {
        type: Object,
        default: null,
    },
});

function priceFormat(price) {
    // Replace all occurrences of '.' with an empty string
    let valueFormatted = price.replace(/\./g, "");

    // Replace ',' with '.'
    valueFormatted = valueFormatted.replace(",", ".");

    return valueFormatted;
}
</script>

<template>
    <form class="space-y-6" @submit.prevent="submit">
        <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800 sm:p-8">
            <section class="max-w-xl space-y-6">
                <fieldset>
                    <InputLabel for="passenger_id" value="Nome" />

                    <SearchableSelect
                        v-model="form.passenger_id"
                        :options="passengers"
                        placeholder="Selecione um passageiro..."
                        search-placeholder="Pesquisar passageiro..."
                        display-key="name"
                        value-key="id"
                        class="mt-1"
                    />

                    <InputError
                        :message="form.errors.passenger_id"
                        class="mt-2"
                    />
                </fieldset>

                <fieldset>
                    <InputLabel value="Dias" />
                    <div class="flex justify-between">
                        <div class="relative flex gap-x-3">
                            <div class="flex h-6 items-center">
                                <Checkbox
                                    id="friday"
                                    v-model:checked="form.friday"
                                    name="friday"
                                    value="1"
                                />
                            </div>
                            <div class="text-sm leading-6">
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    for="friday"
                                >
                                    Sexta
                                </label>
                            </div>
                        </div>
                        <div class="relative flex gap-x-3">
                            <div class="flex h-6 items-center">
                                <Checkbox
                                    id="saturday"
                                    v-model:checked="form.saturday"
                                    name="saturday"
                                    value="1"
                                />
                            </div>
                            <div class="text-sm leading-6">
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    for="saturday"
                                >
                                    Sábado
                                </label>
                            </div>
                        </div>
                        <div class="relative flex gap-x-3">
                            <div class="flex h-6 items-center">
                                <Checkbox
                                    id="sunday"
                                    v-model:checked="form.sunday"
                                    name="sunday"
                                    value="1"
                                />
                            </div>
                            <div class="text-sm leading-6">
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    for="sunday"
                                >
                                    Domingo
                                </label>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <InputLabel for="amount" value="Pago" />

                    <TextInput
                        id="amount"
                        v-model="form.amount"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        type="text"
                    />
                </fieldset>

                <fieldset class="flex gap-4">
                    <div>
                        <InputLabel for="amount" value="A Pagar" />

                        <p
                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{
                                (
                                    (form.friday +
                                        form.saturday +
                                        form.sunday) *
                                    $inertia.page.props.valor_onibus
                                ).toLocaleString("pt-BR", {
                                    style: "currency",
                                    currency: "BRL",
                                })
                            }}
                        </p>
                    </div>

                    <div>
                        <InputLabel for="amount" value="Saldo" />

                        <p
                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{
                                (
                                    priceFormat(form.amount) -
                                    (form.friday +
                                        form.saturday +
                                        form.sunday) *
                                        $inertia.page.props.valor_onibus
                                ).toLocaleString("pt-BR", {
                                    style: "currency",
                                    currency: "BRL",
                                })
                            }}
                        </p>
                    </div>
                </fieldset>

                <fieldset>
                    <InputLabel for="obs" value="Observações" />

                    <textarea
                        id="obs"
                        v-model="form.obs"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:bg-gray-900 dark:text-gray-300 dark:ring-gray-700 dark:focus:border-indigo-600 dark:focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        name="about"
                        rows="3"
                    ></textarea>
                </fieldset>
            </section>
        </div>
        <div class="flex items-center gap-4">
            <PrimaryButton
                :disabled="form.processing"
                :href="route('bus.index')"
                class="flex-1 sm:flex-none"
                type="button"
            >
                Voltar
            </PrimaryButton>
            <SaveButton :disabled="form.processing" class="flex-1 sm:flex-none">
                <p v-if="!form.processing">Salvar</p>
                <p v-else>
                    <font-awesome-icon icon="fa-solid fa-spinner" spin />
                    Aguarde...
                </p>
            </SaveButton>
        </div>
    </form>
</template>
