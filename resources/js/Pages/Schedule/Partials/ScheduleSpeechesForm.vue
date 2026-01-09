<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SelectInput from "@/Components/SelectInput.vue";
import SaveButton from "@/Components/Buttons/SaveButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Datepicker from "flowbite-datepicker/Datepicker";
import { useForm } from "@inertiajs/inertia-vue3";
import { onMounted, onUpdated } from "vue";
import debounce from "lodash/debounce";
import axios from "axios";

const props = defineProps({
    title: String,
    saveRoute: String,
    dates: Object,
    speeches: Object,
    speakers: Object,
    schedule: Object,
    scheduleId: Number,
});

const form = useForm({
    weeks: [],
});

const submit = () => {
    form.post(props.saveRoute, {
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
            is_guest: item.is_guest || false,
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
    <div class="mt-4 space-y-4">
        <!-- Cabeçalho -->
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ title }}
            </h2>
            <span
                class="rounded-full bg-gray-50 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300"
            >
                {{ form.weeks.length }}
                {{ form.weeks.length === 1 ? "semana" : "semanas" }}
            </span>
        </div>

        <form class="space-y-4" @submit.prevent="submit">
            <!-- Grid de semanas -->
            <div
                class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >
                <div v-if="form.weeks.length === 0">
                    <p class="text-yellow-400 dark:text-yellow-300">
                        <font-awesome-icon
                            class="h-3 w-3"
                            icon="fa-solid fa-exclamation-triangle"
                        />
                        Preencha o dia da reunião!
                    </p>
                </div>
                <div
                    v-for="(item, key) in form.weeks"
                    :key="key"
                    class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800"
                >
                    <!-- Header do card com número da semana -->
                    <div
                        class="mb-3 flex items-center justify-between border-b border-gray-100 pb-2 dark:border-gray-700"
                    >
                        <span
                            class="text-xs font-medium text-gray-500 dark:text-gray-400"
                        >
                            Semana {{ key + 1 }}
                        </span>
                        <a
                            v-if="item.speaker && item.speech && schedule"
                            :href="generateWhatsappLink(item)"
                            class="inline-flex items-center gap-1 rounded-md bg-green-600 px-2 py-1 text-xs font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1"
                            target="_blank"
                            title="Enviar convite via WhatsApp"
                        >
                            <font-awesome-icon
                                class="h-3 w-3"
                                icon="fa-brands fa-whatsapp"
                            />
                            Enviar designação
                        </a>
                    </div>

                    <div class="space-y-3">
                        <!-- Data -->
                        <div>
                            <InputLabel
                                :for="`date${key}`"
                                class="text-xs"
                                value="Data"
                            />
                            <TextInput
                                :id="`date${key + title}`"
                                :ref="`date${key}`"
                                v-model="item.date"
                                autocomplete="off"
                                class="js-datepicker mt-1 block w-full text-sm"
                                datepicker
                                type="text"
                                @focusout="changeVal($event.target.value, item)"
                            />
                            <InputError
                                :message="form.errors[`weeks.${key}.date`]"
                                class="mt-1"
                            />
                        </div>

                        <!-- Orador -->
                        <div>
                            <InputLabel
                                :for="`speaker${key}`"
                                class="text-xs"
                                value="Orador"
                            />
                            <TextInput
                                v-if="speakers === undefined"
                                :id="`speaker${key + title}`"
                                :ref="`speaker${key}`"
                                v-model="item.speaker"
                                autocomplete="off"
                                class="mt-1 block w-full text-sm"
                                placeholder="Nome do orador"
                                type="text"
                            />
                            <SelectInput
                                v-else
                                :id="`speaker${key + title}`"
                                :ref="`speaker${key}`"
                                v-model="item.speaker"
                                class="mt-1 block w-full text-sm"
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
                                class="mt-1"
                            />
                        </div>

                        <!-- Tema -->
                        <div>
                            <InputLabel
                                :for="`speech${key}`"
                                class="text-xs"
                                value="Tema"
                            />
                            <SelectInput
                                :id="`speech${key + title}`"
                                :ref="`speech${key}`"
                                v-model="item.speech"
                                class="mt-1 block w-full text-sm"
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
                                class="mt-1"
                            />
                        </div>

                        <!-- Checkbox para convites (apenas quando title === 'Receber') -->
                        <div
                            v-if="title === 'Receber'"
                            class="flex items-center gap-2 pt-1"
                        >
                            <Checkbox
                                :id="`guest${key + title}`"
                                v-model:checked="item.is_guest"
                                class="rounded text-blue-600"
                            />
                            <InputLabel
                                :for="`guest${key + title}`"
                                class="text-xs text-gray-600 dark:text-gray-400"
                                value="É um convite?"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botões de ação -->
            <div
                v-if="form.weeks.length > 0"
                class="flex items-center justify-between gap-3 rounded-lg bg-gray-50 px-4 py-3 dark:bg-gray-800/50"
            >
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{
                            form.weeks.filter((w) => w.date && w.speaker).length
                        }}
                        de {{ form.weeks.length }} preenchidas
                    </span>
                </div>

                <div class="flex gap-3">
                    <slot />
                    <SaveButton :disabled="form.processing">
                        <span
                            v-if="!form.processing"
                            class="flex items-center gap-2"
                        >
                            <font-awesome-icon
                                class="h-3 w-3"
                                icon="fa-solid fa-check"
                            />
                            Salvar {{ title }}
                        </span>
                        <span v-else class="flex items-center gap-2">
                            <font-awesome-icon
                                class="h-3 w-3"
                                icon="fa-solid fa-spinner"
                                spin
                            />
                            Salvando...
                        </span>
                    </SaveButton>

                    <a
                        v-if="title === 'Receber'"
                        :href="
                            route('download-receive-speakers-csv', scheduleId)
                        "
                        class="items-center gap-1 rounded-md bg-sky-800 px-4 py-2 text-center text-sm font-medium text-white hover:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-800 sm:inline-flex"
                        type="button"
                    >
                        <font-awesome-icon
                            class="h-4 w-4"
                            icon="fa-solid fa-file-arrow-down"
                        />
                        Baixar csv
                    </a>
                </div>
            </div>
        </form>
    </div>
</template>
