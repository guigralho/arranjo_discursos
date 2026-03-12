<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DeleteButton from "@/Components/Buttons/DeleteLink.vue";
import EditButton from "@/Components/Buttons/EditLink.vue";
import TablePaginator from "@/Components/TablePaginator.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { onMounted, ref } from "vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import TextInput from "@/Components/TextInput.vue";
import SortIcons from "@/Components/SortIcons.vue";
import Datepicker from "flowbite-datepicker/Datepicker";
import { useDebounceSearch } from "@/composables/useDebounceSearch";
import {
    CalendarIcon,
    MagnifyingGlassIcon,
    PencilSquareIcon,
    PlusIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    name: String,
    list: Object,
    search: String,
    filters: Object,
});

const { filters, updateFilter } = useDebounceSearch("/schedules", {
    page: props.filters.page,
    search: props.filters.search,
    searchDate: props.filters.searchDate,
    orderDir: props.filters.orderDir,
    orderField: props.filters.orderField,
});

const showModal = ref(false);
const selectedItem = ref({});

onMounted(() => {
    Datepicker.locales.pt = {
        monthsShort: [
            "Jan",
            "Fev",
            "Mar",
            "Abr",
            "Mai",
            "Jun",
            "Jul",
            "Ago",
            "Set",
            "Out",
            "Nov",
            "Dez",
        ],
    };

    new Datepicker(document.getElementById("datepickerId"), {
        format: "dd/mm/yyyy",
        startView: 1,
        pickLevel: 1,
        autohide: true,
        language: "pt",
    });
});

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

const changeVal = (val) => {
    updateFilter("searchDate", val);
};
</script>

<template>
    <Head :title="name" />

    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
        <Breadcrumb />

        <!-- Header -->
        <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
            <h1
                class="text-2xl font-bold tracking-tight text-stone-900 dark:text-white"
            >
                {{ name }}
            </h1>
            <Link :href="route('schedules.create')" class="btn-novo">
                <PlusIcon class="h-4 w-4" />
                Novo
            </Link>
        </div>

        <!-- Filters -->
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1 sm:max-w-xs">
                <MagnifyingGlassIcon
                    class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400 dark:text-slate-500"
                />
                <TextInput
                    v-model="filters.search"
                    autocomplete="off"
                    class="h-10 pl-9"
                    name="search"
                    placeholder="Buscar arranjo..."
                    type="text"
                />
            </div>
            <div class="relative sm:max-w-[180px]">
                <CalendarIcon
                    class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400 dark:text-slate-500"
                />
                <TextInput
                    id="datepickerId"
                    v-model="filters.searchDate"
                    autocomplete="off"
                    class="h-10 pl-9"
                    name="searchDate"
                    placeholder="Filtrar data..."
                    type="text"
                    @focusout="changeVal($event.target.value)"
                />
            </div>
        </div>

        <!-- Table card -->
        <div
            class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr
                            class="border-b border-stone-100 dark:border-slate-800"
                        >
                            <th
                                class="w-2/12 cursor-pointer px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400"
                                scope="col"
                                @click="toggleOrder('month')"
                            >
                                <p class="flex items-center gap-2">
                                    Data
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="
                                            filters.orderField === 'month'
                                        "
                                    />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400"
                                scope="col"
                                @click="toggleOrder('congregation')"
                            >
                                <p class="flex items-center gap-2">
                                    Congregação
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="
                                            filters.orderField ===
                                            'congregation'
                                        "
                                    />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400"
                                scope="col"
                                @click="toggleOrder('day')"
                            >
                                <p class="flex items-center gap-2">
                                    Dia
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="
                                            filters.orderField === 'day'
                                        "
                                    />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400"
                                scope="col"
                                @click="toggleOrder('hour')"
                            >
                                <p class="flex items-center gap-2">
                                    Hora
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="
                                            filters.orderField === 'hour'
                                        "
                                    />
                                </p>
                            </th>
                            <th
                                class="w-1/12 px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400"
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
                            v-if="list.total"
                            :key="item.id"
                            class="transition-colors hover:bg-stone-50 dark:hover:bg-slate-800/50"
                        >
                            <td
                                class="whitespace-nowrap px-5 py-4 text-sm font-medium text-stone-900 dark:text-white"
                            >
                                {{ item.translated_month }}
                            </td>
                            <td
                                class="whitespace-nowrap px-5 py-4 text-sm text-stone-600 dark:text-slate-300"
                            >
                                {{ item.congregation }}
                            </td>
                            <td
                                class="whitespace-nowrap px-5 py-4 text-sm text-stone-600 dark:text-slate-300"
                            >
                                {{ item.day }}
                            </td>
                            <td
                                class="whitespace-nowrap px-5 py-4 text-sm text-stone-600 dark:text-slate-300"
                            >
                                {{ item.hour }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-1.5"
                                >
                                    <EditButton
                                        :href="route('schedules.show', item.id)"
                                    >
                                        <PencilSquareIcon class="h-4 w-4" />
                                    </EditButton>
                                    <DeleteButton
                                        @click="
                                            () => {
                                                showModal = true;
                                                selectedItem = item;
                                            }
                                        "
                                    >
                                        <TrashIcon class="h-4 w-4" />
                                    </DeleteButton>
                                </div>
                            </td>
                        </tr>
                        <tr v-else>
                            <td
                                class="px-5 py-12 text-center text-sm text-stone-500 dark:text-slate-400"
                                colspan="5"
                            >
                                Nenhum registro encontrado
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <TablePaginator :list="list" />
        </div>

        <DeleteModal
            :delete-url="`schedules/${selectedItem.id}`"
            :show="showModal"
            :to-delete="`${selectedItem.translated_month} - ${selectedItem.congregation}`"
            @close="showModal = false"
        />
    </div>
</template>
