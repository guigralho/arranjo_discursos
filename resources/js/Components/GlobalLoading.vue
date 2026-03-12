<template>
    <Transition
        enter-active-class="transition-opacity duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isNavigating"
            class="fixed inset-x-0 top-0 z-[60] h-0.5"
        >
            <div class="h-full w-full animate-pulse bg-teal-500" />
            <div class="loading-bar h-full bg-teal-500" />
        </div>
    </Transition>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const isNavigating = ref(false)

onMounted(() => {
    router.on('start', () => {
        isNavigating.value = true
    })

    router.on('finish', () => {
        setTimeout(() => {
            isNavigating.value = false
        }, 100)
    })

    router.on('error', () => {
        isNavigating.value = false
    })
})
</script>

<style scoped>
.loading-bar {
    position: absolute;
    top: 0;
    left: 0;
    animation: loading 1.5s ease-in-out infinite;
}

@keyframes loading {
    0% {
        width: 0%;
        left: 0%;
    }
    50% {
        width: 60%;
        left: 20%;
    }
    100% {
        width: 0%;
        left: 100%;
    }
}
</style>