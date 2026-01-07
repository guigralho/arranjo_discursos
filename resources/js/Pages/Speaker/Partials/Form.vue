<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
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

<script>
import { mask } from "vue-the-mask";
export default {
    directives: { mask },
};
</script>

<template>
    <form class="space-y-6" @submit.prevent="submit">
        <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800 sm:p-8">
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
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
                    <InputLabel for="phone" value="Celular" />

                    <TextInput
                        id="phone"
                        ref="phoneInput"
                        v-model="form.phone"
                        v-mask="['(##) ####-####', '(##) #####-####']"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        type="tel"
                    />

                    <InputError :message="form.errors.phone" class="mt-2" />
                </div>

                <p class="dark:text-gray-400 sm:col-span-2">
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
            <LoadingButton
                :loading="form.processing"
                class="flex-1 sm:flex-none"
                color="green"
                loading-text="Salvando..."
                text="Salvar"
                type="submit"
            />
        </div>
    </form>
</template>
