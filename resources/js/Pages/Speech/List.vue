<script setup>
import { computed, ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { BookOpenIcon, ChevronRightIcon, PencilSquareIcon, PlusIcon, TrashIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import PageHeader from "@/Components/Ui/PageHeader.vue";
import Button from "@/Components/Ui/Button.vue";
import IconButton from "@/Components/Ui/IconButton.vue";
import SearchField from "@/Components/Ui/SearchField.vue";
import FilterChip from "@/Components/Ui/FilterChip.vue";
import SortHeader from "@/Components/Ui/SortHeader.vue";
import SortMenu from "@/Components/Ui/SortMenu.vue";
import EmptyState from "@/Components/Ui/EmptyState.vue";
import ConfirmDelete from "@/Components/Ui/ConfirmDelete.vue";
import TablePaginator from "@/Components/TablePaginator.vue";
import { useDebounceSearch } from "@/composables/useDebounceSearch";
import { useAccess } from "@/composables/useAccess";

const props = defineProps({
    name: String,
    list: Object,
    filters: Object,
});

const { isAdmin } = useAccess();

const { filters, isLoading, hasActiveFilters, toggleOrder, setOrder, resetFilters } = useDebounceSearch("/speeches", {
    page: props.filters.page,
    search: props.filters.search,
    hasSpeakers: props.filters.hasSpeakers,
    moreThan2Years: props.filters.moreThan2Years,
    orderDir: props.filters.orderDir,
    orderField: props.filters.orderField,
});

const LAST_MADE_FIELD = "max(receive_speakers.date)";

const sortOptions = [
    { field: "number", label: "Número", ascLabel: "(crescente)", descLabel: "(decrescente)" },
    { field: "theme", label: "Tema" },
    { field: LAST_MADE_FIELD, label: "Última vez", ascLabel: "(mais antigo)", descLabel: "(mais recente)" },
];

const speeches = computed(() => props.list.data ?? []);

const preparedLabel = (speech) => {
    const count = speech.speakers?.length ?? 0;
    if (count === 0) {
        return null;
    }
    return count === 1 ? "1 orador" : `${count} oradores`;
};

const speechToDelete = ref(null);
</script>

<template>
    <Head :title="name" />

    <PageHeader :title="name">
        <template #meta>
            <span>Esboços de discursos públicos, com quem já preparou cada um e quando foi feito pela última vez.</span>
        </template>
        <template v-if="isAdmin" #actions>
            <Button :href="route('speeches.create')">
                <template #icon><PlusIcon aria-hidden="true" class="h-4 w-4" /></template>
                Novo discurso
            </Button>
        </template>
    </PageHeader>

    <div class="mb-4 flex flex-col gap-2 lg:flex-row lg:items-center">
        <SearchField v-model="filters.search" class="lg:max-w-sm" label="Buscar por número ou tema" placeholder="Número ou tema" />
        <div class="-mx-4 flex gap-2 overflow-x-auto px-4 sm:mx-0 sm:px-0">
            <FilterChip v-model="filters.hasSpeakers">Já preparados</FilterChip>
            <FilterChip v-model="filters.moreThan2Years" title="Nunca feitos ou feitos há mais de 2 anos">Disponíveis</FilterChip>
            <Button v-if="hasActiveFilters" variant="ghost" @click="resetFilters">
                <template #icon><XMarkIcon aria-hidden="true" class="h-4 w-4" /></template>
                Limpar
            </Button>
        </div>
        <SortMenu
            id="speeches-sort"
            :options="sortOptions"
            :order-dir="filters.orderDir"
            :order-field="filters.orderField"
            class="md:hidden"
            @change="setOrder"
        />
    </div>
    <p v-if="filters.moreThan2Years" class="-mt-1 mb-4 text-xs text-stone-500 dark:text-stone-400">
        Mostrando discursos nunca feitos aqui ou feitos há mais de 2 anos.
    </p>

    <section
        :aria-busy="isLoading ? 'true' : 'false'"
        :class="isLoading ? 'opacity-60' : ''"
        class="overflow-hidden rounded-lg border border-stone-200 bg-white transition-opacity dark:border-stone-800 dark:bg-stone-900"
    >
        <template v-if="speeches.length">
            <table class="hidden w-full text-sm md:table">
                <thead class="border-b border-stone-200 text-xs font-medium text-stone-500 dark:border-stone-800 dark:text-stone-400">
                    <tr>
                        <SortHeader
                            :order-dir="filters.orderDir"
                            :order-field="filters.orderField"
                            align="right"
                            class="w-20 py-2.5 pl-5 pr-3"
                            field="number"
                            label="Nº"
                            @sort="toggleOrder"
                        />
                        <SortHeader
                            :order-dir="filters.orderDir"
                            :order-field="filters.orderField"
                            class="px-3 py-2.5"
                            field="theme"
                            label="Tema"
                            @sort="toggleOrder"
                        />
                        <SortHeader
                            :order-dir="filters.orderDir"
                            :order-field="filters.orderField"
                            :field="LAST_MADE_FIELD"
                            class="w-36 px-3 py-2.5"
                            label="Última vez"
                            @sort="toggleOrder"
                        />
                        <th class="w-36 px-3 py-2.5 text-left" scope="col">Preparado por</th>
                        <th v-if="isAdmin" class="w-24 px-3 py-2.5" scope="col"><span class="sr-only">Ações</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-stone-800/70">
                    <tr v-for="speech in speeches" :key="speech.id" class="hover:bg-stone-50 dark:hover:bg-stone-800/40">
                        <td class="py-3.5 pl-5 pr-3 text-right align-top font-display text-lg font-medium leading-6 tabular-nums text-stone-900 dark:text-stone-100">
                            {{ speech.number }}
                        </td>
                        <td class="px-3 py-3.5 align-top leading-6">
                            <Link
                                v-if="isAdmin"
                                :href="route('speeches.show', speech.id)"
                                class="text-stone-900 hover:underline dark:text-stone-100"
                                >{{ speech.theme }}</Link
                            >
                            <span v-else class="text-stone-900 dark:text-stone-100">{{ speech.theme }}</span>
                        </td>
                        <td class="px-3 py-3.5 align-top leading-6 tabular-nums text-stone-600 dark:text-stone-400">
                            {{ speech.last_made?.date ?? "Nunca" }}
                        </td>
                        <td class="px-3 py-3.5 align-top leading-6">
                            <span v-if="preparedLabel(speech)" class="inline-flex items-center gap-1.5 text-stone-700 dark:text-stone-300">
                                <span aria-hidden="true" class="h-1.5 w-1.5 rounded-full bg-pine-600 dark:bg-pine-400" />
                                {{ preparedLabel(speech) }}
                            </span>
                            <span v-else class="text-stone-400 dark:text-stone-500">Ninguém</span>
                        </td>
                        <td v-if="isAdmin" class="px-3 py-2 text-right align-top">
                            <div class="inline-flex">
                                <IconButton
                                    :href="route('speeches.show', speech.id)"
                                    :icon="PencilSquareIcon"
                                    :label="`Editar discurso ${speech.number}`"
                                />
                                <IconButton
                                    :icon="TrashIcon"
                                    :label="`Excluir discurso ${speech.number}`"
                                    tone="danger"
                                    @click="speechToDelete = speech"
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <ul class="divide-y divide-stone-100 dark:divide-stone-800/70 md:hidden">
                <li v-for="speech in speeches" :key="speech.id" class="flex items-start">
                    <component
                        :is="isAdmin ? Link : 'div'"
                        :href="isAdmin ? route('speeches.show', speech.id) : undefined"
                        class="flex min-w-0 flex-1 gap-3 px-4 py-3.5"
                    >
                        <span class="w-9 shrink-0 text-right font-display text-lg font-medium leading-6 tabular-nums text-stone-900 dark:text-stone-100">
                            {{ speech.number }}
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block leading-6 text-stone-900 dark:text-stone-100">{{ speech.theme }}</span>
                            <span class="mt-1 flex flex-wrap gap-x-3 text-xs text-stone-500 dark:text-stone-400">
                                <span>Última vez: <span class="tabular-nums">{{ speech.last_made?.date ?? "nunca" }}</span></span>
                                <span v-if="preparedLabel(speech)" class="inline-flex items-center gap-1">
                                    <span aria-hidden="true" class="h-1.5 w-1.5 rounded-full bg-pine-600 dark:bg-pine-400" />
                                    {{ preparedLabel(speech) }}
                                </span>
                            </span>
                        </span>
                        <ChevronRightIcon v-if="isAdmin" aria-hidden="true" class="mt-0.5 h-5 w-5 shrink-0 text-stone-400" />
                    </component>
                    <IconButton
                        v-if="isAdmin"
                        :icon="TrashIcon"
                        :label="`Excluir discurso ${speech.number}`"
                        class="mr-2 mt-2"
                        tone="danger"
                        @click="speechToDelete = speech"
                    />
                </li>
            </ul>

            <TablePaginator :list="list" />
        </template>

        <EmptyState
            v-else-if="hasActiveFilters"
            :icon="BookOpenIcon"
            description="Nenhum discurso corresponde à busca ou aos filtros."
            title="Nada encontrado"
        >
            <Button size="sm" variant="secondary" @click="resetFilters">Limpar filtros</Button>
        </EmptyState>
        <EmptyState v-else :icon="BookOpenIcon" title="Nenhum discurso cadastrado" />
    </section>

    <ConfirmDelete
        :delete-url="speechToDelete ? route('speeches.destroy', speechToDelete.id) : null"
        :show="!!speechToDelete"
        :to-delete="speechToDelete ? `${speechToDelete.number} · ${speechToDelete.theme}` : null"
        title="Excluir discurso?"
        @close="speechToDelete = null"
    />
</template>
