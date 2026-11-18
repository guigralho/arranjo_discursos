<script setup>
import { computed, ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { ArrowDownTrayIcon, PencilSquareIcon, PlusIcon, TrashIcon, UserGroupIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import PageHeader from "@/Components/Ui/PageHeader.vue";
import Button from "@/Components/Ui/Button.vue";
import IconButton from "@/Components/Ui/IconButton.vue";
import SearchField from "@/Components/Ui/SearchField.vue";
import SortHeader from "@/Components/Ui/SortHeader.vue";
import SortMenu from "@/Components/Ui/SortMenu.vue";
import EmptyState from "@/Components/Ui/EmptyState.vue";
import ConfirmDelete from "@/Components/Ui/ConfirmDelete.vue";
import PhoneLink from "@/Components/Ui/PhoneLink.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TablePaginator from "@/Components/TablePaginator.vue";
import { useDebounceSearch } from "@/composables/useDebounceSearch";
import { usePersistedSelection } from "@/composables/usePersistedSelection";
import { formatDate } from "@/composables/useDates";

const props = defineProps({
    name: String,
    list: Object,
    filters: Object,
});

const { filters, isLoading, hasActiveFilters, toggleOrder, setOrder, resetFilters } = useDebounceSearch("/speakers", {
    page: props.filters.page,
    search: props.filters.search,
    orderDir: props.filters.orderDir,
    orderField: props.filters.orderField,
});

const { selectedIds, clearSelection } = usePersistedSelection("speakers-selection");

const LAST_SPEECH_FIELD = "max(send_speakers.date)";

const sortOptions = [
    { field: "name", label: "Nome" },
    { field: "privilege", label: "Privilégio" },
    { field: LAST_SPEECH_FIELD, label: "Último discurso", ascLabel: "(mais antigo)", descLabel: "(mais recente)" },
];

const speakers = computed(() => props.list.data ?? []);
const pageIds = computed(() => speakers.value.map((speaker) => speaker.id));

const isPageSelected = computed(
    () => pageIds.value.length > 0 && pageIds.value.every((id) => selectedIds.value.includes(id))
);
const isPagePartiallySelected = computed(
    () => !isPageSelected.value && pageIds.value.some((id) => selectedIds.value.includes(id))
);

const togglePage = () => {
    if (isPageSelected.value) {
        selectedIds.value = selectedIds.value.filter((id) => !pageIds.value.includes(id));
    } else {
        selectedIds.value = [...new Set([...selectedIds.value, ...pageIds.value])];
    }
};

const downloadUrl = computed(() => route("speakers.download-speeches", { ids: selectedIds.value }));

const lastSpeechLabel = (speaker) => formatDate(speaker.last_speech_made?.date) || null;

const speakerToDelete = ref(null);
</script>

<template>
    <Head :title="name" />

    <PageHeader :title="name">
        <template #meta>
            <span>Oradores da congregação e os temas que cada um preparou.</span>
        </template>
        <template #actions>
            <Button :href="route('speakers.create')">
                <template #icon><PlusIcon aria-hidden="true" class="h-4 w-4" /></template>
                Novo orador
            </Button>
        </template>
    </PageHeader>

    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center">
        <SearchField v-model="filters.search" class="sm:max-w-sm" label="Buscar por nome ou privilégio" placeholder="Nome ou privilégio" />
        <Button v-if="hasActiveFilters" variant="ghost" @click="resetFilters">
            <template #icon><XMarkIcon aria-hidden="true" class="h-4 w-4" /></template>
            Limpar
        </Button>
        <SortMenu
            id="speakers-sort"
            :options="sortOptions"
            :order-dir="filters.orderDir"
            :order-field="filters.orderField"
            class="md:hidden"
            @change="setOrder"
        />
    </div>

    <section
        :aria-busy="isLoading ? 'true' : 'false'"
        :class="isLoading ? 'opacity-60' : ''"
        class="overflow-hidden rounded-lg border border-stone-200 bg-white transition-opacity dark:border-stone-800 dark:bg-stone-900"
    >
        <template v-if="speakers.length">
            <table class="hidden w-full text-sm md:table">
                <thead class="border-b border-stone-200 text-xs font-medium text-stone-500 dark:border-stone-800 dark:text-stone-400">
                    <tr>
                        <th class="w-12 py-2.5 pl-5 pr-2 text-left" scope="col">
                            <input
                                :checked="isPageSelected"
                                :indeterminate="isPagePartiallySelected"
                                aria-label="Selecionar todos desta página"
                                class="h-4 w-4 rounded border-stone-400 text-pine-700 focus:ring-pine-600 dark:border-stone-600 dark:bg-stone-900 dark:text-pine-500"
                                type="checkbox"
                                @change="togglePage"
                            />
                        </th>
                        <SortHeader
                            :order-dir="filters.orderDir"
                            :order-field="filters.orderField"
                            class="px-3 py-2.5"
                            field="name"
                            label="Nome"
                            @sort="toggleOrder"
                        />
                        <SortHeader
                            :order-dir="filters.orderDir"
                            :order-field="filters.orderField"
                            class="w-32 px-3 py-2.5"
                            field="privilege"
                            label="Privilégio"
                            @sort="toggleOrder"
                        />
                        <th class="w-44 px-3 py-2.5 text-left" scope="col">Telefone</th>
                        <SortHeader
                            :field="LAST_SPEECH_FIELD"
                            :order-dir="filters.orderDir"
                            :order-field="filters.orderField"
                            class="w-40 px-3 py-2.5"
                            label="Último discurso"
                            @sort="toggleOrder"
                        />
                        <th class="w-24 px-3 py-2.5" scope="col"><span class="sr-only">Ações</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-stone-800/70">
                    <tr
                        v-for="speaker in speakers"
                        :key="speaker.id"
                        :class="selectedIds.includes(speaker.id) ? 'bg-pine-50/50 dark:bg-pine-950/30' : 'hover:bg-stone-50 dark:hover:bg-stone-800/40'"
                    >
                        <td class="py-3 pl-5 pr-2">
                            <Checkbox
                                :id="`speaker-select-${speaker.id}`"
                                v-model="selectedIds"
                                :aria-label="`Selecionar ${speaker.name}`"
                                :value="speaker.id"
                            />
                        </td>
                        <td class="px-3 py-3">
                            <Link
                                :href="route('speakers.show', speaker.id)"
                                class="font-medium text-stone-900 hover:underline dark:text-stone-100"
                                >{{ speaker.name }}</Link
                            >
                        </td>
                        <td class="px-3 py-3 text-stone-600 dark:text-stone-400">{{ speaker.privilege }}</td>
                        <td class="px-3 py-3"><PhoneLink :phone="speaker.phone" /></td>
                        <td class="px-3 py-3 tabular-nums text-stone-600 dark:text-stone-400">
                            {{ lastSpeechLabel(speaker) ?? "—" }}
                        </td>
                        <td class="px-3 py-1.5 text-right">
                            <div class="inline-flex">
                                <IconButton
                                    :href="route('speakers.show', speaker.id)"
                                    :icon="PencilSquareIcon"
                                    :label="`Editar ${speaker.name}`"
                                />
                                <IconButton
                                    :icon="TrashIcon"
                                    :label="`Excluir ${speaker.name}`"
                                    tone="danger"
                                    @click="speakerToDelete = speaker"
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <ul class="divide-y divide-stone-100 dark:divide-stone-800/70 md:hidden">
                <li
                    v-for="speaker in speakers"
                    :key="speaker.id"
                    :class="selectedIds.includes(speaker.id) ? 'bg-pine-50/50 dark:bg-pine-950/30' : ''"
                    class="flex items-start gap-3 py-3 pl-4 pr-2"
                >
                    <Checkbox
                        :id="`speaker-select-m-${speaker.id}`"
                        v-model="selectedIds"
                        :aria-label="`Selecionar ${speaker.name}`"
                        :value="speaker.id"
                        class="mt-1"
                    />
                    <Link :href="route('speakers.show', speaker.id)" class="min-w-0 flex-1">
                        <span class="block break-words font-medium text-stone-900 dark:text-stone-100">{{ speaker.name }}</span>
                        <span class="mt-0.5 flex flex-wrap gap-x-3 text-xs text-stone-500 dark:text-stone-400">
                            <span>{{ speaker.privilege }}</span>
                            <span v-if="lastSpeechLabel(speaker)">Último: <span class="tabular-nums">{{ lastSpeechLabel(speaker) }}</span></span>
                        </span>
                    </Link>
                    <IconButton
                        :icon="TrashIcon"
                        :label="`Excluir ${speaker.name}`"
                        tone="danger"
                        @click="speakerToDelete = speaker"
                    />
                </li>
            </ul>

            <TablePaginator :list="list" />
        </template>

        <EmptyState
            v-else-if="hasActiveFilters"
            :icon="UserGroupIcon"
            description="Nenhum orador corresponde à busca."
            title="Nada encontrado"
        >
            <Button size="sm" variant="secondary" @click="resetFilters">Limpar busca</Button>
        </EmptyState>
        <EmptyState v-else :icon="UserGroupIcon" description="Cadastre os oradores da congregação." title="Nenhum orador ainda">
            <Button :href="route('speakers.create')" size="sm">Novo orador</Button>
        </EmptyState>
    </section>

    <!-- Selection bar -->
    <transition
        enter-active-class="transition duration-150 ease-out"
        enter-from-class="translate-y-3 opacity-0"
        leave-active-class="transition duration-100 ease-in"
        leave-to-class="translate-y-3 opacity-0"
    >
        <div
            v-if="selectedIds.length > 0"
            aria-live="polite"
            class="fixed inset-x-0 bottom-[4.75rem] z-30 flex justify-center px-4 lg:bottom-6 lg:pl-60"
        >
            <div
                class="flex w-full max-w-xl items-center gap-2 rounded-lg bg-stone-900 py-2 pl-4 pr-2 text-sm text-stone-50 shadow-float dark:bg-stone-100 dark:text-stone-900"
            >
                <p class="flex-1">
                    <span class="font-semibold tabular-nums">{{ selectedIds.length }}</span>
                    {{ selectedIds.length === 1 ? "orador selecionado" : "oradores selecionados" }}
                </p>
                <button
                    class="h-8 rounded-md px-3 text-stone-300 hover:bg-white/10 hover:text-white dark:text-stone-600 dark:hover:bg-black/5 dark:hover:text-stone-900"
                    type="button"
                    @click="clearSelection"
                >
                    Limpar
                </button>
                <a
                    :href="downloadUrl"
                    class="inline-flex h-8 items-center gap-1.5 rounded-md bg-white px-3 font-medium text-stone-900 hover:bg-stone-200 dark:bg-stone-900 dark:text-stone-50 dark:hover:bg-stone-700"
                >
                    <ArrowDownTrayIcon aria-hidden="true" class="h-4 w-4" />
                    <span class="hidden sm:inline">Baixar temas (Excel)</span>
                    <span class="sm:hidden">Baixar temas</span>
                </a>
            </div>
        </div>
    </transition>

    <ConfirmDelete
        :delete-url="speakerToDelete ? route('speakers.destroy', speakerToDelete.id) : null"
        :show="!!speakerToDelete"
        :to-delete="speakerToDelete?.name"
        title="Excluir orador?"
        @close="speakerToDelete = null"
    />
</template>
