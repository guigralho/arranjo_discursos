<script setup>
import { computed, ref } from "vue";
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
} from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
    modelValue: { type: [String, Number], default: null },
    /** Options; an option may carry a `group` label to render grouped sections. */
    options: { type: Array, required: true },
    displayKey: { type: String, default: "name" },
    valueKey: { type: String, default: "id" },
    /** Optional secondary text key (e.g. talk number), searched and shown in muted tone. */
    prefixKey: { type: String, default: null },
    placeholder: { type: String, default: "Selecione..." },
    disabled: { type: Boolean, default: false },
    /** Value emitted when the selection is cleared. */
    emptyValue: { type: [String, Number], default: null },
    id: { type: String, default: undefined },
    invalid: { type: Boolean, default: false },
    describedBy: { type: String, default: undefined },
    maxResults: { type: Number, default: 80 },
});

const emit = defineEmits(["update:modelValue"]);

const query = ref("");
const toggleButton = ref(null);

const normalize = (text) =>
    String(text ?? "")
        .normalize("NFD")
        .replace(/[̀-ͯ]/g, "")
        .toLowerCase();

const labelFor = (option) =>
    option
        ? props.prefixKey && option[props.prefixKey] !== undefined
            ? `${option[props.prefixKey]} · ${option[props.displayKey]}`
            : String(option[props.displayKey] ?? "")
        : "";

const selectedOption = computed(() =>
    props.options.find((option) => option[props.valueKey] == props.modelValue)
);

const filteredOptions = computed(() => {
    const term = normalize(query.value.trim());
    const matches = term
        ? props.options.filter((option) => normalize(labelFor(option)).includes(term))
        : props.options;
    return matches.slice(0, term ? props.maxResults : props.options.length);
});

const hasMoreThanShown = computed(
    () =>
        query.value.trim() !== "" &&
        props.options.filter((option) =>
            normalize(labelFor(option)).includes(normalize(query.value.trim()))
        ).length > props.maxResults
);

const selection = computed({
    get: () => (selectedOption.value ? selectedOption.value[props.valueKey] : null),
    set: (value) => {
        query.value = "";
        emit("update:modelValue", value === null || value === undefined ? props.emptyValue : value);
    },
});

const displayValue = () => labelFor(selectedOption.value);

const showGroupHeader = (index) => {
    const option = filteredOptions.value[index];
    if (!option.group) {
        return false;
    }
    return index === 0 || filteredOptions.value[index - 1].group !== option.group;
};
</script>

<template>
    <Combobox v-slot="{ open }" v-model="selection" :disabled="disabled" as="div" class="relative" nullable>
        <div class="relative">
            <ComboboxInput
                :id="id"
                :aria-describedby="describedBy"
                :aria-invalid="invalid ? 'true' : undefined"
                :display-value="displayValue"
                :placeholder="placeholder"
                autocomplete="off"
                class="field h-10 truncate pr-9"
                @change="query = $event.target.value"
                @click="!open && toggleButton?.$el?.click()"
            />
            <ComboboxButton
                ref="toggleButton"
                aria-label="Mostrar opções"
                class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 text-stone-400 hover:text-stone-600 dark:hover:text-stone-200"
            >
                <ChevronUpDownIcon aria-hidden="true" class="h-5 w-5" />
            </ComboboxButton>
        </div>

        <transition
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
            @after-leave="query = ''"
        >
            <ComboboxOptions
                class="absolute z-30 mt-1 max-h-72 w-full min-w-[16rem] overflow-auto rounded-md border border-stone-200 bg-white py-1 text-sm shadow-float focus:outline-none dark:border-stone-700 dark:bg-stone-900"
            >
                <li
                    v-if="filteredOptions.length === 0"
                    class="px-3 py-2.5 text-stone-500 dark:text-stone-400"
                >
                    Nada encontrado para “{{ query }}”.
                </li>
                <template v-for="(option, index) in filteredOptions" :key="option[valueKey]">
                    <li
                        v-if="showGroupHeader(index)"
                        aria-hidden="true"
                        class="eyebrow px-3 pb-1 pt-2.5 first:pt-1.5"
                    >
                        {{ option.group }}
                    </li>
                    <ComboboxOption
                        v-slot="{ active, selected }"
                        :value="option[valueKey]"
                        as="template"
                    >
                        <li
                            :class="[
                                active
                                    ? 'bg-pine-50 text-pine-900 dark:bg-pine-950 dark:text-pine-100'
                                    : 'text-stone-800 dark:text-stone-200',
                            ]"
                            class="relative flex cursor-default select-none items-baseline gap-2 py-2 pl-8 pr-3"
                        >
                            <CheckIcon
                                v-if="selected"
                                aria-hidden="true"
                                class="absolute left-2 top-2.5 h-4 w-4 text-pine-700 dark:text-pine-400"
                            />
                            <span
                                v-if="prefixKey"
                                class="w-8 shrink-0 text-right font-medium tabular-nums text-stone-500 dark:text-stone-400"
                                >{{ option[prefixKey] }}</span
                            >
                            <span :class="selected ? 'font-medium' : ''" class="min-w-0">
                                {{ option[displayKey] }}
                            </span>
                        </li>
                    </ComboboxOption>
                </template>
                <li
                    v-if="hasMoreThanShown"
                    class="px-3 py-2 text-xs text-stone-500 dark:text-stone-400"
                >
                    Refine a busca para ver mais resultados.
                </li>
            </ComboboxOptions>
        </transition>
    </Combobox>
</template>
