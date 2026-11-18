<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { BookOpenIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import Button from "@/Components/Ui/Button.vue";
import Combobox from "@/Components/Ui/Combobox.vue";
import EmptyState from "@/Components/Ui/EmptyState.vue";
import ConfirmDelete from "@/Components/Ui/ConfirmDelete.vue";
import TablePaginator from "@/Components/TablePaginator.vue";

const props = defineProps({
    speaker: Object,
    /** Paginated talks already linked to the speaker. */
    mySpeeches: Object,
    /** Talks not yet linked (flat array). */
    speeches: Array,
});

const speechForm = useForm({
    speech_id: "",
});

const addSpeech = () => {
    speechForm.post(route("speaker-speech.store", props.speaker.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => speechForm.reset(),
    });
};

const speechToRemove = ref(null);
</script>

<template>
    <div>
    <section aria-labelledby="prepared-title">
        <div class="mb-4 flex items-baseline justify-between">
            <h2 id="prepared-title" class="text-base font-semibold text-stone-900 dark:text-stone-100">Temas preparados</h2>
            <span class="text-sm tabular-nums text-stone-500">{{ mySpeeches.total }}</span>
        </div>

        <form class="mb-4 flex items-start gap-2" novalidate @submit.prevent="addSpeech">
            <div class="min-w-0 flex-1">
                <label class="sr-only" for="add-speech">Adicionar tema</label>
                <Combobox
                    id="add-speech"
                    v-model="speechForm.speech_id"
                    :described-by="speechForm.errors.speech_id ? 'add-speech-error' : undefined"
                    :invalid="!!speechForm.errors.speech_id"
                    :options="speeches"
                    display-key="theme"
                    empty-value=""
                    placeholder="Adicionar tema por número ou nome"
                    prefix-key="number"
                />
                <p v-if="speechForm.errors.speech_id" id="add-speech-error" class="mt-1.5 text-xs font-medium text-red-700 dark:text-red-400">
                    {{ speechForm.errors.speech_id }}
                </p>
            </div>
            <Button :disabled="!speechForm.speech_id" :loading="speechForm.processing" type="submit">Adicionar</Button>
        </form>

        <div class="overflow-hidden rounded-lg border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
            <ul v-if="mySpeeches.data.length" class="divide-y divide-stone-100 dark:divide-stone-800/70">
                <li v-for="speech in mySpeeches.data" :key="speech.id" class="flex items-start gap-3 py-2.5 pl-4 pr-2">
                    <span class="w-8 shrink-0 text-right font-display text-base font-medium leading-6 tabular-nums text-stone-900 dark:text-stone-100">
                        {{ speech.number }}
                    </span>
                    <span class="min-w-0 flex-1 text-sm leading-6 text-stone-800 dark:text-stone-200">{{ speech.theme }}</span>
                    <button
                        :aria-label="`Remover tema ${speech.number}`"
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded text-stone-400 hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-950/50 dark:hover:text-red-400"
                        :title="`Remover tema ${speech.number}`"
                        type="button"
                        @click="speechToRemove = speech"
                    >
                        <XMarkIcon aria-hidden="true" class="h-4 w-4" />
                    </button>
                </li>
            </ul>
            <EmptyState
                v-else
                :icon="BookOpenIcon"
                compact
                description="Os temas adicionados aparecem primeiro ao escolher este orador em um arranjo."
                title="Nenhum tema ainda"
            />
            <TablePaginator :list="mySpeeches" />
        </div>
    </section>

    <ConfirmDelete
        :delete-url="speechToRemove ? route('speaker-speech.destroy', [speechToRemove.pivot.speaker_id, speechToRemove.pivot.speech_id]) : null"
        :show="!!speechToRemove"
        :to-delete="speechToRemove ? `${speechToRemove.number} · ${speechToRemove.theme}` : null"
        confirm-label="Remover"
        subtitle="O tema sai da lista deste orador. O discurso continua cadastrado."
        title="Remover tema?"
        @close="speechToRemove = null"
    />
    </div>
</template>
