<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import SaveButton from "@/Components/Buttons/SaveButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";

defineProps({
    submit: Function,
    form: Object,
    data: {
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
        <div class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8">
            <section class="max-w-xl space-y-6">
                <div>
                    <InputLabel for="privilege" value="Privilégio" />

                    <SelectInput
                        id="privilege"
                        ref="privilegeInput"
                        v-model="form.privilege"
                        class="mt-1 block w-full"
                    >
                        <option value="">Selecione...</option>
                        <option value="Ancião">Ancião</option>
                        <option value="Servo">Servo</option>
                    </SelectInput>

                    <InputError :message="form.errors.privilege" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="name" value="Orador" />

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

                <p class="dark:text-gray-400">
                    <i>{{
                        data &&
                        `Atualizado em: ${data.updated_at} por ${data.user_updated?.name}`
                    }}</i>
                </p>
            </section>
        </div>
        <div class="flex items-center gap-4">
            <PrimaryButton
                :disabled="form.processing"
                :href="route('speakers.index')"
                class="flex-1 sm:flex-none"
                type="button"
                >Voltar</PrimaryButton
            >
            <SaveButton :disabled="form.processing" class="flex-1 sm:flex-none">
                <p v-if="!form.processing">Salvar</p>
                <p v-else>
                    <font-awesome-icon icon="fa-solid fa-spinner" spin />
                    Aguarde...
                </p>
            </SaveButton>
        </div>
    </form>
</template>
