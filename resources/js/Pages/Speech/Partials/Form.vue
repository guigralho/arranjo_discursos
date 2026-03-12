<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";

defineProps({
    submit: Function,
    form: Object,
    speech: {
        type: Object,
        default: null,
    },
});

const back = () => {
    window.history.back();
};
</script>

<template>
    <form class="space-y-6" @submit.prevent="submit">
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800 sm:p-6">
            <section class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <InputLabel for="number" value="Número" />
                    <TextInput
                        id="number"
                        ref="numberInput"
                        v-model="form.number"
                        autocomplete="off"
                        class="mt-1.5 block w-full"
                        type="number"
                    />
                    <InputError :message="form.errors.number" class="mt-1.5" />
                </div>

                <div>
                    <InputLabel for="theme" value="Tema" />
                    <TextInput
                        id="theme"
                        ref="themeInput"
                        v-model="form.theme"
                        autocomplete="off"
                        class="mt-1.5 block w-full"
                        type="text"
                    />
                    <InputError :message="form.errors.theme" class="mt-1.5" />
                </div>

                <div v-if="speech && speech.created_at" class="sm:col-span-2">
                    <p class="text-xs text-stone-400 dark:text-slate-500">
                        Criado em: {{ speech.created_at }} por {{ speech.user_created?.name }}
                    </p>
                    <p v-if="speech.updated_at" class="text-xs text-stone-400 dark:text-slate-500">
                        Atualizado em: {{ speech.updated_at }} por {{ speech.user_updated?.name }}
                    </p>
                </div>
            </section>
        </div>
        <div class="flex items-center gap-3">
            <PrimaryButton
                :disabled="form.processing"
                type="button"
                @click="back"
            >
                Voltar
            </PrimaryButton>
            <LoadingButton
                :loading="form.processing"
                color="teal"
                loading-text="Salvando..."
                text="Salvar"
                type="submit"
            />
        </div>
    </form>
</template>
