import { ref } from 'vue';

// Shared toast queue: server flashes (props.notify) and client actions both push here.
const toasts = ref([]);
let id = 0;

function dismiss(toastId) {
    toasts.value = toasts.value.filter((t) => t.id !== toastId);
}

function push(type, message) {
    const toast = { id: ++id, type, message };
    toasts.value.push(toast);
    setTimeout(() => dismiss(toast.id), 4000);
}

export function useToast() {
    return {
        toasts,
        dismiss,
        push,
        success: (m) => push('success', m),
        error: (m) => push('error', m),
        info: (m) => push('info', m),
    };
}
