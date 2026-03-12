<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import {
    CalendarDaysIcon,
    ClockIcon,
    MapPinIcon,
    UserIcon,
    MicrophoneIcon,
    ArrowDownIcon,
    ArrowUpIcon,
    InboxIcon,
    PaperAirplaneIcon,
    CalendarIcon,
    ArrowRightIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    current: Object,
    next: Object,
});

const formatDate = () => {
    return new Date().toLocaleDateString('pt-BR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
        <Breadcrumb />

        <!-- Header -->
        <div class="mb-8 flex flex-wrap items-end justify-between gap-2">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-900 dark:text-white sm:text-3xl">
                    Dashboard
                </h1>
                <p class="mt-1 text-sm text-stone-500 dark:text-slate-400">
                    {{ formatDate() }}
                </p>
            </div>
        </div>

        <!-- Current Schedule -->
        <div v-if="current !== null" class="mb-8 space-y-4">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-semibold text-stone-900 dark:text-white">
                    Arranjo Atual
                </h2>
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                    Em andamento
                </span>
            </div>

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800">
                <!-- Schedule header -->
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-stone-100 px-5 py-4 dark:border-slate-800 sm:px-6">
                    <div class="space-y-1">
                        <h3 class="text-base font-semibold text-stone-900 dark:text-white">
                            {{ current.congregation }}
                        </h3>
                        <div class="flex flex-wrap gap-4 text-sm text-stone-500 dark:text-slate-400">
                            <span v-if="current.address" class="flex items-center gap-1.5">
                                <MapPinIcon class="h-3.5 w-3.5" />
                                {{ current.address }}
                            </span>
                            <span v-if="current.hour" class="flex items-center gap-1.5">
                                <ClockIcon class="h-3.5 w-3.5" />
                                {{ current.hour }}
                            </span>
                        </div>
                    </div>
                    <Link
                        :href="route('schedules.show', current.id)"
                        class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600"
                    >
                        Ver detalhes
                        <ArrowRightIcon class="h-3.5 w-3.5" />
                    </Link>
                </div>

                <!-- Schedule content -->
                <div class="p-5 sm:p-6">
                    <div class="grid gap-8 lg:grid-cols-2">
                        <!-- Receive -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-500/10">
                                    <ArrowDownIcon class="h-3.5 w-3.5 text-emerald-600 dark:text-emerald-400" />
                                </div>
                                <h4 class="font-semibold text-stone-900 dark:text-white">Receber</h4>
                                <span class="rounded-full bg-stone-100 px-2 py-0.5 text-xs font-medium text-stone-600 dark:bg-slate-800 dark:text-slate-400">
                                    {{ current.to_receive?.length || 0 }}
                                </span>
                            </div>

                            <div v-if="current.to_receive?.length" class="space-y-2">
                                <div
                                    v-for="(item, key) in current.to_receive"
                                    :key="key"
                                    class="group rounded-xl border border-stone-200 bg-stone-50/50 p-4 transition-all hover:border-teal-200 hover:shadow-sm dark:border-slate-800 dark:bg-slate-800/50 dark:hover:border-teal-500/30"
                                >
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2 text-sm">
                                            <CalendarIcon class="h-3.5 w-3.5 text-stone-400 dark:text-slate-500" />
                                            <span class="font-medium text-stone-900 dark:text-white">{{ item.date }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm">
                                            <UserIcon class="h-3.5 w-3.5 text-stone-400 dark:text-slate-500" />
                                            <span class="text-stone-600 dark:text-slate-300">{{ item.speaker || '-' }}</span>
                                        </div>
                                        <div class="flex items-start gap-2 text-sm">
                                            <MicrophoneIcon class="mt-0.5 h-3.5 w-3.5 text-stone-400 dark:text-slate-500" />
                                            <span class="text-stone-600 dark:text-slate-300">
                                                {{ item.speech?.number ? `${item.speech.number} - ${item.speech.theme}` : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="flex flex-col items-center rounded-xl border-2 border-dashed border-stone-200 py-8 dark:border-slate-800">
                                <InboxIcon class="h-8 w-8 text-stone-300 dark:text-slate-700" />
                                <p class="mt-2 text-sm text-stone-400 dark:text-slate-500">Nenhum discurso para receber</p>
                            </div>
                        </div>

                        <!-- Send -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-100 dark:bg-sky-500/10">
                                    <ArrowUpIcon class="h-3.5 w-3.5 text-sky-600 dark:text-sky-400" />
                                </div>
                                <h4 class="font-semibold text-stone-900 dark:text-white">Enviar</h4>
                                <span class="rounded-full bg-stone-100 px-2 py-0.5 text-xs font-medium text-stone-600 dark:bg-slate-800 dark:text-slate-400">
                                    {{ current.to_send?.length || 0 }}
                                </span>
                            </div>

                            <div v-if="current.to_send?.length" class="space-y-2">
                                <div
                                    v-for="(sendItem, key) in current.to_send"
                                    :key="key"
                                    class="group rounded-xl border border-stone-200 bg-stone-50/50 p-4 transition-all hover:border-teal-200 hover:shadow-sm dark:border-slate-800 dark:bg-slate-800/50 dark:hover:border-teal-500/30"
                                >
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2 text-sm">
                                            <CalendarIcon class="h-3.5 w-3.5 text-stone-400 dark:text-slate-500" />
                                            <span class="font-medium text-stone-900 dark:text-white">{{ sendItem.date }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm">
                                            <UserIcon class="h-3.5 w-3.5 text-stone-400 dark:text-slate-500" />
                                            <span class="text-stone-600 dark:text-slate-300">{{ sendItem.speaker?.name || '-' }}</span>
                                        </div>
                                        <div class="flex items-start gap-2 text-sm">
                                            <MicrophoneIcon class="mt-0.5 h-3.5 w-3.5 text-stone-400 dark:text-slate-500" />
                                            <span class="text-stone-600 dark:text-slate-300">
                                                {{ sendItem.speech?.number ? `${sendItem.speech.number} - ${sendItem.speech.theme}` : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="flex flex-col items-center rounded-xl border-2 border-dashed border-stone-200 py-8 dark:border-slate-800">
                                <PaperAirplaneIcon class="h-8 w-8 text-stone-300 dark:text-slate-700" />
                                <p class="mt-2 text-sm text-stone-400 dark:text-slate-500">Nenhum discurso para enviar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty state -->
        <div v-else class="mb-8 flex flex-col items-center rounded-2xl border-2 border-dashed border-stone-200 bg-white py-16 dark:border-slate-800 dark:bg-slate-900">
            <CalendarDaysIcon class="h-12 w-12 text-stone-300 dark:text-slate-700" />
            <h3 class="mt-4 text-base font-semibold text-stone-900 dark:text-white">Nenhum arranjo atual</h3>
            <p class="mt-1 text-sm text-stone-500 dark:text-slate-400">Não há arranjos ativos no momento.</p>
            <Link
                :href="route('schedules.create')"
                class="mt-5 inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600"
            >
                Criar arranjo
            </Link>
        </div>

        <!-- Next Schedule -->
        <div v-if="next !== null" class="space-y-4">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-semibold text-stone-900 dark:text-white">
                    Próximo Arranjo
                </h2>
                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                    Planejado
                </span>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800 sm:p-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="space-y-1">
                        <h3 class="text-base font-semibold text-stone-900 dark:text-white">
                            {{ next.congregation }}
                        </h3>
                        <div class="flex flex-wrap gap-4 text-sm text-stone-500 dark:text-slate-400">
                            <span v-if="next.address" class="flex items-center gap-1.5">
                                <MapPinIcon class="h-3.5 w-3.5" />
                                {{ next.address }}
                            </span>
                            <span v-if="next.hour" class="flex items-center gap-1.5">
                                <ClockIcon class="h-3.5 w-3.5" />
                                {{ next.hour }}
                            </span>
                        </div>
                    </div>
                    <Link
                        :href="route('schedules.show', next.id)"
                        class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600"
                    >
                        Ver detalhes
                        <ArrowRightIcon class="h-3.5 w-3.5" />
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
