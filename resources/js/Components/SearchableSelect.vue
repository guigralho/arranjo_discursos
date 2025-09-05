<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: null,
  },
  options: {
    type: Array,
    required: true,
  },
  placeholder: {
    type: String,
    default: 'Selecione uma opção...',
  },
  displayKey: {
    type: String,
    default: 'name',
  },
  valueKey: {
    type: String,
    default: 'id',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue'])

const isOpen = ref(false)
const searchQuery = ref('')
const searchInput = ref(null)
const dropdown = ref(null)

const selectedOption = computed(() => {
  return props.options.find(option => option[props.valueKey] == props.modelValue)
})

const filteredOptions = computed(() => {
  if (!searchQuery.value) return props.options

  return props.options.filter((option) =>
    option[props.displayKey]
      .toLowerCase()
      .includes(searchQuery.value.toLowerCase())
  )
})

function selectOption(option) {
  emit('update:modelValue', option[props.valueKey])
  isOpen.value = false
  searchQuery.value = ''
}

function toggleDropdown() {
  if (props.disabled) return
  isOpen.value = !isOpen.value
  if (isOpen.value) {
    setTimeout(() => {
      searchInput.value?.focus()
    }, 100)
  }
}

function closeDropdown() {
  isOpen.value = false
  searchQuery.value = ''
}

// Close dropdown when clicking outside
let clickOutsideHandler
onMounted(() => {
  clickOutsideHandler = (e) => {
    if (dropdown.value && !dropdown.value.contains(e.target)) {
      closeDropdown()
    }
  }
  document.addEventListener('click', clickOutsideHandler)
})

onUnmounted(() => {
  if (clickOutsideHandler) {
    document.removeEventListener('click', clickOutsideHandler)
  }
})
</script>

<template>
  <div ref="dropdown" class="relative">
    <!-- Trigger Button -->
    <button
      :class="{
        'opacity-50 cursor-not-allowed': disabled,
      }"
      :disabled="disabled"
      class="relative w-full cursor-default rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600 sm:text-sm"
      type="button"
      @click="toggleDropdown"
    >
      <span class="block truncate">
        {{ selectedOption ? selectedOption[displayKey] : placeholder }}
      </span>
      <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
        <svg aria-hidden="true" class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
          <path clip-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" fill-rule="evenodd" />
        </svg>
      </span>
    </button>

    <!-- Dropdown -->
    <div
      v-if="isOpen"
      class="absolute z-10 mt-1 w-full overflow-hidden rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800 dark:ring-gray-700"
    >
      <!-- Search Input -->
      <div class="p-2">
        <input
          ref="searchInput"
          v-model="searchQuery"
          class="w-full rounded-md border-gray-300 py-2 px-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
          placeholder="Pesquisar..."
          type="text"
        >
      </div>

      <!-- Options -->
      <ul class="max-h-60 overflow-auto py-1 text-base sm:text-sm">
        <li
          v-if="filteredOptions.length === 0"
          class="relative cursor-default select-none py-2 px-3 text-gray-700 dark:text-gray-300"
        >
          Nenhum resultado encontrado.
        </li>
        <li
          v-for="option in filteredOptions"
          :key="option[valueKey]"
          :class="{
            'bg-indigo-600 text-white': selectedOption && selectedOption[valueKey] === option[valueKey]
          }"
          class="relative cursor-default select-none py-2 pl-4 pr-4 text-gray-900 hover:bg-indigo-600 hover:text-white dark:text-gray-100 dark:hover:bg-indigo-600"
          @click="selectOption(option)"
        >
          <span
            :class="{
              'font-medium': selectedOption && selectedOption[valueKey] === option[valueKey],
              'font-normal': !selectedOption || selectedOption[valueKey] !== option[valueKey]
            }"
            class="block truncate"
          >
            {{ option[displayKey] }}
          </span>
          <span
            v-if="selectedOption && selectedOption[valueKey] === option[valueKey]"
            :class="{
              'text-white': selectedOption && selectedOption[valueKey] === option[valueKey]
            }"
            class="absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
          >
            <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path clip-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" fill-rule="evenodd" />
            </svg>
          </span>
        </li>
      </ul>
    </div>
  </div>
</template>
