import { ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import debounce from 'lodash/debounce'

export function useDebounceSearch(route, initialFilters = {}) {
    const filters = ref({ ...initialFilters })
    
    const debouncedSearch = debounce((newFilters) => {
        Inertia.get(route, newFilters, { 
            preserveState: true, 
            replace: true 
        })
    }, 300)
    
    watch(filters, debouncedSearch, { deep: true })
    
    const updateFilter = (key, value) => {
        // Reset page to 1 when search changes
        if (key === 'search' || key === 'searchDate') {
            filters.value.page = 1
        }
        filters.value[key] = value
    }
    
    return { 
        filters, 
        updateFilter,
        debouncedSearch 
    }
}