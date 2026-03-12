<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DeleteButton from "@/Components/Buttons/DeleteLink.vue";
import EditButton from "@/Components/Buttons/EditLink.vue";
import TablePaginator from "@/Components/TablePaginator.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { ref } from "vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import TextInput from "@/Components/TextInput.vue";
import SortIcons from "@/Components/SortIcons.vue";
import { useDebounceSearch } from "@/composables/useDebounceSearch";
import { usePersistedSelection } from "@/composables/usePersistedSelection";
import {
    PencilSquareIcon,
    TrashIcon,
    MagnifyingGlassIcon,
    ArrowDownTrayIcon,
    XCircleIcon,
    PlusIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    name: String,
    list: Object,
    search: String,
    filters: Object,
});

const { filters, updateFilter } = useDebounceSearch("/speakers", {
    page: props.filters.page,
    search: props.filters.search,
    orderDir: props.filters.orderDir,
    orderField: props.filters.orderField,
});

const showModal = ref(false);
const selectedItem = ref({});
const { selectedIds, clearSelection } =
    usePersistedSelection("speakers-selection");

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

    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
        <Breadcrumb />

        <!-- Header -->
        <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
            <h1 class="text-2xl font-bold tracking-tight text-stone-900 dark:text-white">
                {{ name }}
            </h1>
            <Link :href="route('speakers.create')" class="btn-novo">
                <PlusIcon class="h-4 w-4" />
                Novo
            </Link>
        </div>

        <!-- Filters -->
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1 sm:max-w-xs">
                <MagnifyingGlassIcon class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400 dark:text-slate-500" />
                <TextInput
                    v-model="filters.search"
                    autocomplete="off"
                    class="h-10 pl-9"
                    name="search"
                    placeholder="Buscar orador..."
                    type="text"
                />
            </div>
            <div v-if="selectedIds.length > 0" class="flex gap-2">
                <a
                    :href="route('speakers.download-speeches', { ids: selectedIds })"
                    class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600"
                    type="button"
                >
                    <ArrowDownTrayIcon class="h-4 w-4" />
                    Baixar temas
                </a>
                <button
                    class="inline-flex items-center gap-2 rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-semibold text-stone-700 transition-all hover:bg-stone-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                    type="button"
                    @click="clearSelection"
                >
                    <XCircleIcon class="h-4 w-4" />
                    Limpar
                </button>
            </div>
        </div>

        <!-- Table card -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-stone-100 dark:border-slate-800">
                            <th class="w-10 px-4 py-3.5"></th>
                            <th
                                class="w-2/12 cursor-pointer px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400"
                                scope="col"
                                @click="toggleOrder('privilege')"
                            >
                                <p class="flex items-center gap-2">
                                    Privilégio
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="filters.orderField === 'privilege'"
                                    />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400"
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
                                class="cursor-pointer px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400"
                                scope="col"
                                @click="toggleOrder('max(send_speakers.date)')"
                            >
                                <p class="flex items-center gap-2">
                                    Último discurso
                                    <SortIcons
                                        :order-dir="filters.orderDir"
                                        :update-icon="filters.orderField === 'max(send_speakers.date)'"
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
                    <tbody class="divide-y divide-stone-100 dark:divide-slate-800">
                        <tr
                            v-for="item in list.data"
                            v-if="list.total"
                            :key="item.id"
                            class="transition-colors hover:bg-stone-50 dark:hover:bg-slate-800/50"
                        >
                            <td class="w-10 px-4 py-4">
                                <input
                                    v-model="selectedIds"
                                    :value="item.id"
                                    class="h-4 w-4 rounded border-stone-300 text-teal-600 transition-colors focus:ring-2 focus:ring-teal-500 dark:border-slate-600 dark:bg-slate-800 dark:checked:bg-teal-500 dark:focus:ring-teal-500"
                                    type="checkbox"
                                />
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-stone-600 dark:text-slate-300">
                                <span class="inline-flex rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-medium text-stone-700 dark:bg-slate-800 dark:text-slate-300">
                                    {{ item.privilege }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-stone-900 dark:text-white">
                                {{ item.name }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-stone-600 dark:text-slate-300">
                                {{ item.last_speech_made?.date || '-' }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <EditButton :href="route('speakers.show', item.id)">
                                        <PencilSquareIcon class="h-4 w-4" />
                                    </EditButton>
                                    <DeleteButton
                                        @click="() => {
                                            showModal = true;
                                            selectedItem = item;
                                        }"
                                    >
                                        <TrashIcon class="h-4 w-4" />
                                    </DeleteButton>
                                </div>
                            </td>
                        </tr>
                        <tr v-else>
                            <td class="px-5 py-12 text-center text-sm text-stone-500 dark:text-slate-400" colspan="5">
                                Nenhum registro encontrado
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <TablePaginator :list="list" />
        </div>

        <DeleteModal
            :delete-url="`speakers/${selectedItem.id}`"
            :show="showModal"
            :to-delete="selectedItem.name"
            @close="showModal = false"
        />
    </div>
</template>
