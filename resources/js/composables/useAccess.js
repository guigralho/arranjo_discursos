import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

/**
 * Mirrors the access rules the UI has always used (hard-coded user ids).
 * Kept in one place so every screen applies them consistently.
 */
export function useAccess() {
    const page = usePage();
    const userId = computed(() => page.props.auth?.user?.id);

    const isAdmin = computed(() => userId.value === 1);
    const isBusOnly = computed(() => userId.value === 14);
    const canSeeArrangements = computed(() => !isBusOnly.value);
    const canSeeBus = computed(() => isAdmin.value || isBusOnly.value);

    return { isAdmin, isBusOnly, canSeeArrangements, canSeeBus };
}
