import { router } from '@inertiajs/vue3';

/**
 * Paths served as Inertia (Vue) pages. Links to these navigate without a
 * full reload; everything else (pages still on Blade, downloads, logout)
 * keeps normal browser navigation. Add a pattern here when a page is converted.
 */
const SPA_PATHS = [
    /^\/user\/dashboard$/,
    /^\/user\/order\/(open|completed|canceled|history)$/,
    /^\/user\/trade\/history$/,
    /^\/user\/transactions$/,
    /^\/user\/deposit\/(history|requests)$/,
    /^\/user\/withdraw\/history$/,
    /^\/user\/referrals$/,
    /^\/user\/salary$/,
    /^\/user\/twofactor$/,
    /^\/user\/(profile-setting|change-password|kyc-form|kyc-data)$/,
    /^\/user\/blogs$/,
    /^\/user\/stock$/,
    /^\/user\/stock\/(my|transactions|interest\/trx|transfer\/history|daily\/interest)$/,
    /^\/user\/stock\/(detail|exchange)\/[^/]+$/,
    /^\/user\/(bonds|my\/bonds|bond\/transaction|bond\/interest)$/,
    /^\/user\/bond\/detail\/[^/]+$/,
    /^\/user\/wallet\/(overview|stock|bond|copy)$/,
    /^\/user\/wallet\/list\/(spot|funding)$/,
    /^\/user\/wallet\/(spot|funding)\/[A-Za-z0-9]+$/,
    /^\/user\/convert$/,
    /^\/user\/p2p$/,
    /^\/user\/p2p\/(advertisement|payment-method|feedback\/list|trade\/(running|completed))$/,
    /^\/user\/p2p\/(advertisement\/create(\/\d+)?|payment-method\/(create|edit\/\d+)|trade\/details\/\d+)$/,
    /^\/ticket(\/new|\/view\/[^/]+)?$/,
    /^\/p2p(\/(buy|sell)(\/.*)?)?$/,
    /^\/trade(\/[A-Z0-9]+_[A-Z0-9]+)?$/,
    /^\/futures(\/[A-Z0-9]+_[A-Z0-9]+)?$/,
    /^\/(about-us|contact|crypto-currency|privacy-policy|terms-condition|trade-policy)$/,
];

export function isSpaUrl(href, { publicHome = false } = {}) {
    let url;
    try {
        url = new URL(href, window.location.href);
    } catch {
        return false;
    }
    if (url.origin !== window.location.origin) return false;
    const path = url.pathname.replace(/\/+$/, '') || '/';
    if (path === '/') return publicHome; // "/" redirects to the Blade login unless the public home is on
    return SPA_PATHS.some((re) => re.test(path)) && !url.searchParams.has('legacy');
}

/** Turn plain <a href> clicks to Vue pages into Inertia visits. */
export function installSpaLinks(getPublicHome) {
    document.addEventListener('click', (e) => {
        if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        const a = e.target.closest('a[href]');
        if (!a || a.target === '_blank' || a.hasAttribute('download') || a.dataset.native !== undefined) return;
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || /^(mailto|tel|javascript):/i.test(href)) return;
        if (!isSpaUrl(a.href, { publicHome: getPublicHome() })) return;
        e.preventDefault();
        router.visit(a.href);
    });
}

/** Programmatic navigation that picks SPA or full load. */
export function navigate(href) {
    if (isSpaUrl(href)) router.visit(href);
    else window.location.href = href;
}
