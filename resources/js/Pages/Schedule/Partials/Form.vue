<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
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
        format: "mm/yyyy",
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
        <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800 sm:p-8">
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <InputLabel for="datepickerId" value="Data *" />

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
                    <InputLabel for="congregation" value="Congregação *" />

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

                <div>
                    <InputLabel for="day" value="Dia da reunião" />

                    <SelectInput
                        id="day"
                        ref="dayInput"
                        v-model="form.day"
                        class="mt-1 block w-full"
                    >
                        <option value="">Selecione...</option>
                        <option value="Sábado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                    </SelectInput>

                    <InputError :message="form.errors.day" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="hour" value="Hora da reunião" />

                    <TextInput
                        id="hour"
                        ref="hourInput"
                        v-model="form.hour"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        type="text"
                    />

                    <InputError :message="form.errors.hour" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="address" value="Endereço" />

                    <TextInput
                        id="address"
                        ref="addressInput"
                        v-model="form.address"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        type="text"
                    />

                    <InputError :message="form.errors.address" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="contact" value="Contato" />

                    <TextInput
                        id="contact"
                        ref="contactInput"
                        v-model="form.contact"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        type="text"
                    />

                    <InputError :message="form.errors.contact" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="phone" value="Telefone" />

                    <TextInput
                        id="phone"
                        ref="phoneInput"
                        v-model="form.phone"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        type="text"
                    />

                    <InputError :message="form.errors.phone" class="mt-2" />
                </div>
            </section>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <PrimaryButton
                :disabled="form.processing"
                :href="route('schedules.index')"
                class="flex-1 sm:flex-none"
                type="button"
                >Voltar</PrimaryButton
            >
            <LoadingButton
                :loading="form.processing"
                loading-text="Salvando..."
                text="Salvar"
                type="submit"
                variant="primary"
            />

            <a
                v-if="data?.id"
                :href="route('download-pdf', data?.id)"
                class="flex-1 rounded-md bg-sky-800 px-4 py-2 text-center text-xs font-semibold uppercase text-white shadow-md hover:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-800 focus:ring-offset-2 focus:ring-offset-sky-200 sm:flex-none"
                type="button"
            >
                <font-awesome-icon icon="fa-solid fa-file-arrow-down" />
                Gerar quadro
            </a>
        </div>
    </form>
</template>
