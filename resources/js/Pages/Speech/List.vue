<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import DeleteButton from "@/Components/Buttons/DeleteLink.vue";
import EditButton from "@/Components/Buttons/EditLink.vue";
import TablePaginator from "@/Components/TablePaginator.vue";
import { ref } from "vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextInput from "@/Components/TextInput.vue";
import SortIcons from "@/Components/SortIcons.vue";
import MobileList from "@/Pages/Speech/Partials/MobileList.vue";
import { useDebounceSearch } from "@/composables/useDebounceSearch";

const props = defineProps({
    name: String,
    list: Object,
    search: String,
    filters: Object,
});

const { filters, updateFilter } = useDebounceSearch("/speeches", {
    page: props.filters.page,
    search: props.filters.search,
    hasSpeakers: props.filters.hasSpeakers,
    moreThan2Years: props.filters.moreThan2Years,
    orderDir: props.filters.orderDir,
    orderField: props.filters.orderField,
});

const showModal = ref(false);
const selectedItem = ref({});

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
            class="mb-1 flex w-full flex-col justify-between gap-4 sm:mb-0 md:flex-row"
        >
            <div
                class="flex flex-col gap-4 md:w-full md:flex-row md:items-center"
            >
                <div class="relative">
                    <TextInput
                        v-model="filters.search"
                        autocomplete="off"
                        class="dark:bg-gray-800 dark:text-gray-200"
                        name="search"
                        placeholder="Buscar"
                        type="text"
                    />
                </div>
                <div class="relative flex gap-4">
                    <label class="flex items-center">
                        <Checkbox
                            v-model:checked="filters.hasSpeakers"
                            class="h-5 w-5"
                        />
                        <span
                            class="ml-2 text-lg text-gray-600 dark:text-gray-400"
                        >
                            Já preparado?
                        </span>
                    </label>
                    <label class="flex items-center">
                        <Checkbox
                            v-model:checked="filters.moreThan2Years"
                            class="h-5 w-5"
                        />
                        <span
                            class="ml-2 text-lg text-gray-600 dark:text-gray-400"
                        >
                            Pode ser feito?
                        </span>
                    </label>
                </div>
            </div>
            <Link :href="route('speeches.create')" class="btn-novo">Novo</Link>
        </div>

        <div
            class="-mx-4 overflow-x-auto px-4 py-4 sm:col-span-2 sm:-mx-8 sm:px-8"
        >
            <MobileList
                :list="list"
                :order-dir="filters.orderDir"
                :order-field="filters.orderField"
                @toggle-order="toggleOrder"
            />
            <div
                class="inline-block min-w-full overflow-hidden rounded-lg shadow"
            >
                <table
                    class="hidden min-w-full table-fixed bg-white dark:bg-gray-800 md:block"
                >
                    <thead>
                        <tr
                            class="font-weight-bold border-b text-gray-800 dark:border-gray-900 dark:text-gray-100"
                        >
                            <th
                                class="w-1/12 cursor-pointer px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                                @click="toggleOrder('number')"
                            >
                                <p class="flex items-center gap-2">
                                    Número
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="
                                            filters.orderField === 'number'
                                        "
                                    />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                                @click="toggleOrder('theme')"
                            >
                                <p class="flex items-center gap-2">
                                    Tema
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="
                                            filters.orderField === 'theme'
                                        "
                                    />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                                @click="toggleOrder('speeches.updated_at')"
                            >
                                <p class="flex items-center gap-2">
                                    Atualizado em
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="
                                            filters.orderField ===
                                            'speeches.updated_at'
                                        "
                                    />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                                @click="
                                    toggleOrder('max(receive_speakers.date)')
                                "
                            >
                                <p class="flex items-center gap-2">
                                    Realizado em
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="
                                            orderField ===
                                            'max(receive_speakers.date)'
                                        "
                                    />
                                </p>
                            </th>
                            <th
                                class="w-1/12 px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                            >
                                Preparado?
                            </th>
                            <th
                                v-if="$inertia.page.props.auth.user.id === 1"
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
                            v-if="list.total"
                            :key="item.id"
                            class="font-weight-bold border-b text-gray-800 hover:bg-gray-100 dark:border-gray-900 dark:text-gray-100 dark:hover:bg-gray-700"
                        >
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.number }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.theme }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.updated_at }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.last_made?.date }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.speakers.length > 0 ? "Sim" : "Não" }}
                            </td>
                            <td
                                v-if="$inertia.page.props.auth.user.id === 1"
                                class="space-x-3 whitespace-nowrap px-5 py-5 text-sm"
                            >
                                <EditButton
                                    :href="route('speeches.show', item.id)"
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
                            class="font-weight-bold border-b text-gray-800 dark:border-gray-900 dark:text-gray-100"
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
            :delete-url="`speeches/${selectedItem.id}`"
            :show="showModal"
            :to-delete="`Tema ${selectedItem.number} - ${selectedItem.theme}`"
            @close="showModal = false"
        />
    </div>
</template>
