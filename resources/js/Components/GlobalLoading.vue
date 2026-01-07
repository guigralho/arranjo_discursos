<template>
    <Transition
        enter-active-class="transition-opacity duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isNavigating"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-25 backdrop-blur-sm"
        >
            <div class="rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800">
                <div class="flex items-center space-x-4">
                    <svg
                        class="h-8 w-8 animate-spin text-blue-600"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            Carregando...
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Por favor, aguarde
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const isNavigating = ref(false)

onMounted(() => {
    Inertia.on('start', () => {
        isNavigating.value = true
    })
    
    Inertia.on('finish', () => {
        setTimeout(() => {
            isNavigating.value = false
        }, 100) // Small delay to avoid flash
    })
    
    Inertia.on('error', () => {
        isNavigating.value = false
    })
})
</script>