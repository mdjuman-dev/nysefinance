// navigator.clipboard needs a secure context and permission; in-app WebViews
// often refuse it, so fall back to a hidden textarea + execCommand.
export async function copyText(text) {
    try {
        await navigator.clipboard.writeText(text);
        return true;
    } catch {
        const el = document.createElement('textarea');
        el.value = text;
        el.setAttribute('readonly', '');
        el.style.cssText = 'position:fixed;top:-1000px;opacity:0';
        document.body.appendChild(el);
        el.select();
        let ok = false;
        try { ok = document.execCommand('copy'); } catch {}
        el.remove();
        return ok;
    }
}
