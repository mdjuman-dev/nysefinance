// For the existing JSON endpoints (order update, stock actions, ...) that
// return { success, message } instead of Inertia responses.
function csrf() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

export async function postJson(url, data = {}, { asForm = false } = {}) {
    const body = asForm ? toFormData(data) : JSON.stringify(data);
    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf(),
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
            ...(asForm ? {} : { 'Content-Type': 'application/json' }),
        },
        body,
    });
    const json = await res.json().catch(() => ({}));
    if (res.status === 422 && json.errors) {
        return { success: false, message: Object.values(json.errors).flat() };
    }
    return json;
}

export async function getJson(url, params) {
    const qs = params ? `?${new URLSearchParams(params)}` : '';
    const res = await fetch(url + qs, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
    return res.json();
}

export function messageText(message) {
    if (!message) return '';
    if (Array.isArray(message)) return message.join(' ');
    if (typeof message === 'object') return Object.values(message).flat().join(' ');
    return String(message);
}

function toFormData(data) {
    const fd = new FormData();
    Object.entries(data).forEach(([k, v]) => v != null && fd.append(k, v));
    return fd;
}
