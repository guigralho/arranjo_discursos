<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import EditButton from "@/Components/Buttons/EditLink.vue";
import { ref } from "vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextInput from "@/Components/TextInput.vue";
import SortIcons from "@/Components/SortIcons.vue";
import MobileList from "@/Pages/Bus/Partials/MobileList.vue";
import DeleteLink from "@/Components/Buttons/DeleteLink.vue";
import DeleteButton from "@/Components/Buttons/DeleteLink.vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import { useDebounceSearch } from "@/composables/useDebounceSearch";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps({
    name: String,
    list: Object,
    totais: Object,
    search: String,
    filters: Object,
});

const { filters, updateFilter } = useDebounceSearch("/bus", {
    page: props.filters.page,
    search: props.filters.search,
    friday: props.filters.friday,
    saturday: props.filters.saturday,
    sunday: props.filters.sunday,
    orderDir: props.filters.orderDir,
    orderField: props.filters.orderField,
});

const deleteUrl = ref("");
const showModal = ref(false);
const selectedItem = ref("");

const pagar =
    (parseInt(props.totais.friday) +
        parseInt(props.totais.saturday) +
        parseInt(props.totais.sunday)) *
    usePage().props.valor_onibus;

const toggleOrder = (field) => {
    updateFilter("orderField", field);

    if (filters.value.orderDir === undefined) {
        updateFilter("orderDir", "asc");
    } else if (filters.value.orderDir === "asc") {
        updateFilter("orderDir", "desc");
    } else if (filters.value.orderDir === "desc") {
        updateFilter("orderDir", undefined);
        updateFilter("orderField", "");
    }
};
</script>

<template>
    <Head :title="name" />
    <div class="mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <Breadcrumb />
        <div
            class="mb-1 flex w-full flex-col items-center justify-between gap-4 sm:mb-0 md:flex-row"
        >
            <div class="flex flex-col gap-4 md:w-full md:flex-row">
                <div class="relative">
                    <TextInput
                        v-model="filters.search"
                        autocomplete="off"
                        class="h-9 dark:bg-slate-900 dark:text-slate-200"
                        name="search"
                        placeholder="Buscar"
                        type="text"
                    />
                </div>
                <div class="flex items-center gap-x-3">
                    <div class="relative flex gap-x-3">
                        <div class="flex h-6 items-center">
                            <Checkbox
                                id="sexta"
                                v-model="filters.friday"
                                name="sexta"
                            />
                        </div>
                        <div class="text-sm leading-6">
                            <label
                                class="text-sm font-medium text-stone-700 dark:text-slate-300"
                                for="sexta"
                            >
                                Sexta
                            </label>
                        </div>
                    </div>
                    <div class="relative flex gap-x-3">
                        <div class="flex h-6 items-center">
                            <Checkbox
                                id="sabado"
                                v-model="filters.saturday"
                                name="sabado"
                            />
                        </div>
                        <div class="text-sm leading-6">
                            <label
                                class="text-sm font-medium text-stone-700 dark:text-slate-300"
                                for="sabado"
                            >
                                Sábado
                            </label>
                        </div>
                    </div>
                    <div class="relative flex gap-x-3">
                        <div class="flex h-6 items-center">
                            <Checkbox
                                id="domingo"
                                v-model="filters.sunday"
                                name="domingo"
                            />
                        </div>
                        <div class="text-sm leading-6">
                            <label
                                class="text-sm font-medium text-stone-700 dark:text-slate-300"
                                for="domingo"
                            >
                                Domingo
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a
                        :href="route('bus.download-list')"
                        class="flex-shrink-0 rounded-2xl bg-teal-600 px-4 py-2 text-center text-base font-semibold text-white shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2 focus:ring-offset-teal-200 md:w-auto"
                        type="button"
                    >
                        Baixar excel
                    </a>
                    <DeleteLink
                        class="!text-center !text-base !font-semibold !tracking-normal"
                        @click="
                            () => {
                                showModal = true;
                                selectedItem = 'TODOS OS REGISTROS!';
                                deleteUrl = `bus/delete-all`;
                            }
                        "
                    >
                        Excluir arranjo
                    </DeleteLink>
                </div>
            </div>
            <Link :href="route('bus.create')" class="btn-novo"> Novo</Link>
            <Link :href="route('passengers.index')" class="btn-novo">
                Passageiros
            </Link>
        </div>
    </div>

    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div>
            <p class="text-stone-800 dark:text-white">Chave pix: 11966615727</p>
        </div>
        <div class="py-4">
            <MobileList
                :list="list"
                :order-dir="filters.orderDir"
                :order-field="orderField"
                :totais="totais"
                @toggle-order="toggleOrder"
            />
            <div
                class="hidden overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800 md:block"
            >
                <table class="min-w-full">
                    <thead>
                        <tr
                            class="border-b text-stone-800 dark:border-slate-800 dark:text-white"
                        >
                            <th
                                class="w-[10%] cursor-pointer px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                                @click="toggleOrder('passengers.name')"
                            >
                                Nome
                                <SortIcons
                                    :order-dir="filters.orderDir"
                                    :update-icon="
                                        filters.orderField === 'passengers.name'
                                    "
                                />
                            </th>
                            <th
                                class="px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                RG
                            </th>
                            <th
                                class="px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                Sex
                            </th>
                            <th
                                class="px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                Sáb
                            </th>
                            <th
                                class="px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                Dom
                            </th>
                            <th
                                class="px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                A pagar
                            </th>
                            <th
                                class="px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                Pago
                            </th>
                            <th
                                class="px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                Obs
                            </th>
                            <th
                                class="px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                Status
                            </th>
                            <th
                                class="px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                Saldo
                            </th>
                            <th
                                class="w-1/12 px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-stone-100 dark:divide-slate-800"
                    >
                        <tr
                            v-for="item in list.data"
                            v-if="list.meta.total"
                            :key="item.id"
                            class="text-stone-800 hover:bg-stone-100 dark:text-white dark:hover:bg-slate-800"
                        >
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.passenger.name }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.passenger.doc }}
                            </td>
                            <td
                                class="whitespace-nowrap px-5 py-5 text-center text-sm"
                            >
                                <span
                                    :class="
                                        item.friday
                                            ? 'text-green-500'
                                            : 'text-red-500'
                                    "
                                    title="Sexta"
                                >
                                    <font-awesome-icon
                                        v-if="item.friday"
                                        icon="fa-solid fa-circle-check"
                                        size="lg"
                                    />
                                    <font-awesome-icon
                                        v-else
                                        icon="fa-solid fa-times-circle"
                                        size="lg"
                                    />
                                </span>
                            </td>
                            <td
                                class="whitespace-nowrap px-5 py-5 text-center text-sm"
                            >
                                <span
                                    :class="
                                        item.saturday
                                            ? 'text-green-500'
                                            : 'text-red-500'
                                    "
                                    title="Sábado"
                                >
                                    <font-awesome-icon
                                        v-if="item.saturday"
                                        icon="fa-solid fa-circle-check"
                                        size="lg"
                                    />
                                    <font-awesome-icon
                                        v-else
                                        icon="fa-solid fa-times-circle"
                                        size="lg"
                                    />
                                </span>
                            </td>
                            <td
                                class="whitespace-nowrap px-5 py-5 text-center text-sm"
                            >
                                <span
                                    :class="
                                        item.sunday
                                            ? 'text-green-500'
                                            : 'text-red-500'
                                    "
                                    title="Domingo"
                                >
                                    <font-awesome-icon
                                        v-if="item.sunday"
                                        icon="fa-solid fa-circle-check"
                                        size="lg"
                                    />
                                    <font-awesome-icon
                                        v-else
                                        icon="fa-solid fa-times-circle"
                                        size="lg"
                                    />
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{
                                    item.total.toLocaleString("pt-BR", {
                                        style: "currency",
                                        currency: "BRL",
                                    })
                                }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{
                                    item.amount.toLocaleString("pt-BR", {
                                        style: "currency",
                                        currency: "BRL",
                                    })
                                }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.obs }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                <span
                                    v-if="item.saldo < 0"
                                    class="rounded-md bg-red-500 p-2"
                                >
                                    Deve
                                </span>
                                <span
                                    v-else
                                    class="rounded-md bg-green-500 p-2"
                                >
                                    Pago
                                </span>
                            </td>
                            <td
                                :class="item.saldo < 0 ? 'text-red-500' : ''"
                                class="whitespace-nowrap px-5 py-5 text-sm"
                            >
                                {{
                                    item.saldo.toLocaleString("pt-BR", {
                                        style: "currency",
                                        currency: "BRL",
                                    })
                                }}
                            </td>
                            <td
                                class="space-x-3 whitespace-nowrap px-5 py-5 text-sm"
                            >
                                <EditButton :href="route('bus.show', item.id)">
                                    <font-awesome-icon
                                        icon="fa-solid fa-edit"
                                    />
                                </EditButton>
                                <DeleteButton
                                    @click="
                                        () => {
                                            showModal = true;
                                            selectedItem = item.passenger.name;
                                            deleteUrl = `bus/${item.id}`;
                                        }
                                    "
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-trash"
                                    />
                                </DeleteButton>
                            </td>
                        </tr>
                        <tr v-else class="text-stone-800 dark:text-white">
                            <td class="px-5 py-3 text-sm" colspan="9">
                                Nenhum registro encontrado!
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr
                            class="border-b text-stone-800 hover:bg-stone-100 dark:border-slate-800 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700"
                        >
                            <td
                                class="whitespace-nowrap px-5 py-5 text-center text-sm"
                            >
                                Total
                            </td>
                            <td></td>
                            <td class="text-center">{{ totais.friday }}</td>
                            <td class="text-center">{{ totais.saturday }}</td>
                            <td class="text-center">{{ totais.sunday }}</td>
                            <td class="px-5 py-5 text-sm">
                                {{
                                    totais.total.toLocaleString("pt-BR", {
                                        style: "currency",
                                        currency: "BRL",
                                    })
                                }}
                            </td>
                            <td class="px-5 py-5 text-sm">
                                {{
                                    totais.amount.toLocaleString("pt-BR", {
                                        style: "currency",
                                        currency: "BRL",
                                    })
                                }}
                            </td>
                            <td></td>
                            <td></td>
                            <td
                                :class="
                                    totais.amount - totais.total < 0
                                        ? 'text-red-500'
                                        : ''
                                "
                                class="px-5 py-5 text-sm"
                            >
                                {{
                                    (
                                        totais.amount - totais.total
                                    ).toLocaleString("pt-BR", {
                                        style: "currency",
                                        currency: "BRL",
                                    })
                                }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <DeleteModal
            :delete-url="deleteUrl"
            :show="showModal"
            :to-delete="selectedItem"
            @close="showModal = false"
        />
    </div>
</template>
