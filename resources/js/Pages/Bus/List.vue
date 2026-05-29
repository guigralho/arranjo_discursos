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

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div
            class="mb-4 flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between"
        >
            <div
                class="flex w-full flex-col gap-3 sm:flex-row sm:items-center md:flex-1"
            >
                <div class="w-full sm:max-w-xs">
                    <TextInput
                        v-model="filters.search"
                        autocomplete="off"
                        class="h-9 dark:bg-gray-800 dark:text-gray-200"
                        name="search"
                        placeholder="Buscar"
                        type="text"
                    />
                </div>
                <div class="flex flex-wrap gap-x-4 gap-y-2">
                    <label class="flex cursor-pointer items-center gap-2">
                        <Checkbox
                            id="sexta"
                            v-model="filters.friday"
                            name="sexta"
                        />
                        <span
                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Sexta</span
                        >
                    </label>
                    <label class="flex cursor-pointer items-center gap-2">
                        <Checkbox
                            id="sabado"
                            v-model="filters.saturday"
                            name="sabado"
                        />
                        <span
                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Sábado</span
                        >
                    </label>
                    <label class="flex cursor-pointer items-center gap-2">
                        <Checkbox
                            id="domingo"
                            v-model="filters.sunday"
                            name="domingo"
                        />
                        <span
                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Domingo</span
                        >
                    </label>
                </div>
            </div>
            <div
                class="grid w-full grid-cols-2 gap-2 sm:flex sm:w-auto sm:flex-wrap"
            >
                <a :href="route('bus.download-list')" class="btn-novo">
                    Baixar excel
                </a>
                <button
                    class="btn-novo-danger"
                    type="button"
                    @click="
                        () => {
                            showModal = true;
                            selectedItem = 'TODOS OS REGISTROS!';
                            deleteUrl = 'bus/delete-all';
                        }
                    "
                >
                    Excluir arranjo
                </button>
                <Link :href="route('bus.create')" class="btn-novo">Novo</Link>
                <Link :href="route('passengers.index')" class="btn-novo"
                    >Passageiros</Link
                >
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div>
            <p class="text-gray-800 dark:text-gray-100">
                Chave pix: 11966615727
            </p>
        </div>
        <div class="-mx-4 overflow-x-auto px-4 py-4 sm:-mx-8 sm:px-8">
            <MobileList
                :list="list"
                :order-dir="filters.orderDir"
                :order-field="orderField"
                :totais="totais"
                @toggle-order="toggleOrder"
            />
            <div
                class="inline-block min-w-full overflow-hidden rounded-lg shadow"
            >
                <table
                    class="hidden min-w-full table-fixed bg-white dark:bg-gray-800 md:table"
                >
                    <thead>
                        <tr
                            class="font-weight-bold border-b text-gray-800 dark:border-gray-900 dark:text-gray-100"
                        >
                            <th
                                class="cursor-pointer px-3 py-5 text-left text-sm uppercase"
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
                                class="px-3 py-5 text-left text-sm uppercase"
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
                    <tbody>
                        <tr
                            v-for="item in list.data"
                            v-if="list.meta.total"
                            :key="item.id"
                            class="font-weight-bold border-b text-gray-800 hover:bg-gray-100 dark:border-gray-900 dark:text-gray-100 dark:hover:bg-gray-700"
                        >
                            <td class="break-words px-3 py-5 text-sm">
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
                            <td class="break-words px-3 py-5 text-sm">
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
                        <tr
                            v-else
                            class="font-weight-bold border-b text-gray-800 dark:border-gray-900 dark:text-gray-100"
                        >
                            <td class="px-5 py-3 text-sm" colspan="9">
                                Nenhum registro encontrado!
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr
                            class="font-weight-bold border-b text-gray-800 hover:bg-gray-100 dark:border-gray-900 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600"
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
