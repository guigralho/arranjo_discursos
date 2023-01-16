<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import SaveButton from "@/Components/Buttons/SaveButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Datepicker from "flowbite-datepicker/Datepicker";
import { onMounted } from "vue";

const props = defineProps({
    submit: Function,
    form: Object,
    data: {
        type: Object,
        default: null,
    },
});

onMounted(() => {
    // the DOM element will be assigned to the ref after initial render
    new Datepicker(document.getElementById("datepickerId"), {
        format: "dd/mm/yyyy",
        startView: 1,
        pickLevel: 1,
        autohide: true,
    });
});

const changeVal = (val) => {
    props.form.formatted_month = val;
};
</script>

<template>
    <form class="space-y-6" @submit.prevent="submit">
        <div class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8">
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <InputLabel for="datepickerId" value="Data" />

                    <TextInput
                        id="datepickerId"
                        ref="dateInput"
                        v-model="form.formatted_month"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        datepicker
                        type="text"
                        @focusout="changeVal($event.target.value)"
                    />

                    <InputError
                        :message="form.errors.formatted_month"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel for="congregation" value="Congregação" />

                    <TextInput
                        id="congregation"
                        ref="congregationInput"
                        v-model="form.congregation"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        type="text"
                    />

                    <InputError
                        :message="form.errors.congregation"
                        class="mt-2"
                    />
                </div>

                <p class="dark:text-gray-400">
                    <i>{{
                        data &&
                        `Atualizado em: ${data.updated_at} por ${data.user_updated?.name}`
                    }}</i>
                </p>
            </section>
        </div>
        <div class="flex items-center gap-4">
            <PrimaryButton
                :disabled="form.processing"
                :href="route('schedules.index')"
                class="flex-1 sm:flex-none"
                type="button"
                >Voltar</PrimaryButton
            >
            <SaveButton :disabled="form.processing" class="flex-1 sm:flex-none">
                <p v-if="!form.processing">Salvar</p>
                <p v-else>
                    <font-awesome-icon icon="fa-solid fa-spinner" spin />
                    Aguarde...
                </p>
            </SaveButton>
        </div>
    </form>
</template>
