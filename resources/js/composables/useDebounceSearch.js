import { computed, onUnmounted, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import debounce from "lodash/debounce";

/**
 * Keeps list filters in sync with the URL through debounced Inertia visits.
 *
 * - Any filter change other than `page` sends the user back to page 1.
 * - Filters missing from the server echo are read from the current query string,
 *   so toggles survive reloads and shared links.
 * - Falsy toggles are dropped from the query instead of being sent as "false".
 */
export function useDebounceSearch(route, initialFilters = {}) {
    const query = new URLSearchParams(window.location.search);
    const hydrated = { ...initialFilters };
    Object.keys(hydrated).forEach((key) => {
        if (hydrated[key] === undefined || hydrated[key] === null) {
            const fromQuery = query.get(key);
            if (fromQuery === "true") {
                hydrated[key] = true;
            } else if (fromQuery !== null && fromQuery !== "false") {
                hydrated[key] = fromQuery;
            }
        }
        if (hydrated[key] === "true") {
            hydrated[key] = true;
        }
        if (hydrated[key] === "false") {
            hydrated[key] = false;
        }
    });

    const filters = ref(hydrated);
    const isLoading = ref(false);
    const defaults = { ...initialFilters };

    const cleanParams = (values) =>
        Object.fromEntries(
            Object.entries(values).filter(
                ([, value]) => value !== false && value !== "" && value !== null && value !== undefined
            )
        );

    const visit = debounce((values) => {
        router.get(route, cleanParams(values), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            onStart: () => (isLoading.value = true),
            onFinish: () => (isLoading.value = false),
        });
    }, 300);

    let previous = JSON.stringify(filters.value);
    watch(
        filters,
        (current) => {
            const before = JSON.parse(previous);
            const changedOtherThanPage = Object.keys({ ...before, ...current }).some(
                (key) => key !== "page" && before[key] !== current[key]
            );
            if (changedOtherThanPage && current.page && Number(current.page) !== 1) {
                current.page = 1;
            }
            previous = JSON.stringify(current);
            visit(current);
        },
        { deep: true }
    );

    onUnmounted(() => visit.cancel());

    const updateFilter = (key, value) => {
        filters.value[key] = value;
    };

    /** Cycles a column through asc → desc → default order. */
    const toggleOrder = (field) => {
        if (filters.value.orderField !== field || !filters.value.orderDir) {
            filters.value.orderField = field;
            filters.value.orderDir = "asc";
        } else if (filters.value.orderDir === "asc") {
            filters.value.orderDir = "desc";
        } else {
            filters.value.orderDir = undefined;
            filters.value.orderField = undefined;
        }
    };

    const setOrder = ({ orderField, orderDir }) => {
        filters.value.orderField = orderField ?? undefined;
        filters.value.orderDir = orderDir ?? undefined;
    };

    /** Keys ignored when deciding whether the user has narrowed the list. */
    const nonFilterKeys = ["page", "orderField", "orderDir", "perPage"];

    const hasActiveFilters = computed(() =>
        Object.entries(filters.value).some(
            ([key, value]) =>
                !nonFilterKeys.includes(key) && value !== undefined && value !== null && value !== "" && value !== false
        )
    );

    const resetFilters = () => {
        Object.keys(filters.value).forEach((key) => {
            if (!nonFilterKeys.includes(key)) {
                filters.value[key] = typeof defaults[key] === "boolean" ? false : undefined;
            }
        });
    };

    return {
        filters,
        isLoading,
        hasActiveFilters,
        updateFilter,
        toggleOrder,
        setOrder,
        resetFilters,
        debouncedSearch: visit,
    };
}
