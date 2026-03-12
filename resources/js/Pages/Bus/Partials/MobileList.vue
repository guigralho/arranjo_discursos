<script setup>
import CheckIcon from "@/Components/CheckIcon.vue";
import DeleteLink from "@/Components/Buttons/DeleteLink.vue";
import EditLink from "@/Components/Buttons/EditLink.vue";
import { ref } from "vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    list: Object,
    totais: Object,
    orderField: String,
    orderDir: String,
});

const pagar =
    (parseInt(props.totais.friday) +
        parseInt(props.totais.saturday) +
        parseInt(props.totais.sunday)) *
    usePage().props.valor_onibus;

let showModal = ref(false);
let selectedItem = ref({});
</script>

<template>
    <div class="block md:hidden">
        <div class="mb-4 min-w-full rounded-2xl shadow-sm dark:bg-slate-900">
            <div
                class="w-full rounded-2xl bg-white dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="flow-root">
                    <ul
                        v-if="list.meta.total"
                        class="divide-y divide-stone-200 dark:divide-slate-800"
                        role="list"
                    >
                        <li class="p-4 sm:p-4">
                            <div class="flex flex-col gap-2">
                                <div class="flex justify-between">
                                    <p
                                        class="inline-flex items-center text-base font-semibold text-stone-900 dark:text-white"
                                    >
                                        Sexta: {{ totais.friday }}
                                    </p>
                                    <p
                                        class="inline-flex items-center text-base font-semibold text-stone-900 dark:text-white"
                                    >
                                        Sábado: {{ totais.saturday }}
                                    </p>
                                    <p
                                        class="inline-flex items-center text-base font-semibold text-stone-900 dark:text-white"
                                    >
                                        Domingo: {{ totais.sunday }}
                                    </p>
                                </div>
                                <div class="flex justify-between">
                                    <p
                                        class="inline-flex items-center text-base font-semibold text-stone-900 dark:text-white"
                                    >
                                        A pagar:
                                        {{
                                            totais.total.toLocaleString("pt-BR", {
                                                style: "currency",
                                                currency: "BRL",
                                            })
                                        }}
                                    </p>
                                    <p
                                        class="inline-flex items-center text-base font-semibold text-stone-900 dark:text-white"
                                    >
                                        Pago:
                                        {{
                                            totais.amount.toLocaleString(
                                                "pt-BR",
                                                {
                                                    style: "currency",
                                                    currency: "BRL",
                                                }
                                            )
                                        }}
                                    </p>
                                    <p
                                        :class="
                                            totais.amount - totais.total < 0
                                                ? 'text-red-500'
                                                : 'text-stone-900 dark:text-white'
                                        "
                                        class="inline-flex items-center text-base font-semibold"
                                    >
                                        Saldo:
                                        {{
                                            (
                                                totais.amount - totais.total
                                            ).toLocaleString("pt-BR", {
                                                style: "currency",
                                                currency: "BRL",
                                            })
                                        }}
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li
                            v-for="item in list.data"
                            :key="item.id"
                            class="p-4 sm:p-4"
                        >
                            <div class="flex flex-col gap-2">
                                <p
                                    class="inline-flex items-center text-base font-semibold text-stone-900 dark:text-white"
                                >
                                    {{ item.passenger.name }} -
                                    {{ item.passenger.doc }}
                                </p>

                                <div class="flex gap-4">
                                    <CheckIcon :checked="item.friday > 0" />
                                    <CheckIcon :checked="item.saturday > 0" />
                                    <CheckIcon :checked="item.sunday > 0" />
                                </div>

                                <div
                                    class="flex flex-wrap justify-between gap-4"
                                >
                                    <p
                                        class="inline-flex items-center text-base font-semibold text-stone-900 dark:text-white"
                                    >
                                        A pagar:
                                        {{
                                            item.total.toLocaleString("pt-BR", {
                                                style: "currency",
                                                currency: "BRL",
                                            })
                                        }}
                                    </p>

                                    <p
                                        class="inline-flex items-center text-base font-semibold text-stone-900 dark:text-white"
                                    >
                                        Pago:
                                        {{
                                            item.amount.toLocaleString(
                                                "pt-BR",
                                                {
                                                    style: "currency",
                                                    currency: "BRL",
                                                }
                                            )
                                        }}
                                    </p>

                                    <p
                                        class="inline-flex items-center text-base font-semibold text-stone-900 dark:text-white"
                                    >
                                        Saldo:
                                        {{
                                            item.saldo.toLocaleString("pt-BR", {
                                                style: "currency",
                                                currency: "BRL",
                                            })
                                        }}
                                    </p>

                                    <div>
                                        <span
                                            v-if="item.saldo < 0"
                                            class="flex-1 rounded-md bg-red-500 py-1 px-3"
                                        >
                                            Deve
                                        </span>
                                        <span
                                            v-else
                                            class="flex-grow-0 rounded-md bg-green-500 py-1 px-3"
                                        >
                                            Pago
                                        </span>
                                    </div>
                                </div>

                                <p
                                    class="inline-flex items-center text-base text-sm font-semibold italic text-stone-900 dark:text-white"
                                >
                                    {{ item.obs }}
                                </p>

                                <div class="mt-3 flex gap-3">
                                    <EditLink
                                        :href="route('bus.show', item.id)"
                                        class="grow"
                                    >
                                        Detalhes
                                    </EditLink>
                                    <DeleteLink
                                        class="grow"
                                        @click="
                                            () => {
                                                showModal = true;
                                                selectedItem = item;
                                            }
                                        "
                                    >
                                        Excluir
                                    </DeleteLink>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div v-else class="p-4 text-center sm:p-4">
                        <p>Nenhum registro encontrado!</p>
                    </div>
                </div>
            </div>
        </div>

        <DeleteModal
            :delete-url="`bus/${selectedItem.id}`"
            :show="showModal"
            :to-delete="selectedItem.passenger?.name"
            @close="showModal = false"
        />
    </div>
</template>
