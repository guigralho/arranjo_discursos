<script setup>
import { computed, reactive, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { CalendarDaysIcon, ChatBubbleLeftEllipsisIcon, CheckCircleIcon } from "@heroicons/vue/20/solid";
import Button from "@/Components/Ui/Button.vue";
import Combobox from "@/Components/Ui/Combobox.vue";
import EmptyState from "@/Components/Ui/EmptyState.vue";
import Toggle from "@/Components/Ui/Toggle.vue";
import { parseDate, weekdayName } from "@/composables/useDates";
import { vDatepicker } from "@/composables/vDatepicker";

/**
 * Editor for one direction of a monthly arrangement.
 * - receive: visiting speakers (free-text name) coming to our meetings.
 * - send: our speakers (registered) going to the other congregation.
 * The payload posted is unchanged: { weeks: [{ date: 'dd/mm/yyyy', speech, speaker, is_guest }] }.
 */
const props = defineProps({
    variant: {
        type: String,
        required: true,
        validator: (value) => ["receive", "send"].includes(value),
    },
    saveRoute: { type: String, required: true },
    dates: { type: Array, default: () => [] },
    speeches: { type: Array, default: () => [] },
    speakers: { type: Array, default: () => [] },
    schedule: { type: Object, required: true },
});

const emit = defineEmits(["edit-details"]);

const isSend = computed(() => props.variant === "send");

const form = useForm({ weeks: [] });

const fillWeeks = () => {
    form.weeks = props.dates.map((item) => ({
        date: item.date,
        speech: item.speech_id ?? "",
        speaker: isSend.value ? item.speaker_id ?? "" : item.speaker ?? "",
        is_guest: !!item.is_guest,
    }));
    form.defaults();
};

watch(() => props.dates, fillWeeks, { immediate: true });

const isSubmitting = ref(false);

const submit = () => {
    isSubmitting.value = true;
    form.post(props.saveRoute, {
        preserveScroll: true,
        preserveState: true,
        onFinish: () => (isSubmitting.value = false),
    });
};

const discard = () => {
    form.reset();
    form.clearErrors();
};

/* ---------- Talks prepared by each speaker (send only) ---------- */
const preparedBySpeaker = reactive({});
const loadingSpeaker = reactive({});
const failedSpeaker = reactive({});

const loadPreparedSpeeches = async (speakerId) => {
    if (!speakerId || preparedBySpeaker[speakerId] || loadingSpeaker[speakerId]) {
        return;
    }
    loadingSpeaker[speakerId] = true;
    failedSpeaker[speakerId] = false;
    try {
        const response = await axios.get(route("speakers.get-speeches", speakerId));
        preparedBySpeaker[speakerId] = response.data.map((speech) => speech.id);
    } catch (error) {
        failedSpeaker[speakerId] = true;
    } finally {
        loadingSpeaker[speakerId] = false;
    }
};

watch(
    () => (isSend.value ? form.weeks.map((week) => week.speaker) : []),
    (speakerIds) => speakerIds.forEach((id) => loadPreparedSpeeches(id)),
    { immediate: true }
);

const speakerById = computed(() => Object.fromEntries(props.speakers.map((speaker) => [speaker.id, speaker])));
const speechById = computed(() => Object.fromEntries(props.speeches.map((speech) => [speech.id, speech])));

const firstName = (name) => String(name ?? "").split(" ")[0];

const speechOptionsFor = (week) => {
    if (!isSend.value || !week.speaker || !preparedBySpeaker[week.speaker]) {
        return props.speeches;
    }
    const preparedIds = new Set(preparedBySpeaker[week.speaker]);
    const speakerName = firstName(speakerById.value[week.speaker]?.name);
    const prepared = props.speeches
        .filter((speech) => preparedIds.has(speech.id))
        .map((speech) => ({ ...speech, group: `Preparados por ${speakerName}` }));
    const others = props.speeches
        .filter((speech) => !preparedIds.has(speech.id))
        .map((speech) => ({ ...speech, group: "Todos os discursos" }));
    return [...prepared, ...others];
};

const preparedHint = (week) => {
    if (!isSend.value || !week.speaker) {
        return null;
    }
    if (loadingSpeaker[week.speaker]) {
        return "Carregando temas preparados…";
    }
    if (failedSpeaker[week.speaker]) {
        return "Não foi possível carregar os temas deste orador.";
    }
    const count = preparedBySpeaker[week.speaker]?.length;
    if (count === 0) {
        return "Este orador ainda não tem temas cadastrados.";
    }
    return null;
};

/* ---------- Row helpers ---------- */
const isComplete = (week) => !!week.speaker && !!week.speech;
const completeCount = computed(() => form.weeks.filter(isComplete).length);

const weekdayFor = (week) => weekdayName(parseDate(week.date));

const whatsappLink = (week) => {
    const speaker = speakerById.value[week.speaker];
    const speech = speechById.value[week.speech];
    const phone = String(speaker?.phone ?? "").replace(/\D/g, "");
    if (!speaker || !speech || !phone) {
        return null;
    }
    const schedule = props.schedule;
    const message = [
        `*Arranjo de discurso - ${week.date}*`,
        `*Congregação:* ${schedule.congregation}`,
        `*Endereço:* ${schedule.address}`,
        `*Horário:* ${schedule.hour}`,
        `*Contato:* ${schedule.contact} - ${schedule.phone}`,
        `*Tema:* ${speech.number} - ${speech.theme}`,
        "",
        "Por favor, me confirme se poderá fazer o discurso nesse dia.",
    ].join("\n");
    return `https://api.whatsapp.com/send?phone=55${phone}&text=${encodeURIComponent(message)}`;
};

const hasSpeakerWithoutPhone = (week) =>
    isSend.value && week.speaker && week.speech && !String(speakerById.value[week.speaker]?.phone ?? "").replace(/\D/g, "");

const idFor = (field, index) => `${props.variant}-${field}-${index}`;

defineExpose({
    isDirty: computed(() => form.isDirty),
    isSubmitting,
});
</script>

<template>
    <div>
        <EmptyState
            v-if="form.weeks.length === 0"
            :icon="CalendarDaysIcon"
            :description="
                isSend
                    ? 'As datas de envio são geradas a partir do dia da reunião da outra congregação.'
                    : 'As datas de recebimento são geradas a partir do dia de reunião definido no seu perfil.'
            "
            :title="isSend ? 'Sem datas para enviar' : 'Sem datas para receber'"
            class="rounded-lg border border-dashed border-stone-300 dark:border-stone-700"
        >
            <Button v-if="isSend" size="sm" variant="secondary" @click="emit('edit-details')">Definir dia da reunião</Button>
            <Button v-else :href="route('profile.edit')" size="sm" variant="secondary">Abrir meu perfil</Button>
        </EmptyState>

        <form v-else novalidate @submit.prevent="submit">
            <div
                class="overflow-visible rounded-lg border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900"
            >
                <div
                    aria-hidden="true"
                    class="hidden grid-cols-[1.25rem_10.5rem_minmax(0,1fr)_minmax(0,1.5fr)_8.5rem] gap-4 border-b border-stone-200 px-5 py-2.5 text-xs font-medium text-stone-500 dark:border-stone-800 dark:text-stone-400 lg:grid"
                >
                    <span />
                    <span>Data</span>
                    <span>{{ isSend ? "Nosso orador" : "Orador visitante" }}</span>
                    <span>Tema</span>
                    <span />
                </div>

                <ol class="divide-y divide-stone-200 dark:divide-stone-800">
                    <li
                        v-for="(week, index) in form.weeks"
                        :key="index"
                        class="grid gap-x-4 gap-y-3 px-4 py-4 sm:px-5 lg:grid-cols-[1.25rem_10.5rem_minmax(0,1fr)_minmax(0,1.5fr)_8.5rem] lg:items-start"
                    >
                        <!-- Status -->
                        <div class="hidden pt-2.5 lg:block">
                            <CheckCircleIcon
                                v-if="isComplete(week)"
                                aria-label="Completa"
                                class="h-5 w-5 text-pine-600 dark:text-pine-400"
                                role="img"
                            />
                            <span
                                v-else
                                aria-label="A definir"
                                class="mt-0.5 block h-4 w-4 rounded-full border-2 border-dashed border-clay-400 dark:border-clay-500"
                                role="img"
                            />
                        </div>

                        <!-- Date -->
                        <div>
                            <div class="mb-1.5 flex items-center justify-between lg:hidden">
                                <label :for="idFor('date', index)" class="text-sm font-medium text-stone-800 dark:text-stone-200">
                                    Data
                                </label>
                                <span
                                    :class="isComplete(week) ? 'text-pine-700 dark:text-pine-400' : 'text-clay-700 dark:text-clay-300'"
                                    class="text-xs font-medium"
                                    >{{ isComplete(week) ? "Completa" : "A definir" }}</span
                                >
                            </div>
                            <input
                                :id="idFor('date', index)"
                                :aria-describedby="form.errors[`weeks.${index}.date`] ? idFor('date-error', index) : idFor('weekday', index)"
                                :aria-invalid="form.errors[`weeks.${index}.date`] ? 'true' : undefined"
                                aria-label="Data"
                                v-model="week.date"
                                v-datepicker="{ format: 'dd/mm/yyyy', language: 'pt' }"
                                autocomplete="off"
                                class="field h-10 tabular-nums"
                                inputmode="numeric"
                                placeholder="dd/mm/aaaa"
                                required
                                type="text"
                            />
                            <p :id="idFor('weekday', index)" class="mt-1 text-xs capitalize text-stone-500 dark:text-stone-400">
                                {{ weekdayFor(week) }}
                            </p>
                            <p
                                v-if="form.errors[`weeks.${index}.date`]"
                                :id="idFor('date-error', index)"
                                class="mt-1 text-xs font-medium text-red-700 dark:text-red-400"
                            >
                                {{ form.errors[`weeks.${index}.date`] }}
                            </p>
                        </div>

                        <!-- Speaker -->
                        <div class="min-w-0">
                            <label
                                :for="idFor('speaker', index)"
                                class="mb-1.5 block text-sm font-medium text-stone-800 dark:text-stone-200 lg:sr-only"
                            >
                                {{ isSend ? "Nosso orador" : "Orador visitante" }}
                            </label>
                            <Combobox
                                v-if="isSend"
                                :id="idFor('speaker', index)"
                                v-model="week.speaker"
                                :options="speakers"
                                empty-value=""
                                placeholder="Escolher orador"
                            />
                            <input
                                v-else
                                :id="idFor('speaker', index)"
                                v-model="week.speaker"
                                autocomplete="off"
                                class="field h-10"
                                placeholder="Nome do orador"
                                type="text"
                            />
                            <div v-if="!isSend" class="mt-2">
                                <Toggle v-model="week.is_guest" label="É um convite" />
                            </div>
                        </div>

                        <!-- Talk -->
                        <div class="min-w-0">
                            <label
                                :for="idFor('speech', index)"
                                class="mb-1.5 block text-sm font-medium text-stone-800 dark:text-stone-200 lg:sr-only"
                            >
                                Tema
                            </label>
                            <Combobox
                                :id="idFor('speech', index)"
                                v-model="week.speech"
                                :described-by="preparedHint(week) ? idFor('speech-hint', index) : undefined"
                                :options="speechOptionsFor(week)"
                                display-key="theme"
                                empty-value=""
                                placeholder="Buscar por número ou tema"
                                prefix-key="number"
                            />
                            <p
                                v-if="preparedHint(week)"
                                :id="idFor('speech-hint', index)"
                                class="mt-1 text-xs text-stone-500 dark:text-stone-400"
                            >
                                {{ preparedHint(week) }}
                            </p>
                        </div>

                        <!-- Extra -->
                        <div class="flex items-center lg:justify-end lg:pt-0.5">
                            <template v-if="isSend">
                                <Button
                                    v-if="whatsappLink(week)"
                                    :href="whatsappLink(week)"
                                    external
                                    rel="noopener"
                                    size="sm"
                                    target="_blank"
                                    variant="secondary"
                                >
                                    <template #icon>
                                        <ChatBubbleLeftEllipsisIcon aria-hidden="true" class="h-4 w-4 text-pine-700 dark:text-pine-400" />
                                    </template>
                                    WhatsApp
                                </Button>
                                <span
                                    v-else-if="hasSpeakerWithoutPhone(week)"
                                    class="text-xs text-stone-500 dark:text-stone-400"
                                >
                                    Orador sem telefone
                                </span>
                            </template>
                        </div>
                    </li>
                </ol>
            </div>

            <!-- Save bar -->
            <div
                class="sticky bottom-[4.5rem] z-20 mt-4 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-stone-200 bg-white/95 px-4 py-3 shadow-float backdrop-blur dark:border-stone-800 dark:bg-stone-900/95 lg:bottom-4"
            >
                <p class="text-sm text-stone-600 dark:text-stone-400">
                    <span class="font-medium tabular-nums text-stone-900 dark:text-stone-100">{{ completeCount }}</span>
                    de <span class="tabular-nums">{{ form.weeks.length }}</span> completas
                    <span v-if="form.isDirty" class="ml-2 inline-flex items-center gap-1.5 font-medium text-clay-700 dark:text-clay-300">
                        <span aria-hidden="true" class="h-1.5 w-1.5 rounded-full bg-clay-500" />
                        Alterações não salvas
                    </span>
                </p>
                <div class="flex items-center gap-2">
                    <Button v-if="form.isDirty" :disabled="form.processing" size="sm" variant="ghost" @click="discard">
                        Descartar
                    </Button>
                    <Button :disabled="!form.isDirty" :loading="form.processing" loading-text="Salvando…" size="sm" type="submit">
                        Salvar {{ isSend ? "envios" : "recebimentos" }}
                    </Button>
                </div>
            </div>
        </form>
    </div>
</template>
