export function formatDateTime(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

export function formatDate(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

export function orderStatusTone(status) {
    return { open: 'info', pending: 'warning', positioned: 'violet', completed: 'success', canceled: 'neutral' }[status] || 'neutral';
}

// Deposit / withdraw / generic status words used by the app's badges.
export function statusTone(label) {
    const s = String(label || '').toLowerCase();
    if (/(success|complete|approved|paid|active|verified|accepted|running|win)/.test(s)) return 'success';
    if (/(pending|processing|initiated|review|waiting|hold)/.test(s)) return 'warning';
    if (/(reject|cancel|fail|declined|banned|closed|expired|lose|loss)/.test(s)) return 'danger';
    return 'neutral';
}

export function ticketStatusTone(status) {
    return { Open: 'success', Answered: 'info', 'Customer reply': 'warning', Closed: 'neutral' }[status] || 'neutral';
}

export function priorityTone(priority) {
    return { High: 'danger', Medium: 'warning', Low: 'neutral' }[priority] || 'neutral';
}
