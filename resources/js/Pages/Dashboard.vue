<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
    current: Object,
    next: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header do Dashboard -->
        <div class="flex flex-wrap items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Dashboard
            </h1>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ new Date().toLocaleDateString('pt-BR', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) }}
            </div>
        </div>

        <!-- Arranjo Atual -->
        <div v-if="current !== null" class="space-y-4">
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Arranjo Atual
                </h2>
                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                    Em andamento
                </span>
            </div>

            <div class="rounded-lg bg-white shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-800 dark:ring-gray-700">
                <!-- Cabeçalho do arranjo -->
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800/50">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="space-y-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ current.congregation }}
                            </h3>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                                <span v-if="current.address" class="flex items-center gap-1">
                                    <font-awesome-icon class="h-3 w-3" icon="fa-solid fa-map-marker-alt" />
                                    {{ current.address }}
                                </span>
                                <span v-if="current.hour" class="flex items-center gap-1">
                                    <font-awesome-icon class="h-3 w-3" icon="fa-solid fa-clock" />
                                    {{ current.hour }}
                                </span>
                            </div>
                        </div>
                        <Link
                            :href="route('schedules.show', current.id)"
                            class="inline-flex items-center gap-2 rounded-md dark:bg-sky-800 bg-sky-600 px-3 py-2 text-sm font-medium text-white hover:bg-sky-700 dark:hover:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-500"
                        >
                            Ver detalhes
                        </Link>
                    </div>
                </div>

                <!-- Conteúdo do arranjo -->
                <div class="p-6">
                    <div class="grid gap-8 lg:grid-cols-2">
                        <!-- Receber -->
                        <div class="flex flex-col space-y-4">
                            <div class="flex items-center gap-2">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                    <font-awesome-icon class="h-4 w-4 dark:text-green-600 text-green-500" icon="fa-solid fa-arrow-down" />
                                    Receber
                                </h4>
                                <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    {{ current.to_receive?.length || 0 }} {{ (current.to_receive?.length || 0) === 1 ? 'discurso' : 'discursos' }}
                                </span>
                            </div>

                            <div v-if="current.to_receive?.length" class="grid gap-3 sm:grid-cols-2">
                                <div
                                    v-for="(item, key) in current.to_receive"
                                    :key="key"
                                    class="rounded-lg hover:shadow-lg hover:border-b-4 dark:hover:border-b-sky-800 hover:border-b-sky-600 border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50"
                                >
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <font-awesome-icon class="h-3 w-3 text-gray-400" icon="fa-regular fa-calendar" />
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ item.date }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <font-awesome-icon class="h-3 w-3 text-gray-400" icon="fa-solid fa-user" />
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ item.speaker || '-' }}</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <font-awesome-icon class="mt-0.5 h-3 w-3 text-gray-400" icon="fa-solid fa-microphone" />
                                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                                {{ item.speech?.number ? `${item.speech.number} - ${item.speech.theme}` : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center dark:border-gray-600">
                                <font-awesome-icon class="mx-auto h-8 w-8 text-gray-400" icon="fa-solid fa-inbox" />
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nenhum discurso para receber</p>
                            </div>
                        </div>

                        <!-- Enviar -->
                        <div class="flex flex-col space-y-4">
                            <div class="flex items-center gap-2">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                    <font-awesome-icon class="h-4 w-4 dark:text-sky-800 text-sky-600" icon="fa-solid fa-arrow-up" />
                                    Enviar
                                </h4>
                                <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    {{ current.to_send?.length || 0 }} {{ (current.to_send?.length || 0) === 1 ? 'discurso' : 'discursos' }}
                                </span>
                            </div>

                            <div v-if="current.to_send?.length" class="grid gap-3 sm:grid-cols-2">
                                <div
                                    v-for="(sendItem, key) in current.to_send"
                                    :key="key"
                                    class="rounded-lg hover:shadow-lg hover:border-b-4 dark:hover:border-b-sky-800 hover:border-b-sky-600 border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50"
                                >
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <font-awesome-icon class="h-3 w-3 text-gray-400" icon="fa-regular fa-calendar" />
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ sendItem.date }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <font-awesome-icon class="h-3 w-3 text-gray-400" icon="fa-solid fa-user" />
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ sendItem.speaker?.name || '-' }}</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <font-awesome-icon class="mt-0.5 h-3 w-3 text-gray-400" icon="fa-solid fa-microphone" />
                                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                                {{ sendItem.speech?.number ? `${sendItem.speech.number} - ${sendItem.speech.theme}` : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center dark:border-gray-600">
                                <font-awesome-icon class="mx-auto h-8 w-8 text-gray-400" icon="fa-solid fa-paper-plane" />
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nenhum discurso para enviar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado vazio para arranjo atual -->
        <div v-else class="rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-8 text-center dark:border-gray-600 dark:bg-gray-800">
            <font-awesome-icon class="mx-auto h-12 w-12 text-gray-400" icon="fa-solid fa-calendar-xmark" />
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Nenhum arranjo atual</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Não há arranjos ativos no momento.</p>
        </div>

        <!-- Próximo Arranjo -->
        <div v-if="next !== null" class="space-y-4">
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Próximo Arranjo
                </h2>
                <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                    Planejado
                </span>
            </div>

            <div class="rounded-lg bg-gray-50 shadow-sm ring-1 px-6 py-4 ring-gray-900/5 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="space-y-1">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ next.congregation }}
                        </h3>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                                <span v-if="next.address" class="flex items-center gap-1">
                                    <font-awesome-icon class="h-3 w-3" icon="fa-solid fa-map-marker-alt" />
                                    {{ next.address }}
                                </span>
                            <span v-if="next.hour" class="flex items-center gap-1">
                                    <font-awesome-icon class="h-3 w-3" icon="fa-solid fa-clock" />
                                    {{ next.hour }}
                                </span>
                        </div>
                    </div>
                    <Link
                        :href="route('schedules.show', next.id)"
                        class="inline-flex items-center gap-2 rounded-md dark:bg-sky-800 bg-sky-600 px-3 py-2 text-sm font-medium text-white hover:bg-sky-700 dark:hover:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-500"
                    >
                        Ver detalhes
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
