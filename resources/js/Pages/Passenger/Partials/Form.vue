<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";

defineProps({
    submit: Function,
    form: Object,
    passenger: {
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
            <section class="max-w-xl space-y-6">
                <div>
                    <InputLabel for="name" value="Nome" />

                    <TextInput
                        id="name"
                        ref="nameInput"
                        v-model="form.name"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        type="text"
                    />

                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="doc" value="Documento" />

                    <TextInput
                        id="doc"
                        ref="docInput"
                        v-model="form.doc"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        type="text"
                    />

                    <InputError :message="form.errors.doc" class="mt-2" />
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

                <p class="dark:text-gray-400">
                    <i>{{
                        passenger &&
                        `Atualizado em: ${passenger.updated_at} por ${passenger.user_updated?.name}`
                    }}</i>
                </p>
            </section>
        </div>
        <div class="flex items-center gap-4">
            <PrimaryButton
                :disabled="form.processing"
                type="button"
                @click="back"
                >Voltar
            </PrimaryButton>
            <LoadingButton 
                :loading="form.processing" 
                text="Salvar" 
                loading-text="Salvando..." 
                type="submit"
                variant="primary"
            />
        </div>
    </form>
</template>
