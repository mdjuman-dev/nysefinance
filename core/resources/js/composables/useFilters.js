import { reactive, watch } from 'vue';
import { router } from '@inertiajs/vue3';

/**
 * Query-string filters that re-visit the current page (keeping scroll/state).
 * Text fields are debounced; selects apply immediately.
 */
export function useFilters(keys, { debounce = ['search'], only } = {}) {
    const params = new URLSearchParams(window.location.search);
    const filters = reactive(Object.fromEntries(keys.map((k) => [k, params.get(k) ?? ''])));
    let timer;

    function apply() {
        const query = Object.fromEntries(Object.entries(filters).filter(([, v]) => v !== '' && v != null));
        router.get(window.location.pathname, query, { preserveState: true, preserveScroll: true, replace: true, only });
    }

    keys.forEach((k) =>
        watch(
            () => filters[k],
            () => {
                clearTimeout(timer);
                timer = setTimeout(apply, debounce.includes(k) ? 400 : 0);
            },
        ),
    );

    const reset = () => keys.forEach((k) => (filters[k] = ''));
    const active = () => keys.some((k) => filters[k] !== '');

    return { filters, apply, reset, active };
}
