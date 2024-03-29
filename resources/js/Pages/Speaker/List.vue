<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import DeleteButton from "@/Components/Buttons/DeleteLink.vue";
import EditButton from "@/Components/Buttons/EditLink.vue";
import TablePaginator from "@/Components/TablePaginator.vue";
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import debounce from "lodash/debounce";
import DeleteModal from "@/Components/DeleteModal.vue";
import TextInput from "@/Components/TextInput.vue";
import SortIcons from "@/Components/SortIcons.vue";

let props = defineProps({
    name: String,
    list: Object,
    search: String,
    filters: Object,
});

let page = ref(props.filters.page);
let search = ref(props.filters.search);
let orderDir = ref(props.filters.orderDir);
let orderField = ref(props.filters.orderField);
let showModal = ref(false);
let selectedItem = ref({});
let ids = ref([]);

watch(
    [search, orderDir, orderField],
    debounce(function (
        [valueSearch, valueOrderDir, valueOrderField],
        [oldValSearch]
    ) {
        if (valueSearch !== oldValSearch) {
            page.value = 1;
        }
        Inertia.get(
            "/speakers",
            {
                search: valueSearch,
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
    <Head :title="name" />

    <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
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
                <div class="relative">
                    <a
                        v-if="ids.length > 0"
                        :href="route('speakers.download-speeches', { ids })"
                        class="w-full flex-shrink-0 rounded-lg bg-sky-800 px-4 py-2 text-center text-base font-semibold text-white shadow-md hover:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-800 focus:ring-offset-2 focus:ring-offset-sky-200 md:w-auto"
                        type="button"
                    >
                        Baixar temas
                    </a>
                </div>
            </div>
            <Link :href="route('speakers.create')" class="btn-novo">Novo</Link>
        </div>
        <div class="-mx-4 overflow-x-auto px-4 py-4 sm:-mx-8 sm:px-8">
            <div
                class="inline-block min-w-full overflow-hidden rounded-lg shadow"
            >
                <table class="min-w-full table-fixed bg-white dark:bg-gray-800">
                    <thead>
                        <tr
                            class="font-weight-bold border-b text-gray-800 dark:border-gray-900 dark:text-gray-100"
                        >
                            <th></th>
                            <th
                                class="w-2/12 cursor-pointer px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                                @click="toggleOrder('privilege')"
                            >
                                <p class="flex items-center gap-2">
                                    Privilégio
                                    <SortIcons
                                        :order-dir="orderDir"
                                        :update-icon="
                                            orderField === 'privilege'
                                        "
                                    />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                                @click="toggleOrder('name')"
                            >
                                Nome
                                <SortIcons
                                    :order-dir="orderDir"
                                    :update-icon="orderField === 'name'"
                                />
                            </th>
                            <th
                                class="cursor-pointer px-5 py-5 text-left text-sm uppercase"
                                scope="col"
                                @click="toggleOrder('max(send_speakers.date)')"
                            >
                                Último discurso
                                <SortIcons
                                    :order-dir="orderDir"
                                    :update-icon="
                                        orderField === 'max(send_speakers.date)'
                                    "
                                />
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
                            v-if="list.total"
                            :key="item.id"
                            class="font-weight-bold border-b text-gray-800 hover:bg-gray-100 dark:border-gray-900 dark:text-gray-100 dark:hover:bg-gray-700"
                        >
                            <td class="w-4 p-4">
                                <div class="flex items-center">
                                    <input
                                        id="checkbox-table-1"
                                        v-model="ids"
                                        :value="item.id"
                                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                                        type="checkbox"
                                    />
                                    <label
                                        class="sr-only"
                                        for="checkbox-table-1"
                                        >checkbox</label
                                    >
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.privilege }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.name }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-5 text-sm">
                                {{ item.last_speech_made?.date }}
                            </td>
                            <td
                                class="space-x-3 whitespace-nowrap px-5 py-5 text-sm"
                            >
                                <EditButton
                                    :href="route('speakers.show', item.id)"
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
            :delete-url="`speakers/${selectedItem.id}`"
            :show="showModal"
            :to-delete="selectedItem.name"
            @close="showModal = false"
        />
    </div>
</template>
