<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
    current: Object,
    next: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            Arranjo atual
        </p>
        <div
            class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg"
        >
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <p>
                    Congregação: {{ current.congregation }} -
                    <Link
                        :href="route('schedules.show', current.id)"
                        class="text-md rounded-md border border-sky-800 px-2 text-sky-800 hover:bg-sky-800 hover:text-gray-100 dark:bg-sky-800 dark:text-gray-100 dark:hover:bg-transparent dark:hover:bg-transparent"
                    >
                        Detalhes
                    </Link>
                </p>
                <p>Endereço: {{ current.address }}</p>
                <p>Horário: {{ current.hour }}</p>

                <div class="mt-4 grid grid-cols-1 gap-4">
                    <p class="text-2xl">Receber</p>
                    <div
                        class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
                    >
                        <div
                            v-for="(item, key) in current.to_receive"
                            :key="key"
                        >
                            <p>Dia: {{ item.date }}</p>
                            <p>Orador: {{ item.speaker }}</p>
                            <p>
                                Tema: {{ item.speech?.number }} -
                                {{ item.speech?.theme }}
                            </p>
                        </div>
                    </div>
                    <p class="text-2xl">Enviar</p>
                    <div
                        class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
                    >
                        <div
                            v-for="(sendItem, key) in current.to_send"
                            :key="key"
                        >
                            <p>Dia: {{ sendItem.date }}</p>
                            <p>Orador: {{ sendItem.speaker?.name || "-" }}</p>
                            <p>
                                Tema: {{ sendItem.speech?.number }} -
                                {{ sendItem.speech?.theme }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p
            v-if="next !== null"
            class="mt-4 text-2xl font-bold text-gray-900 dark:text-gray-100"
        >
            Próximo arranjo
        </p>
        <div
            v-if="next !== null"
            class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg"
        >
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <p>
                    Congregação: {{ next.congregation }} -
                    <Link
                        :href="route('schedules.show', next.id)"
                        class="text-md rounded-md border border-sky-800 px-2 text-sky-800 hover:bg-sky-800 hover:text-gray-100 dark:bg-sky-800 dark:text-gray-100 dark:hover:bg-transparent dark:hover:bg-transparent"
                    >
                        Detalhes
                    </Link>
                </p>
                <p>Endereço: {{ next.address }}</p>
                <p>Horário: {{ next.hour }}</p>
            </div>
        </div>
    </div>
</template>
