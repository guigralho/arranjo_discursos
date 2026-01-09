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
        <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800 sm:p-8">
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <InputLabel for="number" value="Número" />

                    <TextInput
                        id="number"
                        ref="numberInput"
                        v-model="form.number"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        type="number"
                    />

                    <InputError :message="form.errors.number" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="number" value="Tema" />

                    <TextInput
                        id="theme"
                        ref="themeInput"
                        v-model="form.theme"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        type="text"
                    />

                    <InputError :message="form.errors.theme" class="mt-2" />
                </div>

                <div>
                    <p class="text-sm dark:text-gray-400">
                        <i>{{
                            speech &&
                            speech.created_at &&
                            `Criado em: ${speech.created_at} por ${speech.user_created?.name}`
                        }}</i>
                    </p>
                    <p class="text-sm dark:text-gray-400">
                        <i>{{
                            speech &&
                            speech.updated_at &&
                            `Atualizado em: ${speech.updated_at} por ${speech.user_updated?.name}`
                        }}</i>
                    </p>
                </div>
            </section>
        </div>
        <div class="flex items-center gap-4">
            <PrimaryButton
                :disabled="form.processing"
                type="button"
                @click="back"
                >Voltar</PrimaryButton
            >
            <LoadingButton
                :loading="form.processing"
                color="green"
                loading-text="Salvando..."
                text="Salvar"
                type="submit"
            />
        </div>
    </form>
</template>
