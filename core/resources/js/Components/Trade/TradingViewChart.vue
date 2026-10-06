<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';

// Same TradingView tv.js widget the admin setting embeds ("{market}:{pair}", dark theme).
const props = defineProps({ symbol: String, interval: { type: String, default: '60' } });
const id = `tv_${Math.random().toString(36).slice(2)}`;
const failed = ref(false);

function loadScript() {
    if (window.TradingView) return Promise.resolve();
    return new Promise((resolve, reject) => {
        const existing = document.getElementById('tv-js');
        if (existing) {
            existing.addEventListener('load', resolve);
            existing.addEventListener('error', reject);
            return;
        }
        const s = document.createElement('script');
        s.id = 'tv-js';
        s.src = 'https://s3.tradingview.com/tv.js';
        s.onload = resolve;
        s.onerror = reject;
        document.head.appendChild(s);
    });
}

async function render() {
    try {
        await loadScript();
        const el = document.getElementById(id);
        if (!el) return;
        el.innerHTML = '';
        new window.TradingView.widget({
            autosize: true,
            symbol: props.symbol,
            interval: props.interval,
            timezone: 'Etc/UTC',
            theme: 'dark',
            style: '1',
            locale: 'en',
            toolbar_bg: '#0b0d12',
            backgroundColor: '#0b0d12',
            gridColor: 'rgba(255,255,255,0.04)',
            enable_publishing: false,
            allow_symbol_change: false,
            hide_side_toolbar: window.innerWidth < 768,
            container_id: id,
        });
    } catch {
        failed.value = true;
    }
}

onMounted(render);
watch(() => props.symbol, render);
onBeforeUnmount(() => {
    const el = document.getElementById(id);
    if (el) el.innerHTML = '';
});
</script>

<template>
    <div class="relative h-full w-full">
        <div :id="id" class="h-full w-full"></div>
        <div v-if="failed" class="absolute inset-0 grid place-items-center text-sm text-zinc-500">Chart unavailable</div>
    </div>
</template>
