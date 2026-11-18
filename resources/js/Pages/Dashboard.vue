<script setup>
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { ArrowRightIcon, CalendarDaysIcon, PlusIcon } from "@heroicons/vue/20/solid";
import PageHeader from "@/Components/Ui/PageHeader.vue";
import Button from "@/Components/Ui/Button.vue";
import EmptyState from "@/Components/Ui/EmptyState.vue";
import DirectionTag from "@/Components/Ui/DirectionTag.vue";
import PhoneLink from "@/Components/Ui/PhoneLink.vue";
import { useAccess } from "@/composables/useAccess";
import {
    capitalize,
    formatDate,
    isSameDay,
    monthName,
    parseDate,
    startOfToday,
    weekdayName,
    weekdayShort,
} from "@/composables/useDates";

const props = defineProps({
    current: Object,
    next: Object,
});

const { canSeeArrangements } = useAccess();

const today = startOfToday();
const nextMonthIndex = (today.getMonth() + 1) % 12;
const nextMonthYear = today.getMonth() === 11 ? today.getFullYear() + 1 : today.getFullYear();

const todayLabel = `${capitalize(weekdayName(today))}, ${today.getDate()} de ${monthName(today.getMonth())}`;
const currentMonthTitle = `${capitalize(monthName(today.getMonth()))} de ${today.getFullYear()}`;
const nextMonthTitle = `${capitalize(monthName(nextMonthIndex))} de ${nextMonthYear}`;

/** Receive and send entries merged into one chronological agenda. */
const agenda = computed(() => {
    if (!props.current) {
        return [];
    }
    const receive = (props.current.to_receive ?? []).map((item) => ({
        key: `r-${item.id ?? item.date}`,
        direction: "receive",
        date: parseDate(item.date),
        speakerName: item.speaker || null,
        speech: item.speech,
        isGuest: !!item.is_guest,
    }));
    const send = (props.current.to_send ?? []).map((item) => ({
        key: `s-${item.id ?? item.date}`,
        direction: "send",
        date: parseDate(item.date),
        speakerName: item.speaker?.name ?? null,
        speech: item.speech,
        isGuest: false,
    }));
    return [...receive, ...send].sort((a, b) => (a.date ?? 0) - (b.date ?? 0));
});

const isComplete = (entry) => !!entry.speakerName && !!entry.speech?.number;
const completeCount = computed(() => agenda.value.filter(isComplete).length);
const progress = computed(() =>
    agenda.value.length ? Math.round((completeCount.value / agenda.value.length) * 100) : 0
);

const nextEntryKey = computed(
    () => agenda.value.find((entry) => entry.date && entry.date >= today)?.key ?? null
);

const isPast = (entry) => entry.date && entry.date < today;

/** Rows keep their original dates when an arrangement's month is changed later; flag them. */
const arrangementMonth = computed(() => parseDate(props.current?.formatted_month));
const isOutsideMonth = (entry) =>
    !!entry.date &&
    !!arrangementMonth.value &&
    (entry.date.getMonth() !== arrangementMonth.value.getMonth() ||
        entry.date.getFullYear() !== arrangementMonth.value.getFullYear());
const hasOutsideMonth = computed(() => agenda.value.some(isOutsideMonth));
</script>

<template>
    <Head title="Início" />

    <PageHeader :eyebrow="todayLabel" :title="currentMonthTitle">
        <template v-if="current" #actions>
            <Button :href="route('schedules.show', current.id)" variant="secondary">
                Abrir arranjo
                <ArrowRightIcon aria-hidden="true" class="h-4 w-4" />
            </Button>
        </template>
    </PageHeader>

    <div v-if="current" class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_18rem] lg:gap-10">
        <!-- Agenda -->
        <section aria-labelledby="agenda-title">
            <div class="mb-3 flex flex-wrap items-end justify-between gap-3">
                <h2 id="agenda-title" class="text-base font-semibold text-stone-900 dark:text-stone-100">
                    Agenda do mês
                </h2>
                <div class="flex items-center gap-3 text-sm text-stone-600 dark:text-stone-400">
                    <span>
                        <span class="font-medium tabular-nums text-stone-900 dark:text-stone-100">{{ completeCount }}</span>
                        de <span class="tabular-nums">{{ agenda.length }}</span> datas definidas
                    </span>
                    <span
                        aria-hidden="true"
                        class="hidden h-1.5 w-24 overflow-hidden rounded-full bg-stone-200 dark:bg-stone-800 sm:block"
                    >
                        <span
                            :style="{ width: `${progress}%` }"
                            class="block h-full rounded-full bg-pine-600 dark:bg-pine-400"
                        />
                    </span>
                </div>
            </div>

            <p
                v-if="hasOutsideMonth"
                class="mb-3 rounded-md bg-clay-50 px-3 py-2 text-sm text-clay-900 dark:bg-clay-950 dark:text-clay-100"
                role="note"
            >
                Algumas datas não pertencem ao mês deste arranjo. Revise-as no arranjo.
            </p>
            <ol
                v-if="agenda.length"
                class="divide-y divide-stone-200 overflow-hidden rounded-lg border border-stone-200 bg-white dark:divide-stone-800 dark:border-stone-800 dark:bg-stone-900"
            >
                <li
                    v-for="entry in agenda"
                    :key="entry.key"
                    :class="[
                        isPast(entry) && !isOutsideMonth(entry) ? 'opacity-70' : '',
                        entry.key === nextEntryKey ? 'bg-pine-50/60 dark:bg-pine-950/40' : '',
                    ]"
                    class="relative flex gap-4 px-4 py-4 sm:gap-5 sm:px-5"
                >
                    <span
                        v-if="entry.key === nextEntryKey"
                        aria-hidden="true"
                        class="absolute inset-y-0 left-0 w-0.5 bg-pine-600 dark:bg-pine-400"
                    />
                    <div class="w-11 shrink-0 text-center">
                        <p class="font-display text-[1.75rem] font-medium leading-none tabular-nums text-stone-900 dark:text-stone-50">
                            {{ entry.date?.getDate() ?? "–" }}
                        </p>
                        <p class="mt-1 text-[11px] font-semibold uppercase tracking-wide text-stone-500 dark:text-stone-400">
                            {{ weekdayShort(entry.date) }}
                        </p>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="mb-1 flex flex-wrap items-center gap-2">
                            <DirectionTag :direction="entry.direction" />
                            <span
                                v-if="entry.key === nextEntryKey"
                                class="text-xs font-semibold text-pine-800 dark:text-pine-300"
                                >{{ isSameDay(entry.date, today) ? "Hoje" : "Próximo" }}</span
                            >
                            <span v-if="entry.isGuest" class="text-xs text-stone-500 dark:text-stone-400">· Convite</span>
                            <span v-if="isOutsideMonth(entry)" class="text-xs font-medium text-clay-700 dark:text-clay-300">
                                · {{ formatDate(entry.date) }}, fora do mês
                            </span>
                        </div>
                        <p
                            :class="entry.speakerName ? 'text-stone-900 dark:text-stone-100' : 'text-clay-700 dark:text-clay-300'"
                            class="break-words font-medium"
                        >
                            {{ entry.speakerName ?? "Orador a definir" }}
                        </p>
                        <p class="mt-0.5 break-words text-sm text-stone-600 dark:text-stone-400">
                            <template v-if="entry.speech?.number">
                                <span class="font-medium tabular-nums text-stone-800 dark:text-stone-200">Nº {{ entry.speech.number }}</span>
                                · {{ entry.speech.theme }}
                            </template>
                            <span v-else class="text-clay-700 dark:text-clay-300">Tema a definir</span>
                        </p>
                    </div>
                </li>
            </ol>
            <div
                v-else
                class="rounded-lg border border-dashed border-stone-300 bg-white dark:border-stone-700 dark:bg-stone-900"
            >
                <EmptyState
                    :icon="CalendarDaysIcon"
                    compact
                    description="Defina o dia da reunião no arranjo para gerar as datas do mês."
                    title="Nenhuma data neste arranjo"
                >
                    <Button :href="route('schedules.show', current.id)" size="sm" variant="secondary">Abrir arranjo</Button>
                </EmptyState>
            </div>
        </section>

        <!-- Details -->
        <aside class="space-y-8">
            <section aria-labelledby="partner-title">
                <p class="eyebrow mb-2">Arranjo com</p>
                <h2 id="partner-title" class="break-words font-display text-2xl font-medium leading-snug text-stone-900 dark:text-stone-50">
                    {{ current.congregation }}
                </h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div v-if="current.day || current.hour">
                        <dt class="text-stone-500 dark:text-stone-400">Reunião deles</dt>
                        <dd class="text-stone-900 dark:text-stone-100">
                            {{ [current.day, current.hour].filter(Boolean).join(" · ") }}
                        </dd>
                    </div>
                    <div v-if="current.address">
                        <dt class="text-stone-500 dark:text-stone-400">Endereço</dt>
                        <dd class="break-words text-stone-900 dark:text-stone-100">{{ current.address }}</dd>
                    </div>
                    <div v-if="current.contact || current.phone">
                        <dt class="text-stone-500 dark:text-stone-400">Contato</dt>
                        <dd class="text-stone-900 dark:text-stone-100">
                            <span v-if="current.contact" class="block">{{ current.contact }}</span>
                            <PhoneLink v-if="current.phone" :phone="current.phone" />
                        </dd>
                    </div>
                </dl>
            </section>

            <section aria-labelledby="next-title" class="border-t border-stone-200 pt-6 dark:border-stone-800">
                <p class="eyebrow mb-2">Próximo mês</p>
                <h2 id="next-title" class="text-base font-semibold text-stone-900 dark:text-stone-100">{{ nextMonthTitle }}</h2>
                <template v-if="next">
                    <p class="mt-1 break-words text-sm text-stone-600 dark:text-stone-400">{{ next.congregation }}</p>
                    <Link
                        :href="route('schedules.show', next.id)"
                        class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-pine-800 hover:underline dark:text-pine-300"
                    >
                        Preparar datas
                        <ArrowRightIcon aria-hidden="true" class="h-4 w-4" />
                    </Link>
                </template>
                <template v-else>
                    <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">Ainda sem arranjo.</p>
                    <Button
                        v-if="canSeeArrangements"
                        :href="route('schedules.create')"
                        class="mt-3"
                        size="sm"
                        variant="secondary"
                    >
                        <template #icon><PlusIcon aria-hidden="true" class="h-4 w-4" /></template>
                        Criar arranjo
                    </Button>
                </template>
            </section>
        </aside>
    </div>

    <div v-else class="space-y-8">
        <div class="rounded-lg border border-dashed border-stone-300 bg-white dark:border-stone-700 dark:bg-stone-900">
            <EmptyState
                :icon="CalendarDaysIcon"
                description="Quando houver um arranjo para este mês, a agenda de quem recebemos e enviamos aparece aqui."
                title="Nenhum arranjo para este mês"
            >
                <Button v-if="canSeeArrangements" :href="route('schedules.create')">
                    <template #icon><PlusIcon aria-hidden="true" class="h-4 w-4" /></template>
                    Criar arranjo
                </Button>
            </EmptyState>
        </div>
        <section v-if="next" aria-labelledby="next-only-title" class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="eyebrow mb-1">Próximo mês · {{ nextMonthTitle }}</p>
                <h2 id="next-only-title" class="font-medium text-stone-900 dark:text-stone-100">{{ next.congregation }}</h2>
            </div>
            <Button :href="route('schedules.show', next.id)" size="sm" variant="secondary">Preparar datas</Button>
        </section>
    </div>
</template>
