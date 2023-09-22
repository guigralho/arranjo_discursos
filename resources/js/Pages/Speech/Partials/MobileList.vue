<script setup>
import EditLink from "@/Components/Buttons/EditLink.vue";
import DeleteLink from "@/Components/Buttons/DeleteLink.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import DeleteModal from "@/Components/DeleteModal.vue";

const emit = defineEmits(["toggleOrder"]);

const props = defineProps({
    list: Object,
    orderField: String,
    orderDir: String,
});

let dir = ref(props.orderDir);
let field = ref(props.orderField);
let showModal = ref(false);
let selectedItem = ref({});

watch([dir, field], ([valueOrderDir, valueOrderField]) => {
    Inertia.get(
        "/speeches",
        {
            orderDir: valueOrderDir,
            orderField: valueOrderField,
        },
        { preserveState: true, replace: true }
    );
});
</script>
<template>
    <div class="block md:hidden">
        <div class="mb-4 grid grid-cols-3 justify-between gap-4">
            <div class="relative col-span-2">
                <SelectInput
                    id="privilege"
                    ref="privilegeInput"
                    v-model="field"
                    class="w-full"
                >
                    <option value="">Ordenar por</option>
                    <option value="number">Número</option>
                    <option value="theme">Tema</option>
                    <option value="speeches.updated_at">Atualizado em</option>
                    <option value="max(receive_speakers.date)">
                        Realizado em
                    </option>
                </SelectInput>
            </div>
            <div class="relative grid grid-cols-2 gap-2">
                <PrimaryButton @click="dir = 'asc'">
                    <font-awesome-icon icon="fa-solid fa-angle-up" size="2x" />
                </PrimaryButton>
                <PrimaryButton @click="dir = 'desc'">
                    <font-awesome-icon
                        icon="fa-solid fa-angle-down"
                        size="2x"
                    />
                </PrimaryButton>
            </div>
        </div>
        <div class="mb-4 min-w-full rounded-lg shadow dark:bg-gray-800">
            <div
                class="w-full rounded-lg bg-white dark:border-gray-700 dark:bg-gray-800"
            >
                <div class="flow-root">
                    <ul
                        v-if="list.total"
                        class="divide-y divide-gray-200 dark:divide-gray-700"
                        role="list"
                    >
                        <li
                            v-for="item in list.data"
                            :key="item.id"
                            class="p-4 sm:p-4"
                        >
                            <div class="flex flex-col">
                                <p
                                    class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    {{ item.number }} -
                                    {{ item.theme }}
                                </p>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Atualizado em
                                    {{ item.updated_at }}
                                </p>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{
                                        item.last_made?.date &&
                                        "Realizado em " + item.last_made?.date
                                    }}
                                </p>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{
                                        item.speakers.length === 0 ? "Não" : ""
                                    }}
                                    Preparado
                                </p>
                                <div class="mt-3 flex gap-3">
                                    <EditLink
                                        :href="route('speeches.show', item.id)"
                                        class="grow"
                                    >
                                        Detalhes
                                    </EditLink>
                                    <DeleteLink
                                        class="grow"
                                        @click="
                                            () => {
                                                showModal = true;
                                                selectedItem = item;
                                            }
                                        "
                                    >
                                        Excluir
                                    </DeleteLink>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div v-else class="p-4 text-center sm:p-4">
                        <p>Nenhum registro encontrado!</p>
                    </div>
                </div>
            </div>
        </div>

        <DeleteModal
            :delete-url="`speeches/${selectedItem.id}`"
            :show="showModal"
            :to-delete="`Tema ${selectedItem.number} - ${selectedItem.theme}`"
            @close="showModal = false"
        />
    </div>
</template>
