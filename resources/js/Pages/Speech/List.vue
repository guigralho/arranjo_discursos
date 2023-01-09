<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link} from '@inertiajs/inertia-vue3';

defineProps({
    name: String,
    list: Object,
    search: String
});
</script>

<template>
    <Head :title="name"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ name }}</h2>
        </template>


        <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="py-8">
                <div class="flex md:flex-row flex-col justify-end w-full mb-1 sm:mb-0">
                    <div class="text-end">
                        <form
                            class="flex flex-col justify-center max-w-sm space-y-3 md:flex-row md:w-full md:space-x-3 md:space-y-0">
                            <div class=" relative ">
                                <input
                                    :value="$page.props.ziggy.query.search"
                                    class="rounded-lg border-transparent flex-1 appearance-none border border-gray-300 w-full py-2 px-4 bg-white text-gray-700 placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-200"
                                    name="search"
                                    type="text"/>
                            </div>
                            <button
                                class="flex-shrink-0 px-4 py-2 text-base font-semibold text-white bg-purple-600 rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200"
                                type="submit">
                                Buscar
                            </button>
                        </form>
                    </div>
                </div>
                <div class="px-4 py-4 -mx-4 overflow-x-auto sm:-mx-8 sm:px-8">
                    <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
                        <table class="min-w-full leading-normal bg-white dark:bg-gray-800">
                            <thead>
                            <tr class="border-b text-gray-800 font-weight-bold dark:border-gray-900 dark:text-white px-10">
                                <th class="px-5 py-5 text-sm text-left uppercase"
                                    scope="col">
                                    Número
                                </th>
                                <th class="px-5 py-5 text-sm text-left uppercase"
                                    scope="col">
                                    Nome
                                </th>
                                <th class="px-5 py-5 text-sm text-left uppercase"
                                    scope="col">
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in list.data" :key="item.id"
                                class="border-b text-gray-800 font-weight-bold dark:border-gray-900 dark:text-white">
                                <td class="px-5 py-3 text-sm">
                                    {{ item.number }}
                                </td>
                                <td class="px-5 py-3 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap dark:text-white">
                                        {{ item.theme }}
                                    </p>
                                </td>
                                <td class="px-5 py-3 text-sm space-x-3">
                                    <Link :href="route('profile.edit')"
                                          class="flex-shrink-0 px-4 py-2 text-base font-semibold text-white bg-purple-600 rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200">
                                        Editar
                                    </Link>
                                    <Link :href="route('profile.edit')"
                                          class="flex-shrink-0 px-4 py-2 text-base font-semibold text-white bg-red-600 rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200">
                                        Excluir
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div
                            class="flex flex-col items-center px-5 py-5 bg-white dark:bg-gray-800 xs:flex-row xs:justify-between">
                            <div class="flex items-center">
                                <template v-for="(link, index) in list.links">
                                    <Link
                                        :class="link.active ? 'bg-gray-100 dark:bg-gray-700  ' : index === 0 ? 'rounded-l-xl border' : index === list.links.length - 1 ? 'rounded-r-xl border' : 'border-t border-b'"
                                        :disabled="!link.url"
                                        :href="link.url"
                                        class="w-full px-4 py-2 text-base text-gray-600 bg-white dark:bg-gray-800  dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700"
                                        v-html="link.label.replaceAll('Next', '').replaceAll('Previous', '')"/>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
