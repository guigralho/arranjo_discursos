<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DeleteButton from "@/Components/Buttons/DeleteLink.vue";
import EditButton from "@/Components/Buttons/EditLink.vue";
import TablePaginator from "@/Components/TablePaginator.vue";
import { ref } from "vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import TextInput from "@/Components/TextInput.vue";
import SortIcons from "@/Components/SortIcons.vue";
import MobileList from "@/Pages/Passenger/Partials/MobileList.vue";
import { useDebounceSearch } from "@/composables/useDebounceSearch";

const props = defineProps({
    name: String,
    list: Object,
    search: String,
    filters: Object,
});

const { filters, updateFilter } = useDebounceSearch('/passengers', {
    page: props.filters.page,
    search: props.filters.search,
    orderDir: props.filters.orderDir,
    orderField: props.filters.orderField,
});

const showModal = ref(false);
const selectedItem = ref({});


const toggleOrder = (field) => {
    updateFilter('orderField', field);

    if (filters.value.orderDir === undefined) {
        updateFilter('orderDir', "asc");
    } else if (filters.value.orderDir === "asc") {
        updateFilter('orderDir', "desc");
    } else if (filters.value.orderDir === "desc") {
        updateFilter('orderDir', undefined);
        updateFilter('orderField', "");
    }
};
</script>

<template>
    <Head :title="name" />

    <div class="mx-auto max-w-6xl py-6 px-4 sm:px-6 lg:px-8">
        <div
            class="mb-1 flex w-full flex-col items-center justify-between gap-4 sm:mb-0 md:flex-row"
        >
            <div
                class="flex flex-col gap-4 md:w-full md:flex-row md:items-center"
            >
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
            </div>
            <Link :href="route('passengers.create')" class="btn-novo"
                >Novo
            </Link>
        </div>

        <div
            class="py-4 sm:col-span-2"
        >
            <MobileList
                :list="list"
                :order-dir="filters.orderDir"
                :order-field="orderField"
                @toggle-order="toggleOrder"
            />
            <div
                class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800"
            >
                <table
                    class="hidden min-w-full md:block"
                >
                    <thead>
                        <tr
                            class="border-b text-stone-800 dark:border-slate-800 dark:text-white"
                        >
                            <th
                                class="w-1/12 cursor-pointer px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                                @click="toggleOrder('name')"
                            >
                                <p class="flex items-center gap-2">
                                    Nome
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="filters.orderField === 'name'"
                                    />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                                @click="toggleOrder('doc')"
                            >
                                <p class="flex items-center gap-2">
                                    Documento
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="filters.orderField === 'doc'"
                                    />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                                @click="toggleOrder('phone')"
                            >
                                <p class="flex items-center gap-2">
                                    Telefone
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="filters.orderField === 'phone'"
                                    />
                                </p>
                            </th>
                            <th
                                class="w-1/12 px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-slate-800">
                        <tr
                            v-for="item in list.data"
                            v-if="list.total"
                            :key="item.id"
                            class="text-stone-800 hover:bg-stone-100 dark:text-white dark:hover:bg-slate-800"
                        >
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.name }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.doc }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.phone }}
                            </td>
                            <td
                                class="space-x-3 whitespace-nowrap px-5 py-5 text-sm"
                            >
                                <EditButton
                                    :href="route('passengers.show', item.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-edit"
                                    />
                                </EditButton>
                                <DeleteButton
                                    @click="
                                        () => {
                                            showModal = true;
                                            selectedItem = item;
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
                            class="text-stone-800 dark:text-white"
                        >
                            <td class="px-5 py-3 text-sm" colspan="4">
                                Nenhum registro encontrado!
                            </td>
                        </tr>
                    </tbody>
                </table>

                <TablePaginator :list="list" />
            </div>
        </div>

        <DeleteModal
            :delete-url="`passengers/${selectedItem.id}`"
            :show="showModal"
            :to-delete="selectedItem.name"
            @close="showModal = false"
        />
    </div>
</template>
