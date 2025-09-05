import { ref } from 'vue'

export function useLoading() {
    const isLoading = ref(false)
    const loadingStates = ref(new Map())
    
    const withLoading = async (asyncFn, key = 'default') => {
        isLoading.value = true
        loadingStates.value.set(key, true)
        
        try {
            return await asyncFn()
        } finally {
            isLoading.value = false
            loadingStates.value.set(key, false)
        }
    }
    
    const isLoadingKey = (key) => {
        return loadingStates.value.get(key) || false
    }
    
    const setLoading = (loading, key = 'default') => {
        if (key === 'default') {
            isLoading.value = loading
        }
        loadingStates.value.set(key, loading)
    }
    
    return { 
        isLoading, 
        withLoading, 
        isLoadingKey,
        setLoading
    }
}