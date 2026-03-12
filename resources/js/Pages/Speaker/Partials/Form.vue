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
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-stone-200/60 dark:bg-slate-900 dark:ring-slate-800 sm:p-6">
            <section class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <InputLabel for="name" value="Orador" />
                    <TextInput
                        id="name"
                        ref="nameInput"
                        v-model="form.name"
                        autocomplete="off"
                        class="mt-1.5 block w-full"
                        type="text"
                    />
                    <InputError :message="form.errors.name" class="mt-1.5" />
                </div>

                <div>
                    <InputLabel for="privilege" value="Privilégio" />
                    <SelectInput
                        id="privilege"
                        ref="privilegeInput"
                        v-model="form.privilege"
                        class="mt-1.5 block w-full"
                    >
                        <option value="">Selecione...</option>
                        <option value="Ancião">Ancião</option>
                        <option value="Servo">Servo</option>
                    </SelectInput>
                    <InputError :message="form.errors.privilege" class="mt-1.5" />
                </div>

                <div>
                    <InputLabel for="phone" value="Celular" />
                    <TextInput
                        id="phone"
                        ref="phoneInput"
                        v-model="form.phone"
                        v-mask="['(##) ####-####', '(##) #####-####']"
                        autocomplete="off"
                        class="mt-1.5 block w-full"
                        type="tel"
                    />
                    <InputError :message="form.errors.phone" class="mt-1.5" />
                </div>

                <div v-if="data && data.created_at" class="sm:col-span-2">
                    <p class="text-xs text-stone-400 dark:text-slate-500">
                        Criado em: {{ data.created_at }} por {{ data.user_created?.name }}
                    </p>
                    <p v-if="data.updated_at" class="text-xs text-stone-400 dark:text-slate-500">
                        Atualizado em: {{ data.updated_at }} por {{ data.user_updated?.name }}
                    </p>
                </div>
            </section>
        </div>
        <div class="flex items-center gap-3">
            <PrimaryButton
                :disabled="form.processing"
                :href="route('speakers.index')"
                type="button"
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
