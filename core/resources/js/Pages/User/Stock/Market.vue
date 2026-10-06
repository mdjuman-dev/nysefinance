<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import Modal from '@/Components/UI/Modal.vue';
import CoinIcon from '@/Components/CoinIcon.vue';

const props = defineProps({ stocks: Array, isMember: Boolean, urls: Object });

/* Live quotes: same Finnhub key and pacing the Blade page used (1 req/s, refresh every 30s). */
const FINNHUB_KEY = 'd49k3rhr01qlaebho1ngd49k3rhr01qlaebho1o0';
const quotes = reactive({});
let stopped = false;
let timer;

async function fetchAll() {
    for (const s of props.stocks) {
        if (stopped) return;
        if (!s.code) continue;
        try {
            const res = await fetch(`https://finnhub.io/api/v1/quote?symbol=${encodeURIComponent(s.code)}&token=${FINNHUB_KEY}`);
            const d = await res.json();
            if (d && d.c) quotes[s.code] = { price: d.c, change: d.dp ?? 0 };
        } catch {}
        await new Promise((r) => setTimeout(r, 1000));
    }
    if (!stopped) timer = setTimeout(fetchAll, 30000);
}
onMounted(fetchAll);
onBeforeUnmount(() => {
    stopped = true;
    clearTimeout(timer);
});

const search = ref('');
const filtered = computed(() => {
    const q = search.value.trim().toLowerCase();
    return props.stocks.filter((s) => !q || s.name.toLowerCase().includes(q) || (s.code || '').toLowerCase().includes(q));
});

/* Membership gate */
const memberModal = ref(false);
const buying = ref(false);
function open(stock) {
    if (props.isMember) window.location.href = stock.url;
    else memberModal.value = true;
}
function buyMembership() {
    buying.value = true;
    router.post(props.urls.member, {}, { onFinish: () => { buying.value = false; memberModal.value = false; } });
}
</script>

<template>
    <Head title="Stock market" />
    <PageHeader title="Stock market" subtitle="Invest in global stocks" icon="ri-stock-line">
        <template #actions>
            <template v-if="isMember">
                <a :href="urls.history" class="btn-ghost"><i class="ri-file-history-line"></i> History</a>
                <a :href="urls.my" class="btn-primary"><i class="ri-briefcase-4-line"></i> My stocks</a>
            </template>
            <button v-else class="btn-primary" @click="memberModal = true"><i class="ri-vip-crown-line"></i> Get membership</button>
        </template>
    </PageHeader>

    <div v-if="!isMember" class="mb-5 flex flex-col gap-4 rounded-3xl border border-amber-400/20 bg-gradient-to-r from-amber-400/[0.08] to-transparent p-5 sm:flex-row sm:items-center">
        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-amber-400/15 text-2xl text-amber-300"><i class="ri-vip-crown-2-line"></i></span>
        <div class="flex-1">
            <p class="font-semibold text-white">Global Stock Membership required</p>
            <p class="text-sm text-zinc-400">A one-time 20 USDT membership unlocks buying, selling and exchanging stocks.</p>
        </div>
        <button class="btn-primary" @click="memberModal = true">Unlock for 20 USDT</button>
    </div>

    <div class="card mb-4 p-3">
        <label class="relative block">
            <i class="ri-search-line pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-sm text-zinc-500"></i>
            <input v-model="search" type="search" placeholder="Search company or ticker" class="w-full rounded-lg border border-white/[0.07] bg-white/[0.03] py-2.5 pr-3 pl-9 text-sm text-zinc-200 placeholder:text-zinc-500 focus:border-brand-500/50 focus:outline-none" />
        </label>
    </div>

    <EmptyState v-if="!filtered.length" icon="ri-stock-line" title="No stocks found" class="card" />

    <div v-else class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
        <button v-for="s in filtered" :key="s.id" type="button" class="card card-hover group flex items-center gap-4 p-4 text-left" @click="open(s)">
            <CoinIcon :src="s.image" :symbol="s.code || s.name" size="h-12 w-12" />
            <div class="min-w-0 flex-1">
                <p class="truncate font-semibold text-white">{{ s.name }}</p>
                <p class="font-mono text-xs text-zinc-500">{{ s.code }}</p>
            </div>
            <div class="text-right">
                <template v-if="quotes[s.code]">
                    <p class="font-mono text-sm font-semibold text-white">${{ quotes[s.code].price.toFixed(2) }}</p>
                    <p class="font-mono text-xs font-semibold" :class="quotes[s.code].change >= 0 ? 'text-up' : 'text-down'">
                        {{ quotes[s.code].change >= 0 ? '+' : '' }}{{ Number(quotes[s.code].change).toFixed(2) }}%
                    </p>
                </template>
                <span v-else class="block h-8 w-16 animate-pulse rounded-lg bg-white/[0.05]"></span>
            </div>
            <i class="ri-arrow-right-s-line text-xl text-zinc-600 transition group-hover:translate-x-0.5 group-hover:text-zinc-300"></i>
        </button>
    </div>

    <Modal :show="memberModal" title="Global Stock Membership" @close="memberModal = false">
        <div class="rounded-2xl border border-amber-400/20 bg-amber-400/[0.06] p-4 text-center">
            <i class="ri-vip-crown-2-fill text-4xl text-amber-300"></i>
            <p class="mt-2 font-mono text-3xl font-bold text-white">20 USDT</p>
            <p class="text-xs text-zinc-400">One-time payment from your USDT wallet</p>
        </div>
        <p class="mt-4 text-sm text-zinc-400">To buy stocks you first need a Global Stock Membership. Do you want to buy it now?</p>
        <div class="mt-5 grid grid-cols-2 gap-2">
            <button class="btn-ghost" @click="memberModal = false">Not now</button>
            <button class="btn-primary" :disabled="buying" @click="buyMembership"><i :class="buying ? 'ri-loader-4-line animate-spin' : 'ri-check-line'"></i> Confirm</button>
        </div>
    </Modal>
</template>
