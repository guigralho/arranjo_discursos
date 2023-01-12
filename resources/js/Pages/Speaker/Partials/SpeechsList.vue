<script setup>
import SaveButton from "@/Components/Buttons/SaveButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SelectInput from "@/Components/SelectInput.vue";
import InputError from "@/Components/InputError.vue";
import TablePaginator from "@/Components/TablePaginator.vue";
import DeleteLink from "@/Components/Buttons/DeleteLink.vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

const props = defineProps({
    speaker: Object,
    mySpeeches: Object,
    speeches: Object,
});

let showModal = ref(false);
let selectedItem = ref({});

const speechFrom = useForm({
    speech_id: "",
});

const submitSpeech = () => {
    speechFrom.post(route("speaker-speech.store", props.speaker.id), {
        preserveScroll: true,
        onSuccess: () => {
            speechFrom.speech_id = "00";
        },
        onError: () => {
            if (speechFrom.errors.speech_id) {
                speechFrom.speech_id = "00";
            }
        },
    });
};
</script>
<template>
    <form class="flex flex-col space-y-6" @submit.prevent="submitSpeech">
        <div class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8">
            <section class="max-w-xl space-y-6">
                <div>
                    <InputLabel for="privilege" value="Temas" />

                    <SelectInput
                        id="privilege"
                        ref="privilegeInput"
                        v-model="speechFrom.speech_id"
                        class="mt-1 block w-full"
                    >
                        <option value="00">Selecione...</option>
                        <option
                            v-for="speech in speeches"
                            :key="speech.id"
                            :value="speech.id"
                        >
                            {{ speech.number }} - {{ speech.theme }}
                        </option>
                    </SelectInput>

                    <InputError
                        :message="speechFrom.errors.speech_id"
                        class="mt-2"
                    />
                </div>
            </section>
        </div>
        <div class="flex items-center gap-4">
            <SaveButton
                :disabled="speechFrom.processing"
                class="flex-1 sm:flex-none"
            >
                <p v-if="!speechFrom.processing">Salvar</p>
                <p v-else>
                    <font-awesome-icon icon="fa-solid fa-spinner" spin />
                    Aguarde...
                </p>
            </SaveButton>
        </div>
    </form>

    <div class="-mx-4 overflow-x-auto px-4 py-4 sm:col-span-2 sm:-mx-8 sm:px-8">
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <table class="min-w-full table-fixed bg-white dark:bg-gray-800">
                <thead>
                    <tr
                        class="font-weight-bold border-b px-10 text-gray-800 dark:border-gray-900 dark:text-white"
                    >
                        <th
                            class="w-2/12 px-5 py-5 text-left text-sm uppercase"
                            scope="col"
                        >
                            Número
                        </th>
                        <th
                            class="px-5 py-5 text-left text-sm uppercase"
                            scope="col"
                        >
                            Tema
                        </th>
                        <th
                            class="w-1/12 px-5 py-5 text-left text-sm uppercase"
                            scope="col"
                        >
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody v-if="mySpeeches.total">
                    <tr
                        v-for="item in mySpeeches.data"
                        :key="item.id"
                        class="font-weight-bold border-b text-gray-800 hover:bg-gray-100 dark:border-gray-900 dark:text-white dark:hover:bg-gray-700"
                    >
                        <td class="whitespace-nowrap px-5 py-5 text-sm">
                            {{ item.number }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-5 text-sm">
                            {{ item.theme }}
                        </td>
                        <td
                            class="space-x-3 whitespace-nowrap px-5 py-5 text-sm"
                        >
                            <DeleteLink
                                @click="
                                    () => {
                                        showModal = true;
                                        selectedItem = item;
                                    }
                                "
                            >
                                <font-awesome-icon icon="fa-solid fa-trash" />
                            </DeleteLink>
                        </td>
                    </tr>
                </tbody>
                <tr
                    v-else
                    class="font-weight-bold border-b text-gray-800 hover:bg-gray-100 dark:border-gray-900 dark:text-white dark:hover:bg-gray-700"
                >
                    <td class="whitespace-nowrap px-5 py-5 text-sm" colspan="3">
                        Nenhum registro encontrado
                    </td>
                </tr>
            </table>

            <TablePaginator :list="mySpeeches" />
        </div>
    </div>

    <DeleteModal
        :delete-url="`/speaker-speech/${selectedItem.pivot?.speaker_id}/${selectedItem.pivot?.speech_id}`"
        :show="showModal"
        :to-delete="selectedItem.theme"
        @close="showModal = false"
    />
</template>
