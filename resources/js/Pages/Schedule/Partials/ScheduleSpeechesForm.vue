<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SelectInput from "@/Components/SelectInput.vue";
import SaveButton from "@/Components/Buttons/SaveButton.vue";
import Datepicker from "flowbite-datepicker/Datepicker";
import { useForm } from "@inertiajs/inertia-vue3";
import { onMounted, onUpdated } from "vue";
import debounce from "lodash/debounce";
import axios from "axios";

const props = defineProps({
    title: String,
    route: String,
    dates: Object,
    speeches: Object,
    speakers: Object,
    schedule: Object,
});

const form = useForm({
    weeks: [],
});

const submit = () => {
    form.post(props.route, {
        preserveScroll: true,
        onSuccess: () => {},
        onError: () => {
            if (form.errors.day) {
                form.reset("day");
            }
        },
    });
};

const fillDates = () => {
    props.dates.map((item, key) => {
        form.weeks.push({
            date: item.date,
            speech: item.speech_id,
            speaker:
                props.speakers === undefined ? item.speaker : item.speaker_id,
        });
    });
};

const getSpeakerSpeeches = debounce(async function (val) {
    const response = await axios.get("/speakers/get-speeches/" + val.value);

    let select = document.getElementById(
        val.id.replaceAll("speaker", "speech")
    );
    let html = "<option value=''>Selecione</option>";
    response.data.map((item) => {
        html += `<option value='${item.id}'>${item.number} - ${item.theme}</option>`;
    });
    select.innerHTML = html;
}, 300);

const generateWhatsappLink = (item) => {
    let speaker = props.speakers.filter(
        (res) => res.id === parseInt(item.speaker)
    )[0];
    let disc = props.speeches.filter(
        (res) => res.id === parseInt(item.speech)
    )[0];

    let link = "";
    if (speaker.phone) {
        let phone = speaker.phone
            .replaceAll("-", "")
            .replaceAll(" ", "")
            .replaceAll("(", "")
            .replaceAll(")", "");

        link = "https://api.whatsapp.com/send?phone=";
        link += `55${phone}`;
        link += `&text=*Arranjo de discurso - ${item.date}*%0a`;
        link += `*Congregação:* ${props.schedule?.congregation}%0a`;
        link += `*Endereço:* ${props.schedule?.address}%0a`;
        link += `*Horário:* ${props.schedule?.hour}%0a`;
        link += `*Contato:* ${props.schedule?.contact} - ${props.schedule?.phone}%0a`;
        link += `*Tema:* ${disc.number} - ${disc.theme}%0a%0a`;
        link += `Por favor, me confirme se poderá fazer o discurso nesse dia.`;
    }

    return link;
};

onMounted(() => {
    fillDates();
});

onUpdated(() => {
    // the DOM element will be assigned to the ref after initial render
    const inputs = document.getElementsByClassName("js-datepicker");
    Array.prototype.forEach.call(inputs, function (el) {
        // Do stuff here
        new Datepicker(el, {
            format: "dd/mm/yyyy",
            autohide: true,
        });
    });
});

const changeVal = (val, item) => {
    item.date = val;
};
</script>
<template>
    <div class="mt-6">
        <h1 class="text-xl font-bold text-gray-700 dark:text-gray-300">
            {{ title }}
        </h1>
        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="(item, key) in form.weeks"
                    :key="key"
                    class="flex flex-col gap-4"
                >
                    <div
                        class="grid grid-cols-1 gap-4 rounded-lg bg-white p-4 shadow dark:bg-gray-800 sm:p-8"
                    >
                        <div>
                            <InputLabel :for="`date${key}`" value="Data" />

                            <TextInput
                                :id="`date${key + title}`"
                                :ref="`date${key}`"
                                v-model="item.date"
                                autocomplete="off"
                                class="js-datepicker mt-1 block w-full"
                                datepicker
                                type="text"
                                @focusout="changeVal($event.target.value, item)"
                            />

                            <InputError
                                :message="form.errors[`weeks.${key}.date`]"
                                class="mt-2"
                            />
                        </div>
                        <div>
                            <InputLabel :for="`speaker${key}`" value="Orador" />

                            <TextInput
                                v-if="speakers === undefined"
                                :id="`speaker${key + title}`"
                                :ref="`speaker${key}`"
                                v-model="item.speaker"
                                autocomplete="off"
                                class="mt-1 block w-full"
                                type="text"
                            />

                            <SelectInput
                                v-else
                                :id="`speaker${key + title}`"
                                :ref="`speaker${key}`"
                                v-model="item.speaker"
                                class="mt-1 block w-full"
                                @change="getSpeakerSpeeches($event.target)"
                            >
                                <option value="">Selecione...</option>
                                <option
                                    v-for="speaker in speakers"
                                    :key="speaker.id"
                                    :value="speaker.id"
                                >
                                    {{ speaker.name }}
                                </option>
                            </SelectInput>

                            <InputError
                                :message="form.errors[`weeks.${key}.speaker`]"
                                class="mt-2"
                            />
                        </div>
                        <div>
                            <InputLabel :for="`speech${key}`" value="Tema" />

                            <SelectInput
                                :id="`speech${key + title}`"
                                :ref="`speech${key}`"
                                v-model="item.speech"
                                class="mt-1 block w-full"
                            >
                                <option value="">Selecione...</option>
                                <option
                                    v-for="speech in speeches"
                                    :key="speech.id"
                                    :value="speech.id"
                                >
                                    {{ speech.number }} - {{ speech.theme }}
                                </option>
                            </SelectInput>

                            <InputError
                                :message="form.errors[`weeks.${key}.speech`]"
                                class="mt-2"
                            />
                        </div>

                        <a
                            v-if="item.speaker && item.speech && schedule"
                            :href="generateWhatsappLink(item)"
                            class="w-fit gap-1 rounded-md border border-transparent bg-green-700 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-green-900 focus:bg-green-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-green-900 sm:grow-0"
                            target="_blank"
                        >
                            <font-awesome-icon
                                icon="fa-brands fa-whatsapp"
                                size="lg"
                            />
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-4">
                <SaveButton
                    :disabled="form.processing"
                    class="flex-1 sm:flex-none"
                >
                    <p v-if="!form.processing">Salvar</p>
                    <p v-else>
                        <font-awesome-icon icon="fa-solid fa-spinner" spin />
                        Aguarde...
                    </p>
                </SaveButton>
                <slot />
            </div>
        </form>
    </div>
</template>
