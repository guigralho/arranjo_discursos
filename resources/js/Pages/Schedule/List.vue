<script setup>
import { computed, ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { CalendarDaysIcon, ChevronRightIcon, PencilSquareIcon, PlusIcon, TrashIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import PageHeader from "@/Components/Ui/PageHeader.vue";
import Button from "@/Components/Ui/Button.vue";
import IconButton from "@/Components/Ui/IconButton.vue";
import SearchField from "@/Components/Ui/SearchField.vue";
import SortHeader from "@/Components/Ui/SortHeader.vue";
import SortMenu from "@/Components/Ui/SortMenu.vue";
import EmptyState from "@/Components/Ui/EmptyState.vue";
import ConfirmDelete from "@/Components/Ui/ConfirmDelete.vue";
import TablePaginator from "@/Components/TablePaginator.vue";
import { useDebounceSearch } from "@/composables/useDebounceSearch";
import { vDatepicker } from "@/composables/vDatepicker";
import { capitalize, monthName, monthShort, parseDate } from "@/composables/useDates";

const props = defineProps({
    name: String,
    list: Object,
    filters: Object,
});

const { filters, isLoading, hasActiveFilters, toggleOrder, setOrder, resetFilters, updateFilter } =
    useDebounceSearch("/schedules", {
        page: props.filters.page,
        search: props.filters.search,
        searchDate: props.filters.searchDate,
        orderDir: props.filters.orderDir,
        orderField: props.filters.orderField,
    });

const monthPicker = { format: "dd/mm/yyyy", startView: 1, pickLevel: 1 };

const onMonthInput = (event) => {
    const value = event.target.value.trim();
    if (value === "" || /^\d{2}\/\d{2}\/\d{4}$/.test(value)) {
        updateFilter("searchDate", value || undefined);
    }
};

const sortOptions = [
    { field: "month", label: "Mês", ascLabel: "(mais antigo)", descLabel: "(mais recente)" },
    { field: "congregation", label: "Congregação" },
];

const isGroupedByYear = computed(() => !filters.value.orderField || filters.value.orderField === "month");

const rows = computed(() =>
    (props.list.data ?? []).map((schedule) => {
        const date = parseDate(schedule.formatted_month);
        return {
            ...schedule,
            year: date?.getFullYear() ?? "",
            monthIndex: date?.getMonth() ?? 0,
        };
    })
);

const showYearBefore = (index) =>
    isGroupedByYear.value && (index === 0 || rows.value[index - 1].year !== rows.value[index].year);

const meetingLabel = (schedule) => [schedule.day, schedule.hour].filter(Boolean).join(" · ");

const scheduleToDelete = ref(null);
</script>

<template>
    <Head :title="name" />

    <PageHeader :title="name">
        <template #meta>
            <span>Um arranjo por mês com cada congregação.</span>
        </template>
        <template #actions>
            <Button :href="route('schedules.create')">
                <template #icon><PlusIcon aria-hidden="true" class="h-4 w-4" /></template>
                Novo arranjo
            </Button>
        </template>
    </PageHeader>

    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center">
        <SearchField
            v-model="filters.search"
            class="sm:max-w-sm"
            label="Buscar congregação"
            placeholder="Buscar congregação"
        />
        <div class="flex gap-2">
            <div class="relative flex-1 sm:w-44 sm:flex-none">
                <label class="sr-only" for="month-filter">Filtrar por mês</label>
                <CalendarDaysIcon
                    aria-hidden="true"
                    class="pointer-events-none absolute left-3 top-3 h-4 w-4 text-stone-400"
                />
                <input
                    id="month-filter"
                    v-datepicker="monthPicker"
                    :value="filters.searchDate ?? ''"
                    autocomplete="off"
                    class="field h-10 pl-9"
                    placeholder="Filtrar mês"
                    type="text"
                    @input="onMonthInput"
                />
            </div>
            <Button v-if="hasActiveFilters" variant="ghost" @click="resetFilters">
                <template #icon><XMarkIcon aria-hidden="true" class="h-4 w-4" /></template>
                Limpar
            </Button>
        </div>
        <SortMenu
            id="schedules-sort"
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
        <template v-if="rows.length">
            <!-- Desktop table -->
            <table class="hidden w-full text-sm md:table">
                <thead class="border-b border-stone-200 text-xs font-medium text-stone-500 dark:border-stone-800 dark:text-stone-400">
                    <tr>
                        <SortHeader
                            :order-dir="filters.orderDir"
                            :order-field="filters.orderField"
                            class="w-40 px-5 py-2.5"
                            field="month"
                            label="Mês"
                            @sort="toggleOrder"
                        />
                        <SortHeader
                            :order-dir="filters.orderDir"
                            :order-field="filters.orderField"
                            class="px-5 py-2.5"
                            field="congregation"
                            label="Congregação"
                            @sort="toggleOrder"
                        />
                        <SortHeader
                            :order-dir="filters.orderDir"
                            :order-field="filters.orderField"
                            class="w-48 px-5 py-2.5"
                            field="day"
                            label="Reunião deles"
                            @sort="toggleOrder"
                        />
                        <th class="w-24 px-5 py-2.5 text-right" scope="col"><span class="sr-only">Ações</span></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(schedule, index) in rows" :key="schedule.id">
                        <tr v-if="showYearBefore(index)" class="bg-stone-50 dark:bg-stone-950/40">
                            <th
                                class="border-b border-stone-200 px-5 py-1.5 text-left font-display text-sm font-medium text-stone-600 dark:border-stone-800 dark:text-stone-400"
                                colspan="4"
                                scope="colgroup"
                            >
                                {{ schedule.year }}
                            </th>
                        </tr>
                        <tr class="group border-b border-stone-100 last:border-0 hover:bg-stone-50 dark:border-stone-800/70 dark:hover:bg-stone-800/40">
                            <td class="px-5 py-3.5">
                                <span class="font-medium text-stone-900 dark:text-stone-100">
                                    {{ capitalize(monthName(schedule.monthIndex)) }}
                                </span>
                                <span v-if="!isGroupedByYear" class="ml-1 tabular-nums text-stone-500">{{ schedule.year }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <Link
                                    :href="route('schedules.show', schedule.id)"
                                    class="font-medium text-stone-900 hover:underline dark:text-stone-100"
                                >
                                    {{ schedule.congregation }}
                                </Link>
                            </td>
                            <td class="px-5 py-3.5 text-stone-600 dark:text-stone-400">
                                {{ meetingLabel(schedule) || "—" }}
                            </td>
                            <td class="px-3 py-2 text-right">
                                <div class="inline-flex">
                                    <IconButton
                                        :href="route('schedules.show', schedule.id)"
                                        :icon="PencilSquareIcon"
                                        :label="`Abrir arranjo com ${schedule.congregation}`"
                                    />
                                    <IconButton
                                        :icon="TrashIcon"
                                        :label="`Excluir arranjo com ${schedule.congregation}`"
                                        tone="danger"
                                        @click="scheduleToDelete = schedule"
                                    />
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <!-- Mobile list -->
            <ul class="md:hidden">
                <template v-for="(schedule, index) in rows" :key="schedule.id">
                    <li
                        v-if="showYearBefore(index)"
                        class="border-b border-stone-200 bg-stone-50 px-4 py-1.5 font-display text-sm font-medium text-stone-600 dark:border-stone-800 dark:bg-stone-950/40 dark:text-stone-400"
                    >
                        {{ schedule.year }}
                    </li>
                    <li class="flex items-center border-b border-stone-100 last:border-0 dark:border-stone-800/70">
                        <Link
                            :href="route('schedules.show', schedule.id)"
                            class="flex min-w-0 flex-1 items-center gap-4 px-4 py-3.5"
                        >
                            <span class="w-10 shrink-0 text-center">
                                <span class="block font-display text-lg font-medium uppercase leading-none text-stone-900 dark:text-stone-100">
                                    {{ monthShort(schedule.monthIndex) }}
                                </span>
                                <span v-if="!isGroupedByYear" class="text-[11px] tabular-nums text-stone-500">{{ schedule.year }}</span>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block break-words font-medium text-stone-900 dark:text-stone-100">{{ schedule.congregation }}</span>
                                <span v-if="meetingLabel(schedule)" class="block text-sm text-stone-600 dark:text-stone-400">
                                    {{ meetingLabel(schedule) }}
                                </span>
                            </span>
                            <ChevronRightIcon aria-hidden="true" class="h-5 w-5 shrink-0 text-stone-400" />
                        </Link>
                        <IconButton
                            :icon="TrashIcon"
                            :label="`Excluir arranjo com ${schedule.congregation}`"
                            class="mr-2"
                            tone="danger"
                            @click="scheduleToDelete = schedule"
                        />
                    </li>
                </template>
            </ul>

            <TablePaginator :list="list" />
        </template>

        <EmptyState
            v-else-if="hasActiveFilters"
            :icon="CalendarDaysIcon"
            description="Nenhum arranjo corresponde aos filtros aplicados."
            title="Nada encontrado"
        >
            <Button size="sm" variant="secondary" @click="resetFilters">Limpar filtros</Button>
        </EmptyState>
        <EmptyState
            v-else
            :icon="CalendarDaysIcon"
            description="Crie o primeiro arranjo para começar a organizar quem recebemos e enviamos."
            title="Nenhum arranjo ainda"
        >
            <Button :href="route('schedules.create')" size="sm">Novo arranjo</Button>
        </EmptyState>
    </section>

    <ConfirmDelete
        :delete-url="scheduleToDelete ? route('schedules.destroy', scheduleToDelete.id) : null"
        :show="!!scheduleToDelete"
        :to-delete="scheduleToDelete ? `${scheduleToDelete.congregation} · ${scheduleToDelete.translated_month}` : null"
        subtitle="O arranjo e todas as datas de receber e enviar deste mês serão excluídos."
        title="Excluir arranjo?"
        @close="scheduleToDelete = null"
    />
</template>
