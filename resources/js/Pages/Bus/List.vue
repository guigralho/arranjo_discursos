<script setup>
import { Link, usePage } from "@inertiajs/inertia-vue3";
import EditButton from "@/Components/Buttons/EditLink.vue";
import { ref, watch } from "vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextInput from "@/Components/TextInput.vue";
import SortIcons from "@/Components/SortIcons.vue";
import debounce from "lodash/debounce";
import { Inertia } from "@inertiajs/inertia";
import MobileList from "@/Pages/Bus/Partials/MobileList.vue";
import DeleteLink from "@/Components/Buttons/DeleteLink.vue";
import DeleteButton from "@/Components/Buttons/DeleteLink.vue";
import DeleteModal from "@/Components/DeleteModal.vue";

let props = defineProps({
    name: String,
    list: Object,
    totais: Object,
    search: String,
    filters: Object,
});

let page = ref(props.filters.page);
let search = ref(props.filters.search);
let friday = ref(props.filters.friday);
let saturday = ref(props.filters.saturday);
let sunday = ref(props.filters.sunday);
let orderDir = ref(props.filters.orderDir);
let orderField = ref(props.filters.orderField);
let deleteUrl = ref("");
let showModal = ref(false);
let selectedItem = ref("");

const pagar =
    (parseInt(props.totais.friday) +
        parseInt(props.totais.saturday) +
        parseInt(props.totais.sunday)) *
    usePage().props.value.valor_onibus;

watch(
    [search, friday, saturday, sunday, orderDir, orderField],
    debounce(function (
        [
            valueSearch,
            valueFriday,
            valueSaturday,
            valueSunday,
            valueOrderDir,
            valueOrderField,
        ],
        [oldValSearch]
    ) {
        if (valueSearch !== oldValSearch) {
            page.value = 1;
        }
        Inertia.get(
            "/bus",
            {
                search: valueSearch,
                friday: valueFriday,
                saturday: valueSaturday,
                sunday: valueSunday,
                orderDir: valueOrderDir,
                orderField: valueOrderField,
                page: page.value,
            },
            { preserveState: true, replace: true }
        );
    },
    300)
);

const toggleOrder = (field) => {
    orderField.value = field;

    if (orderDir.value === undefined) {
        orderDir.value = "asc";
    } else if (orderDir.value === "asc") {
        orderDir.value = "desc";
    } else if (orderDir.value === "desc") {
        orderDir.value = undefined;

        orderField.value = "";
    }
};
</script>

<template>
    <div class="mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <div
            class="mb-1 flex w-full flex-col justify-between gap-4 sm:mb-0 md:flex-row"
        >
            <div class="flex flex-col gap-4 md:w-full md:flex-row">
                <div class="relative">
                    <TextInput
                        v-model="search"
                        autocomplete="off"
                        class="dark:bg-gray-800 dark:text-gray-200"
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
                                v-model="friday"
                                name="sexta"
                            />
                        </div>
                        <div class="text-sm leading-6">
                            <label
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
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
                                v-model="saturday"
                                name="sabado"
                            />
                        </div>
                        <div class="text-sm leading-6">
                            <label
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
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
                                v-model="sunday"
                                name="domingo"
                            />
                        </div>
                        <div class="text-sm leading-6">
                            <label
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
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
                        class="flex-shrink-0 rounded-lg bg-sky-800 px-4 py-2 text-center text-base font-semibold text-white shadow-md hover:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-800 focus:ring-offset-2 focus:ring-offset-sky-200 md:w-auto"
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
            <p class="text-gray-800 dark:text-gray-100">
                Chave pix: 11966615727
            </p>
        </div>
        <div class="-mx-4 overflow-x-auto px-4 py-4 sm:-mx-8 sm:px-8">
            <MobileList
                :list="list"
                :order-dir="orderDir"
                :order-field="orderField"
                :totais="totais"
                @toggle-order="toggleOrder"
            />
            <div
                class="inline-block hidden min-w-full overflow-hidden rounded-lg shadow md:block"
            >
                <table class="min-w-full table-fixed bg-white dark:bg-gray-800">
                    <thead>
                        <tr
                            class="font-weight-bold border-b text-gray-800 dark:border-gray-900 dark:text-gray-100"
                        >
                            <th
                                class="w-[10%] cursor-pointer px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                                @click="toggleOrder('passengers.name')"
                            >
                                Nome
                                <SortIcons
                                    :order-dir="orderDir"
                                    :update-icon="
                                        orderField === 'passengers.name'
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
                    <tbody>
                        <tr
                            v-for="item in list.data"
                            v-if="list.meta.total"
                            :key="item.id"
                            class="font-weight-bold border-b text-gray-800 hover:bg-gray-100 dark:border-gray-900 dark:text-gray-100 dark:hover:bg-gray-700"
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
                                    (totais.amount - totais.total).toLocaleString(
                                        "pt-BR",
                                        {
                                            style: "currency",
                                            currency: "BRL",
                                        }
                                    )
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
