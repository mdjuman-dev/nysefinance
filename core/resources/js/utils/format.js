export function formatPrice(value) {
    const n = Number(value) || 0;
    const digits = n >= 1000 ? 2 : n >= 1 ? 2 : n >= 0.01 ? 4 : 6;
    return n.toLocaleString('en-US', { minimumFractionDigits: digits, maximumFractionDigits: digits });
}

export function formatCompact(value) {
    const n = Number(value) || 0;
    if (n === 0) return '—';
    return n.toLocaleString('en-US', { notation: 'compact', maximumFractionDigits: 2 });
}

export function formatPercent(value) {
    const n = Number(value) || 0;
    return `${n > 0 ? '+' : ''}${n.toFixed(2)}%`;
}

export function trendClass(value) {
    const n = Number(value) || 0;
    if (n > 0) return 'text-up';
    if (n < 0) return 'text-down';
    return 'text-zinc-400';
}

export function formatMoney(value, digits = 2) {
    const n = Number(value) || 0;
    return n.toLocaleString('en-US', { minimumFractionDigits: digits, maximumFractionDigits: digits });
}

export function formatAmount(value) {
    const n = Number(value) || 0;
    const digits = Math.abs(n) >= 1 ? 2 : Math.abs(n) >= 0.0001 ? 6 : 8;
    return n.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: digits });
}

export function timeAgo(iso) {
    if (!iso) return '';
    const seconds = Math.round((Date.now() - new Date(iso).getTime()) / 1000);
    const units = [
        ['year', 31536000], ['month', 2592000], ['week', 604800],
        ['day', 86400], ['hour', 3600], ['minute', 60],
    ];
    const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' });
    for (const [unit, size] of units) {
        if (Math.abs(seconds) >= size) return rtf.format(-Math.round(seconds / size), unit);
    }
    return 'just now';
}
