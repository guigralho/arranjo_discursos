<script setup>
import { computed, onMounted, onUnmounted, ref } from "vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from "@headlessui/vue";
import { DocumentArrowDownIcon, TableCellsIcon } from "@heroicons/vue/20/solid";
import PageHeader from "@/Components/Ui/PageHeader.vue";
import Button from "@/Components/Ui/Button.vue";
import ActionMenu from "@/Components/Ui/ActionMenu.vue";
import ActionMenuItem from "@/Components/Ui/ActionMenuItem.vue";
import Form from "./Partials/Form.vue";
import WeekEditor from "./Partials/WeekEditor.vue";
import { capitalize, monthLabel } from "@/composables/useDates";

const props = defineProps({
    name: String,
    schedule: Object,
    speeches: Array,
    speakers: Array,
});

const form = useForm({
    formatted_month: props.schedule.month_year,
    congregation: props.schedule.congregation,
    day: props.schedule.day ?? "",
    hour: props.schedule.hour,
    address: props.schedule.address,
    contact: props.schedule.contact,
    phone: props.schedule.phone,
});

const submit = () => {
    form.put(route("schedules.update", props.schedule.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => form.defaults(),
    });
};

/* ---------- Tabs (persisted in the URL hash) ---------- */
const tabs = [
    { key: "receber", label: "Receber", hint: "Quem vem até nós" },
    { key: "enviar", label: "Enviar", hint: "Quem vai até eles" },
    { key: "dados", label: "Dados do arranjo", hint: null },
];

const initialTab = Math.max(0, tabs.findIndex((tab) => `#${tab.key}` === window.location.hash));
const selectedTab = ref(initialTab);

const receiveEditor = ref(null);
const sendEditor = ref(null);

const editorForTab = (index) => [receiveEditor.value, sendEditor.value, null][index];
const tabIsDirty = (index) => (index === 2 ? form.isDirty : !!editorForTab(index)?.isDirty);
const hasUnsavedChanges = () => [0, 1, 2].some(tabIsDirty);

const confirmLeaving = () => window.confirm("Há alterações não salvas. Deseja sair mesmo assim?");

const changeTab = (index) => {
    if (index === selectedTab.value) {
        return;
    }
    if (tabIsDirty(selectedTab.value) && !confirmLeaving()) {
        return;
    }
    selectedTab.value = index;
    history.replaceState(history.state, "", `#${tabs[index].key}`);
};

const completion = (rows, speakerKey) => {
    const total = rows?.length ?? 0;
    const done = (rows ?? []).filter((row) => row[speakerKey] && row.speech_id).length;
    return { total, done };
};

const receiveCompletion = computed(() => completion(props.schedule.to_receive, "speaker"));
const sendCompletion = computed(() => completion(props.schedule.to_send, "speaker_id"));
const counts = computed(() => [receiveCompletion.value, sendCompletion.value, null]);

/* ---------- Unsaved-changes guard ---------- */
let removeBeforeListener = null;

const onBeforeUnload = (event) => {
    if (hasUnsavedChanges()) {
        event.preventDefault();
        event.returnValue = "";
    }
};

onMounted(() => {
    window.addEventListener("beforeunload", onBeforeUnload);
    removeBeforeListener = router.on("before", (event) => {
        const visit = event.detail.visit;
        const isOwnSave = visit.method !== "get" || form.processing;
        if (!isOwnSave && hasUnsavedChanges() && !confirmLeaving()) {
            return false;
        }
    });
});

onUnmounted(() => {
    window.removeEventListener("beforeunload", onBeforeUnload);
    removeBeforeListener?.();
});

const monthTitle = computed(() => capitalize(monthLabel(props.schedule.month_year)));
const meetingLabel = computed(() => [props.schedule.day, props.schedule.hour].filter(Boolean).join(" · "));
</script>

<template>
    <Head :title="`${schedule.congregation} · ${schedule.translated_month}`" />

    <PageHeader :back-href="route('schedules.index')" :eyebrow="monthTitle" :title="schedule.congregation" back-label="Arranjos">
        <template #meta>
            <span v-if="meetingLabel">Reunião deles: {{ meetingLabel }}</span>
            <span v-if="schedule.address" class="break-words">{{ schedule.address }}</span>
        </template>
        <template #actions>
            <Button :href="route('download-pdf', schedule.id)" external variant="primary">
                <template #icon><DocumentArrowDownIcon aria-hidden="true" class="h-4 w-4" /></template>
                Gerar quadro
            </Button>
            <ActionMenu label="Exportar">
                <ActionMenuItem :href="route('download-csv', schedule.id)" :icon="TableCellsIcon" external>
                    Arranjo completo (CSV)
                </ActionMenuItem>
                <ActionMenuItem :href="route('download-receive-speakers-csv', schedule.id)" :icon="TableCellsIcon" external>
                    Oradores que recebemos (CSV)
                </ActionMenuItem>
            </ActionMenu>
        </template>
    </PageHeader>

    <TabGroup :selected-index="selectedTab" manual @change="changeTab">
        <TabList
            class="-mx-4 mb-6 flex gap-1 overflow-x-auto overflow-y-hidden border-b border-stone-200 px-4 dark:border-stone-800 sm:mx-0 sm:px-0"
        >
            <Tab v-for="(tab, index) in tabs" :key="tab.key" v-slot="{ selected }" as="template">
                <button
                    :class="
                        selected
                            ? 'border-pine-700 text-stone-900 dark:border-pine-400 dark:text-stone-50'
                            : 'border-transparent text-stone-500 hover:text-stone-800 dark:text-stone-400 dark:hover:text-stone-200'
                    "
                    class="-mb-px flex shrink-0 items-center gap-2 whitespace-nowrap border-b-2 px-3 py-3 text-sm font-medium transition-colors"
                    type="button"
                >
                    {{ tab.label }}
                    <span
                        v-if="counts[index]"
                        :class="
                            counts[index].total > 0 && counts[index].done === counts[index].total
                                ? 'bg-pine-100 text-pine-800 dark:bg-pine-900 dark:text-pine-200'
                                : 'bg-stone-100 text-stone-600 dark:bg-stone-800 dark:text-stone-300'
                        "
                        class="rounded px-1.5 py-0.5 text-xs tabular-nums"
                        >{{ counts[index].done }}/{{ counts[index].total }}</span
                    >
                    <span v-if="tabIsDirty(index)" class="h-1.5 w-1.5 rounded-full bg-clay-500" title="Alterações não salvas">
                        <span class="sr-only">(alterações não salvas)</span>
                    </span>
                </button>
            </Tab>
        </TabList>

        <TabPanels>
            <TabPanel :unmount="false" class="focus:outline-none">
                <p class="mb-4 text-sm text-stone-600 dark:text-stone-400">
                    Oradores visitantes que vêm às nossas reuniões, vindos de {{ schedule.congregation }}.
                </p>
                <WeekEditor
                    ref="receiveEditor"
                    :dates="schedule.to_receive"
                    :save-route="route('schedules.save-receive', schedule.id)"
                    :schedule="schedule"
                    :speeches="speeches"
                    variant="receive"
                />
            </TabPanel>
            <TabPanel :unmount="false" class="focus:outline-none">
                <p class="mb-4 text-sm text-stone-600 dark:text-stone-400">
                    Nossos oradores que vão à reunião de {{ schedule.congregation }}. Ao escolher o orador, os temas que ele preparou aparecem primeiro.
                </p>
                <WeekEditor
                    ref="sendEditor"
                    :dates="schedule.to_send"
                    :save-route="route('schedules.save-send', schedule.id)"
                    :schedule="schedule"
                    :speakers="speakers"
                    :speeches="speeches"
                    variant="send"
                    @edit-details="changeTab(2)"
                />
            </TabPanel>
            <TabPanel :unmount="false" class="focus:outline-none">
                <Form :form="form" :submit="submit" submit-label="Salvar dados" />
                <p class="mt-8 max-w-3xl text-xs text-stone-500 dark:text-stone-400">
                    Criado em {{ schedule.created_at }}<template v-if="schedule.user_created"> por {{ schedule.user_created.name }}</template>.
                    <template v-if="schedule.user_updated">
                        Atualizado em {{ schedule.updated_at }} por {{ schedule.user_updated.name }}.
                    </template>
                </p>
            </TabPanel>
        </TabPanels>
    </TabGroup>
</template>
