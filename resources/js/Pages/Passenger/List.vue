<script setup>
import { computed, ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { PencilSquareIcon, PlusIcon, TrashIcon, UsersIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import PageHeader from "@/Components/Ui/PageHeader.vue";
import Button from "@/Components/Ui/Button.vue";
import IconButton from "@/Components/Ui/IconButton.vue";
import SearchField from "@/Components/Ui/SearchField.vue";
import SortHeader from "@/Components/Ui/SortHeader.vue";
import SortMenu from "@/Components/Ui/SortMenu.vue";
import EmptyState from "@/Components/Ui/EmptyState.vue";
import ConfirmDelete from "@/Components/Ui/ConfirmDelete.vue";
import PhoneLink from "@/Components/Ui/PhoneLink.vue";
import TablePaginator from "@/Components/TablePaginator.vue";
import { useDebounceSearch } from "@/composables/useDebounceSearch";

const props = defineProps({
    name: String,
    list: Object,
    filters: Object,
});

const { filters, isLoading, hasActiveFilters, toggleOrder, setOrder, resetFilters } = useDebounceSearch("/passengers", {
    page: props.filters.page,
    search: props.filters.search,
    orderDir: props.filters.orderDir,
    orderField: props.filters.orderField,
});

const sortOptions = [
    { field: "name", label: "Nome" },
    { field: "doc", label: "Documento" },
];

const passengers = computed(() => props.list.data ?? []);
const passengerToDelete = ref(null);
</script>

<template>
    <Head :title="name" />

    <PageHeader :title="name">
        <template #meta>
            <span>Cadastro de quem pode reservar lugar no ônibus.</span>
        </template>
        <template #actions>
            <Button :href="route('passengers.create')">
                <template #icon><PlusIcon aria-hidden="true" class="h-4 w-4" /></template>
                Novo passageiro
            </Button>
        </template>
    </PageHeader>

    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center">
        <SearchField v-model="filters.search" class="sm:max-w-sm" label="Buscar passageiro" placeholder="Nome, documento ou telefone" />
        <Button v-if="hasActiveFilters" variant="ghost" @click="resetFilters">
            <template #icon><XMarkIcon aria-hidden="true" class="h-4 w-4" /></template>
            Limpar
        </Button>
        <SortMenu
            id="passengers-sort"
            :options="sortOptions"
            :order-dir="filters.orderDir"
            :order-field="filters.orderField"
            class="md:hidden"
            @change="setOrder"
        />
    </div>

    <section
        :aria-busy="isLoading ? 'true' : 'false'"
        :class="isLoading ? 'opacity-60' : ''"
        class="overflow-hidden rounded-lg border border-stone-200 bg-white transition-opacity dark:border-stone-800 dark:bg-stone-900"
    >
        <template v-if="passengers.length">
            <table class="hidden w-full text-sm md:table">
                <thead class="border-b border-stone-200 text-xs font-medium text-stone-500 dark:border-stone-800 dark:text-stone-400">
                    <tr>
                        <SortHeader :order-dir="filters.orderDir" :order-field="filters.orderField" class="px-5 py-2.5" field="name" label="Nome" @sort="toggleOrder" />
                        <SortHeader :order-dir="filters.orderDir" :order-field="filters.orderField" class="w-48 px-3 py-2.5" field="doc" label="Documento" @sort="toggleOrder" />
                        <SortHeader :order-dir="filters.orderDir" :order-field="filters.orderField" class="w-48 px-3 py-2.5" field="phone" label="Telefone" @sort="toggleOrder" />
                        <th class="w-24 px-3 py-2.5" scope="col"><span class="sr-only">Ações</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-stone-800/70">
                    <tr v-for="passenger in passengers" :key="passenger.id" class="hover:bg-stone-50 dark:hover:bg-stone-800/40">
                        <td class="px-5 py-3">
                            <Link :href="route('passengers.show', passenger.id)" class="font-medium text-stone-900 hover:underline dark:text-stone-100">
                                {{ passenger.name }}
                            </Link>
                        </td>
                        <td class="px-3 py-3 tabular-nums text-stone-600 dark:text-stone-400">{{ passenger.doc || "—" }}</td>
                        <td class="px-3 py-3"><PhoneLink :phone="passenger.phone" /></td>
                        <td class="px-3 py-1.5 text-right">
                            <div class="inline-flex">
                                <IconButton :href="route('passengers.show', passenger.id)" :icon="PencilSquareIcon" :label="`Editar ${passenger.name}`" />
                                <IconButton :icon="TrashIcon" :label="`Excluir ${passenger.name}`" tone="danger" @click="passengerToDelete = passenger" />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <ul class="divide-y divide-stone-100 dark:divide-stone-800/70 md:hidden">
                <li v-for="passenger in passengers" :key="passenger.id" class="flex items-center gap-2 py-3 pl-4 pr-2">
                    <Link :href="route('passengers.show', passenger.id)" class="min-w-0 flex-1">
                        <span class="block break-words font-medium text-stone-900 dark:text-stone-100">{{ passenger.name }}</span>
                        <span class="mt-0.5 flex flex-wrap gap-x-3 text-xs tabular-nums text-stone-500 dark:text-stone-400">
                            <span v-if="passenger.doc">{{ passenger.doc }}</span>
                            <span v-if="passenger.phone">{{ passenger.phone }}</span>
                        </span>
                    </Link>
                    <IconButton :icon="TrashIcon" :label="`Excluir ${passenger.name}`" tone="danger" @click="passengerToDelete = passenger" />
                </li>
            </ul>

            <TablePaginator :list="list" />
        </template>

        <EmptyState v-else-if="hasActiveFilters" :icon="UsersIcon" description="Nenhum passageiro corresponde à busca." title="Nada encontrado">
            <Button size="sm" variant="secondary" @click="resetFilters">Limpar busca</Button>
        </EmptyState>
        <EmptyState v-else :icon="UsersIcon" title="Nenhum passageiro ainda">
            <Button :href="route('passengers.create')" size="sm">Novo passageiro</Button>
        </EmptyState>
    </section>

    <ConfirmDelete
        :delete-url="passengerToDelete ? route('passengers.destroy', passengerToDelete.id) : null"
        :show="!!passengerToDelete"
        :to-delete="passengerToDelete?.name"
        title="Excluir passageiro?"
        @close="passengerToDelete = null"
    />
</template>
