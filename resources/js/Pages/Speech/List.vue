<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DeleteButton from "@/Components/Buttons/DeleteLink.vue";
import EditButton from "@/Components/Buttons/EditLink.vue";
import TablePaginator from "@/Components/TablePaginator.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { ref } from "vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextInput from "@/Components/TextInput.vue";
import SortIcons from "@/Components/SortIcons.vue";
import MobileList from "@/Pages/Speech/Partials/MobileList.vue";
import { useDebounceSearch } from "@/composables/useDebounceSearch";
import {
    PencilSquareIcon,
    TrashIcon,
    MagnifyingGlassIcon,
    PlusIcon,
    CheckCircleIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";

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

    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
        <Breadcrumb />

        <!-- Header -->
        <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
            <h1 class="text-2xl font-bold tracking-tight text-stone-900 dark:text-white">
                {{ name }}
            </h1>
            <Link
                v-if="$page.props.auth.user.id === 1"
                :href="route('speeches.create')"
                class="btn-novo"
            >
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
                    placeholder="Buscar discurso..."
                    type="text"
                />
            </div>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 text-sm text-stone-600 dark:text-slate-400">
                    <Checkbox
                        v-model:checked="filters.hasSpeakers"
                        class="h-4 w-4 rounded border-stone-300 text-teal-600 focus:ring-teal-500 dark:border-slate-600 dark:bg-slate-800"
                    />
                    Já preparado?
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-600 dark:text-slate-400">
                    <Checkbox
                        v-model:checked="filters.moreThan2Years"
                        class="h-4 w-4 rounded border-stone-300 text-teal-600 focus:ring-teal-500 dark:border-slate-600 dark:bg-slate-800"
                    />
                    Pode ser feito?
                </label>
            </div>
        </div>

        <!-- Mobile List -->
        <MobileList
            :list="list"
            :order-dir="filters.orderDir"
            :order-field="filters.orderField"
            @toggle-order="toggleOrder"
        />

        <!-- Table card -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800">
            <div class="overflow-x-auto">
                <table class="hidden min-w-full md:table">
                    <thead>
                        <tr class="border-b border-stone-100 dark:border-slate-800">
                            <th
                                class="w-1/12 cursor-pointer px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400"
                                scope="col"
                                @click="toggleOrder('number')"
                            >
                                <p class="flex items-center gap-2">
                                    Número
                                    <SortIcons :order-dir="filters.orderDir" :update-icon="filters.orderField === 'number'" />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400"
                                scope="col"
                                @click="toggleOrder('theme')"
                            >
                                <p class="flex items-center gap-2">
                                    Tema
                                    <SortIcons :order-dir="filters.orderDir" :update-icon="filters.orderField === 'theme'" />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400"
                                scope="col"
                                @click="toggleOrder('speeches.updated_at')"
                            >
                                <p class="flex items-center gap-2">
                                    Atualizado em
                                    <SortIcons :order-dir="filters.orderDir" :update-icon="filters.orderField === 'speeches.updated_at'" />
                                </p>
                            </th>
                            <th
                                class="cursor-pointer px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400"
                                scope="col"
                                @click="toggleOrder('max(receive_speakers.date)')"
                            >
                                <p class="flex items-center gap-2">
                                    Realizado em
                                    <SortIcons :order-dir="filters.orderDir" :update-icon="filters.orderField === 'max(receive_speakers.date)'" />
                                </p>
                            </th>
                            <th class="w-1/12 px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-slate-400" scope="col">
                                Preparado?
                            </th>
                            <th
                                v-if="$page.props.auth.user.id === 1"
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
                            <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-teal-700 dark:text-teal-400">
                                {{ item.number }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-stone-900 dark:text-white">
                                {{ item.theme }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-stone-600 dark:text-slate-300">
                                {{ item.updated_at }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-stone-600 dark:text-slate-300">
                                {{ item.last_made?.date || '-' }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-center">
                                <CheckCircleIcon v-if="item.speakers.length > 0" class="mx-auto h-5 w-5 text-emerald-500" />
                                <XCircleIcon v-else class="mx-auto h-5 w-5 text-stone-300 dark:text-slate-600" />
                            </td>
                            <td
                                v-if="$page.props.auth.user.id === 1"
                                class="whitespace-nowrap px-5 py-4 text-right"
                            >
                                <div class="flex items-center justify-end gap-1.5">
                                    <EditButton :href="route('speeches.show', item.id)">
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
                            <td class="px-5 py-12 text-center text-sm text-stone-500 dark:text-slate-400" colspan="6">
                                Nenhum registro encontrado
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <TablePaginator :list="list" />
        </div>

        <DeleteModal
            :delete-url="`speeches/${selectedItem.id}`"
            :show="showModal"
            :to-delete="`Tema ${selectedItem.number} - ${selectedItem.theme}`"
            @close="showModal = false"
        />
    </div>
</template>
