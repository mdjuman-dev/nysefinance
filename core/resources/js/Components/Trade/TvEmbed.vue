<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

/**
 * TradingView "external embedding" widget (mini overview, technical analysis,
 * financials, profile, events, advanced chart). It is only injected once it
 * scrolls into view, so a page with many cards does not load every iframe up front.
 */
const props = defineProps({
    widget: { type: String, required: true }, // e.g. 'mini-symbol-overview'
    config: { type: Object, required: true },
});

const el = ref(null);
let io;

function inject() {
    if (!el.value || el.value.dataset.loaded) return;
    el.value.dataset.loaded = '1';
    const inner = document.createElement('div');
    inner.className = 'tradingview-widget-container__widget';
    inner.style.height = '100%';
    const script = document.createElement('script');
    script.src = `https://s3.tradingview.com/external-embedding/embed-widget-${props.widget}.js`;
    script.async = true;
    script.innerHTML = JSON.stringify({ colorTheme: 'dark', isTransparent: true, locale: 'en', width: '100%', height: '100%', ...props.config });
    el.value.append(inner, script);
}

onMounted(() => {
    if (!('IntersectionObserver' in window)) return inject();
    io = new IntersectionObserver((entries) => entries.some((e) => e.isIntersecting) && (inject(), io.disconnect()), { rootMargin: '200px' });
    io.observe(el.value);
});
onBeforeUnmount(() => io && io.disconnect());
</script>

<template>
    <div ref="el" class="tradingview-widget-container h-full w-full"></div>
</template>
