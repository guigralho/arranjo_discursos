<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SaveButton from "@/Components/Buttons/SaveButton.vue";

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
        <Transition
            class="transition duration-300 ease-in-out"
            enter-from-class="opacity-0"
            leave-to-class="opacity-0"
        >
            <div
                v-if="form.recentlySuccessful"
                class="bg-green-50 px-5 py-3 sm:rounded-lg"
            >
                <p class="text-bold text-emerald-800">
                    <font-awesome-icon icon="fa-solid fa-circle-check" />
                    Salvo com sucesso!
                </p>
            </div>
        </Transition>
        <div class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8">
            <section class="max-w-xl space-y-6">
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

                <p class="dark:text-gray-400">
                    <i>{{
                        speech &&
                        `Atualizado em: ${speech.updated_at} por ${speech.user_updated?.name}`
                    }}</i>
                </p>
            </section>
        </div>
        <div class="flex items-center gap-4">
            <PrimaryButton
                :disabled="form.processing"
                type="button"
                @click="back"
                >Voltar</PrimaryButton
            >
            <SaveButton :disabled="form.processing">
                <font-awesome-icon
                    v-if="form.processing"
                    icon="fa-solid fa-spinner"
                    spin
                />
                <p v-if="!form.processing">Salvar</p>
                <p v-else>Aguarde...</p>
            </SaveButton>
        </div>
    </form>
</template>
