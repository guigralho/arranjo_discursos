<script setup>
import { computed, ref } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import {
    ArrowDownTrayIcon,
    BanknotesIcon,
    CheckIcon,
    PencilSquareIcon,
    PlusIcon,
    TicketIcon,
    TrashIcon,
    XMarkIcon,
} from "@heroicons/vue/20/solid";
import PageHeader from "@/Components/Ui/PageHeader.vue";
import Button from "@/Components/Ui/Button.vue";
import IconButton from "@/Components/Ui/IconButton.vue";
import SearchField from "@/Components/Ui/SearchField.vue";
import FilterChip from "@/Components/Ui/FilterChip.vue";
import SortHeader from "@/Components/Ui/SortHeader.vue";
import EmptyState from "@/Components/Ui/EmptyState.vue";
import ConfirmDelete from "@/Components/Ui/ConfirmDelete.vue";
import Dialog from "@/Components/Ui/Dialog.vue";
import FormField from "@/Components/Ui/FormField.vue";
import ActionMenu from "@/Components/Ui/ActionMenu.vue";
import ActionMenuItem from "@/Components/Ui/ActionMenuItem.vue";
import TablePaginator from "@/Components/TablePaginator.vue";
import { useDebounceSearch } from "@/composables/useDebounceSearch";
import { formatCurrency, formatMoneyInput, toDecimalString, vMoneyMask } from "@/composables/useMoney";

const props = defineProps({
    name: String,
    list: Object,
    totais: Object,
    filters: Object,
});

const { filters, isLoading, hasActiveFilters, toggleOrder, resetFilters } = useDebounceSearch("/bus", {
    page: props.filters.page,
    search: props.filters.search,
    friday: props.filters.friday,
    saturday: props.filters.saturday,
    sunday: props.filters.sunday,
    orderDir: props.filters.orderDir,
    orderField: props.filters.orderField,
});

const page = usePage();
const fare = computed(() => Number(page.props.valor_onibus ?? 0));

const bookings = computed(() => props.list.data ?? []);
const balanceTotal = computed(() => Number(props.totais.amount ?? 0) - Number(props.totais.total ?? 0));

const days = [
    { key: "friday", short: "Sex", label: "Sexta" },
    { key: "saturday", short: "Sáb", label: "Sábado" },
    { key: "sunday", short: "Dom", label: "Domingo" },
];

/* ---------- Fare ---------- */
const showFareDialog = ref(false);
const fareForm = useForm({
    valor_onibus: formatMoneyInput(Math.round(fare.value * 100)),
});

const openFareDialog = () => {
    fareForm.valor_onibus = formatMoneyInput(Math.round(fare.value * 100));
    fareForm.clearErrors();
    showFareDialog.value = true;
};

const saveFare = () => {
    fareForm
        .transform((data) => ({ valor_onibus: toDecimalString(data.valor_onibus) }))
        .put(route("bus.update-fare"), {
            preserveScroll: true,
            onSuccess: () => (showFareDialog.value = false),
        });
};

/* ---------- Deletion ---------- */
const bookingToDelete = ref(null);
const showDeleteAll = ref(false);
</script>

<template>
    <Head :title="name" />

    <PageHeader :title="name">
        <template #meta>
            <span>Reservas da viagem de ônibus por dia e situação de pagamento.</span>
        </template>
        <template #actions>
            <ActionMenu label="Ações">
                <ActionMenuItem :href="route('bus.download-list')" :icon="ArrowDownTrayIcon" external>Baixar lista (Excel)</ActionMenuItem>
                <ActionMenuItem :icon="BanknotesIcon" @select="openFareDialog">Alterar valor da passagem</ActionMenuItem>
                <ActionMenuItem :icon="TrashIcon" tone="danger" @select="showDeleteAll = true">Excluir todas as reservas</ActionMenuItem>
            </ActionMenu>
            <Button :href="route('bus.create')">
                <template #icon><PlusIcon aria-hidden="true" class="h-4 w-4" /></template>
                Nova reserva
            </Button>
        </template>
    </PageHeader>

    <!-- Summary -->
    <section aria-label="Resumo" class="mb-6 grid grid-cols-2 gap-px overflow-hidden rounded-lg border border-stone-200 bg-stone-200 dark:border-stone-800 dark:bg-stone-800 md:grid-cols-4">
        <div class="bg-white px-4 py-3 dark:bg-stone-900">
            <p class="text-xs text-stone-500 dark:text-stone-400">Passagem</p>
            <p class="mt-0.5 flex items-baseline gap-2">
                <span class="font-display text-xl font-medium tabular-nums">{{ formatCurrency(fare) }}</span>
                <button class="text-xs font-medium text-pine-800 hover:underline dark:text-pine-300" type="button" @click="openFareDialog">
                    Alterar
                </button>
            </p>
        </div>
        <div class="bg-white px-4 py-3 dark:bg-stone-900">
            <p class="text-xs text-stone-500 dark:text-stone-400">Passageiros por dia</p>
            <p class="mt-1 flex flex-wrap gap-x-3 gap-y-0.5 text-sm">
                <span v-for="day in days" :key="day.key">
                    <span class="text-stone-500 dark:text-stone-400">{{ day.short }}</span>
                    <span class="ml-1 font-semibold tabular-nums">{{ totais[day.key] }}</span>
                </span>
            </p>
        </div>
        <div class="bg-white px-4 py-3 dark:bg-stone-900">
            <p class="text-xs text-stone-500 dark:text-stone-400">Recebido · a receber</p>
            <p class="mt-0.5 text-sm">
                <span class="font-display text-xl font-medium tabular-nums">{{ formatCurrency(totais.amount) }}</span>
                <span class="ml-1 tabular-nums text-stone-500 dark:text-stone-400">de {{ formatCurrency(totais.total) }}</span>
            </p>
        </div>
        <div class="bg-white px-4 py-3 dark:bg-stone-900">
            <p class="text-xs text-stone-500 dark:text-stone-400">Saldo</p>
            <p
                :class="balanceTotal < 0 ? 'text-red-700 dark:text-red-400' : 'text-pine-800 dark:text-pine-300'"
                class="mt-0.5 font-display text-xl font-medium tabular-nums"
            >
                {{ formatCurrency(balanceTotal) }}
            </p>
        </div>
    </section>

    <div class="mb-4 flex flex-col gap-2 lg:flex-row lg:items-center">
        <SearchField v-model="filters.search" class="lg:max-w-sm" label="Buscar passageiro" placeholder="Buscar passageiro" />
        <div class="-mx-4 flex gap-2 overflow-x-auto px-4 sm:mx-0 sm:px-0" role="group" aria-label="Filtrar por dia">
            <FilterChip v-for="day in days" :key="day.key" v-model="filters[day.key]">{{ day.label }}</FilterChip>
            <Button v-if="hasActiveFilters" variant="ghost" @click="resetFilters">
                <template #icon><XMarkIcon aria-hidden="true" class="h-4 w-4" /></template>
                Limpar
            </Button>
        </div>
    </div>

    <section
        :aria-busy="isLoading ? 'true' : 'false'"
        :class="isLoading ? 'opacity-60' : ''"
        class="overflow-hidden rounded-lg border border-stone-200 bg-white transition-opacity dark:border-stone-800 dark:bg-stone-900"
    >
        <template v-if="bookings.length">
            <div class="hidden overflow-x-auto lg:block">
                <table class="w-full text-sm">
                    <thead class="border-b border-stone-200 text-xs font-medium text-stone-500 dark:border-stone-800 dark:text-stone-400">
                        <tr>
                            <SortHeader
                                :order-dir="filters.orderDir"
                                :order-field="filters.orderField"
                                class="px-5 py-2.5"
                                field="passengers.name"
                                label="Passageiro"
                                @sort="toggleOrder"
                            />
                            <th v-for="day in days" :key="day.key" class="w-12 px-1 py-2.5 text-center" scope="col">
                                <abbr :title="day.label" class="no-underline">{{ day.short }}</abbr>
                            </th>
                            <th class="w-28 px-3 py-2.5 text-right" scope="col">A pagar</th>
                            <th class="w-28 px-3 py-2.5 text-right" scope="col">Pago</th>
                            <th class="w-36 px-3 py-2.5 text-right" scope="col">Saldo</th>
                            <th class="px-3 py-2.5 text-left" scope="col">Obs.</th>
                            <th class="w-24 px-3 py-2.5" scope="col"><span class="sr-only">Ações</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800/70">
                        <tr v-for="booking in bookings" :key="booking.id" class="hover:bg-stone-50 dark:hover:bg-stone-800/40">
                            <td class="px-5 py-3">
                                <Link :href="route('bus.show', booking.id)" class="font-medium text-stone-900 hover:underline dark:text-stone-100">
                                    {{ booking.passenger?.name }}
                                </Link>
                                <span v-if="booking.passenger?.doc" class="block text-xs tabular-nums text-stone-500 dark:text-stone-400">
                                    {{ booking.passenger.doc }}
                                </span>
                            </td>
                            <td v-for="day in days" :key="day.key" class="px-1 py-3 text-center">
                                <CheckIcon v-if="booking[day.key]" :aria-label="`Vai na ${day.label.toLowerCase()}`" class="mx-auto h-4 w-4 text-pine-700 dark:text-pine-400" role="img" />
                                <span v-else class="text-stone-300 dark:text-stone-600"><span aria-hidden="true">·</span><span class="sr-only">Não vai</span></span>
                            </td>
                            <td class="px-3 py-3 text-right tabular-nums text-stone-700 dark:text-stone-300">{{ formatCurrency(booking.total) }}</td>
                            <td class="px-3 py-3 text-right tabular-nums text-stone-700 dark:text-stone-300">{{ formatCurrency(booking.amount) }}</td>
                            <td class="px-3 py-3 text-right">
                                <span
                                    :class="booking.saldo < 0 ? 'bg-red-50 text-red-800 dark:bg-red-950/60 dark:text-red-300' : 'bg-pine-50 text-pine-800 dark:bg-pine-950 dark:text-pine-200'"
                                    class="inline-flex items-center gap-1 whitespace-nowrap rounded px-1.5 py-0.5 text-xs font-medium tabular-nums"
                                >
                                    {{ booking.saldo < 0 ? "Deve" : "Pago" }}
                                    <template v-if="booking.saldo !== 0">· {{ formatCurrency(Math.abs(booking.saldo)) }}</template>
                                </span>
                            </td>
                            <td class="max-w-[14rem] px-3 py-3 text-stone-600 dark:text-stone-400">
                                <span class="line-clamp-2 break-words" :title="booking.obs">{{ booking.obs }}</span>
                            </td>
                            <td class="px-3 py-1.5 text-right">
                                <div class="inline-flex">
                                    <IconButton :href="route('bus.show', booking.id)" :icon="PencilSquareIcon" :label="`Editar reserva de ${booking.passenger?.name}`" />
                                    <IconButton :icon="TrashIcon" :label="`Excluir reserva de ${booking.passenger?.name}`" tone="danger" @click="bookingToDelete = booking" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <ul class="divide-y divide-stone-100 dark:divide-stone-800/70 lg:hidden">
                <li v-for="booking in bookings" :key="booking.id" class="flex items-start gap-2 py-3 pl-4 pr-2">
                    <Link :href="route('bus.show', booking.id)" class="min-w-0 flex-1">
                        <span class="flex items-start justify-between gap-3">
                            <span class="min-w-0 break-words font-medium text-stone-900 dark:text-stone-100">{{ booking.passenger?.name }}</span>
                            <span
                                :class="booking.saldo < 0 ? 'bg-red-50 text-red-800 dark:bg-red-950/60 dark:text-red-300' : 'bg-pine-50 text-pine-800 dark:bg-pine-950 dark:text-pine-200'"
                                class="shrink-0 rounded px-1.5 py-0.5 text-xs font-medium tabular-nums"
                            >
                                {{ booking.saldo < 0 ? `Deve ${formatCurrency(Math.abs(booking.saldo))}` : "Pago" }}
                            </span>
                        </span>
                        <span class="mt-1 flex flex-wrap gap-1.5">
                            <span
                                v-for="day in days.filter((d) => booking[d.key])"
                                :key="day.key"
                                class="rounded bg-stone-100 px-1.5 py-0.5 text-xs text-stone-700 dark:bg-stone-800 dark:text-stone-300"
                                >{{ day.label }}</span
                            >
                            <span class="text-xs tabular-nums text-stone-500 dark:text-stone-400">
                                Pago {{ formatCurrency(booking.amount) }} de {{ formatCurrency(booking.total) }}
                            </span>
                        </span>
                        <span v-if="booking.obs" class="mt-1 block break-words text-xs text-stone-500 dark:text-stone-400">{{ booking.obs }}</span>
                    </Link>
                    <IconButton :icon="TrashIcon" :label="`Excluir reserva de ${booking.passenger?.name}`" tone="danger" @click="bookingToDelete = booking" />
                </li>
            </ul>

            <TablePaginator :list="list" />
        </template>

        <EmptyState v-else-if="hasActiveFilters" :icon="TicketIcon" description="Nenhuma reserva corresponde aos filtros." title="Nada encontrado">
            <Button size="sm" variant="secondary" @click="resetFilters">Limpar filtros</Button>
        </EmptyState>
        <EmptyState v-else :icon="TicketIcon" description="As reservas da viagem aparecem aqui." title="Nenhuma reserva ainda">
            <Button :href="route('bus.create')" size="sm">Nova reserva</Button>
        </EmptyState>
    </section>

    <p class="mt-4 text-sm text-stone-600 dark:text-stone-400">
        Pagamentos via PIX: <span class="font-medium tabular-nums text-stone-900 dark:text-stone-100">11966615727</span>
    </p>

    <Dialog :show="showFareDialog" title="Valor da passagem" @close="showFareDialog = false">
        <form id="fare-form" novalidate @submit.prevent="saveFare">
            <FormField id="valor_onibus" v-slot="{ field }" :error="fareForm.errors.valor_onibus" hint="Valor cobrado por dia de viagem." label="Valor por dia">
                <div class="relative">
                    <span aria-hidden="true" class="pointer-events-none absolute left-3 top-2.5 text-sm text-stone-500">R$</span>
                    <input
                        v-model="fareForm.valor_onibus"
                        v-money-mask
                        autocomplete="off"
                        class="field h-10 pl-9 text-right tabular-nums"
                        inputmode="numeric"
                        type="text"
                        v-bind="field"
                    />
                </div>
            </FormField>
        </form>
        <template #actions>
            <Button :disabled="fareForm.processing" variant="secondary" @click="showFareDialog = false">Cancelar</Button>
            <Button :loading="fareForm.processing" form="fare-form" loading-text="Salvando…" type="submit">Salvar valor</Button>
        </template>
    </Dialog>

    <ConfirmDelete
        :delete-url="bookingToDelete ? route('bus.destroy', bookingToDelete.id) : null"
        :show="!!bookingToDelete"
        :to-delete="bookingToDelete?.passenger?.name"
        title="Excluir reserva?"
        @close="bookingToDelete = null"
    />
    <ConfirmDelete
        :delete-url="route('bus.delete-all')"
        :show="showDeleteAll"
        confirm-label="Excluir todas"
        confirm-word="EXCLUIR"
        subtitle="Todas as reservas do ônibus serão apagadas definitivamente. Use isso apenas para começar uma nova viagem."
        title="Excluir todas as reservas?"
        @close="showDeleteAll = false"
    />
</template>
