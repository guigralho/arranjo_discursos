import { ref, watch } from 'vue'

export function usePersistedSelection(storageKey) {
    const loadFromStorage = () => {
        try {
            const stored = localStorage.getItem(storageKey)
            return stored ? JSON.parse(stored) : []
        } catch (e) {
            console.error('Error loading from localStorage:', e)
            return []
        }
    }

    const selectedIds = ref(loadFromStorage())

    watch(selectedIds, (newValue) => {
        try {
            localStorage.setItem(storageKey, JSON.stringify(newValue))
        } catch (e) {
            console.error('Error saving to localStorage:', e)
        }
    }, { deep: true })

    const clearSelection = () => {
        selectedIds.value = []
        localStorage.removeItem(storageKey)
    }

    return {
        selectedIds,
        clearSelection
    }
}