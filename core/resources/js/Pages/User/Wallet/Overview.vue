<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import SegmentTabs from '@/Components/UI/SegmentTabs.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import CoinIcon from '@/Components/CoinIcon.vue';
import DepositSheet from '@/Components/Wallet/DepositSheet.vue';
import WithdrawSheet from '@/Components/Wallet/WithdrawSheet.vue';
import { useToast } from '@/composables/useToast';
import { formatAmount, formatMoney } from '@/utils/format';

// Shared by Overview (all wallets), Spot and Funding.
const props = defineProps({
    tab: { type: String, default: 'overview' },
    tabs: Array,
    estimated: Number,
    frozen: Boolean,
    wallets: Object,
    gateways: Array,
    withdrawMethods: Array,
    open: String,
    urls: Object,
});
const toast = useToast();
const titles = { overview: 'Estimated balance', spot: 'Spot balance', funding: 'Funding balance' };
const showInOrder = computed(() => props.wallets.data.some((w) => w.inOrder > 0));

/* Balance visibility shares the dashboard's localStorage key */
const hidden = ref(false);
try { hidden.value = localStorage.getItem('viewBalance') === 'yes'; } catch {}
function toggleHidden() {
    hidden.value = !hidden.value;
    try { hidden.value ? localStorage.setItem('viewBalance', 'yes') : localStorage.removeItem('viewBalance'); } catch {}
}
const mask = (t) => (hidden.value ? '••••••' : t);

/* Sheets */
const sheet = ref(props.open);
const frozenMsg = 'Your all transaction has been frozen. Wait until your report solved';
function openSheet(name) {
    if (props.frozen && name === 'withdraw') return toast.error(frozenMsg);
    sheet.value = name;
}
function go(url) {
    if (props.frozen) return toast.error(frozenMsg);
    window.location.href = url;
}

const actions = computed(() => [
    { label: 'Deposit', icon: 'ri-add-circle-line', run: () => openSheet('deposit'), primary: true },
    { label: 'Withdraw', icon: 'ri-upload-2-line', run: () => openSheet('withdraw') },
    { label: 'Convert', icon: 'ri-exchange-funds-line', run: () => go(props.urls.convert) },
    { label: 'Transfer', icon: 'ri-arrow-left-right-line', run: () => go(props.urls.transfer) },
]);

/* Assets */
const search = ref('');
const hideZero = ref(false);
const rows = computed(() => {
    const q = search.value.trim().toUpperCase();
    return props.wallets.data.filter((w) => (!q || w.symbol.includes(q) || w.name.toUpperCase().includes(q)) && (!hideZero.value || w.balance > 0));
});

/* Live USD price per coin (Binance ticker, like before) */
const prices = reactive({});
const trend = reactive({});
let timer;
async function loadPrices() {
    // One request for every Binance USDT price instead of one per coin (unknown symbols used to 400).
    try {
        const res = await fetch('https://api.binance.com/api/v3/ticker/price');
        if (!res.ok) return;
        const all = new Map((await res.json()).map((t) => [t.symbol, Number(t.price)]));
        for (const s of new Set(props.wallets.data.map((w) => w.symbol))) {
            const p = ['USDT', 'USD'].includes(s) ? 1 : all.get(`${s}USDT`);
            if (p == null) continue;
            if (prices[s] != null) trend[s] = p > prices[s] ? 'up' : p < prices[s] ? 'down' : trend[s];
            prices[s] = p;
        }
    } catch {}
}
onMounted(() => {
    loadPrices();
    timer = setInterval(loadPrices, 10000);
});
onBeforeUnmount(() => clearInterval(timer));
</script>

<template>
    <Head :title="tab === 'overview' ? 'Wallet' : `${tab[0].toUpperCase()}${tab.slice(1)} wallet`" />

    <div class="mb-4"><SegmentTabs :tabs="tabs" /></div>

    <div v-if="frozen" class="mb-5 flex items-center gap-3 rounded-2xl border border-down/20 bg-down/[0.06] p-4">
        <i class="ri-lock-2-line text-2xl text-down"></i>
        <p class="text-sm text-zinc-300">Transactions are frozen while a report on your account is under review.</p>
    </div>

    <!-- Balance -->
    <section class="relative mb-5 overflow-hidden rounded-3xl border border-white/[0.07] bg-gradient-to-br from-ink-800 via-ink-900 to-ink-900 p-5 sm:p-7">
        <div class="pointer-events-none absolute -top-24 -right-16 h-64 w-64 rounded-full bg-brand-500/20 blur-3xl"></div>
        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="flex items-center gap-2 text-sm text-zinc-400">
                    {{ titles[tab] }}
                    <button class="grid h-7 w-7 place-items-center rounded-lg text-zinc-500 hover:bg-white/[0.06] hover:text-white" :aria-label="hidden ? 'Show balance' : 'Hide balance'" @click="toggleHidden">
                        <i :class="hidden ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                    </button>
                </p>
                <p class="mt-2 font-mono text-[2.1rem] leading-none font-bold text-white sm:text-5xl"><span class="text-zinc-500">$</span>{{ mask(formatMoney(estimated)) }}</p>
                <p class="mt-3 inline-flex items-center gap-1.5 rounded-full border border-brand-500/20 bg-brand-500/10 px-3 py-1 text-xs font-medium text-brand-300">
                    <i class="ri-percent-line"></i> Hold USDT and earn up to 9% APR
                </p>
            </div>
            <div class="grid grid-cols-4 gap-2 sm:flex sm:gap-3">
                <button v-for="a in actions" :key="a.label" type="button" class="group flex flex-col items-center gap-1.5 sm:flex-row sm:gap-2 sm:rounded-xl sm:px-5 sm:py-2.5 sm:text-sm sm:font-semibold" :class="a.primary ? 'sm:bg-brand-500 sm:text-ink-950 sm:hover:bg-brand-400' : 'sm:border sm:border-white/10 sm:bg-white/[0.04] sm:hover:bg-white/[0.08]'" @click="a.run()">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl text-xl transition group-active:scale-95 sm:h-auto sm:w-auto sm:rounded-none sm:bg-transparent sm:text-base sm:shadow-none" :class="a.primary ? 'bg-brand-500 text-ink-950 shadow-lg shadow-brand-500/30' : 'bg-white/[0.07] text-zinc-100'">
                        <i :class="a.icon"></i>
                    </span>
                    <span class="text-xs font-medium text-zinc-300 sm:text-sm" :class="a.primary ? 'sm:text-ink-950' : 'sm:text-zinc-100'">{{ a.label }}</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Assets -->
    <section class="card overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-white/[0.05] p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
            <h2 class="font-semibold text-white">Assets</h2>
            <div class="flex items-center gap-3">
                <label class="flex cursor-pointer items-center gap-2 text-xs text-zinc-400">
                    <input v-model="hideZero" type="checkbox" class="h-4 w-4 rounded border-white/20 bg-ink-850 accent-brand-500" /> Hide zero
                </label>
                <label class="relative block flex-1 sm:w-56">
                    <i class="ri-search-line pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-sm text-zinc-500"></i>
                    <input v-model="search" type="search" placeholder="Search coin" class="w-full rounded-lg border border-white/[0.07] bg-white/[0.03] py-2 pr-3 pl-9 text-sm text-zinc-200 placeholder:text-zinc-500 focus:border-brand-500/50 focus:outline-none" />
                </label>
            </div>
        </div>

        <EmptyState v-if="!rows.length" icon="ri-wallet-3-line" title="No assets found" />
        <ul v-else class="divide-y divide-white/[0.04]">
            <li v-for="w in rows" :key="w.id">
                <a :href="w.url" class="flex items-center gap-3 px-4 py-3.5 transition hover:bg-white/[0.02] active:bg-white/[0.04] sm:px-5">
                    <CoinIcon :src="w.image" :symbol="w.symbol" size="h-10 w-10" />
                    <div class="min-w-0 flex-1">
                        <p class="flex items-center gap-2 font-semibold text-white">
                            {{ w.symbol }}
                            <span v-if="tab === 'overview'" class="rounded bg-white/[0.05] px-1.5 py-0.5 text-[10px] font-medium tracking-wide text-zinc-400 uppercase">{{ w.type }}</span>
                        </p>
                        <p class="truncate text-xs text-zinc-500">{{ w.name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-mono text-sm font-semibold text-zinc-100">{{ w.sign }}{{ mask(formatAmount(w.balance)) }}</p>
                        <p v-if="showInOrder && w.inOrder > 0" class="font-mono text-[11px] text-amber-300">{{ mask(formatAmount(w.inOrder)) }} in orders</p>
                        <p class="font-mono text-xs" :class="trend[w.symbol] === 'up' ? 'text-up' : trend[w.symbol] === 'down' ? 'text-down' : 'text-zinc-500'">
                            <template v-if="prices[w.symbol] != null">≈ ${{ mask(formatMoney(w.balance * prices[w.symbol])) }}</template>
                            <template v-else>—</template>
                        </p>
                    </div>
                    <i class="ri-arrow-right-s-line text-lg text-zinc-600"></i>
                </a>
            </li>
        </ul>
        <Pagination :meta="wallets.meta" />
    </section>

    <DepositSheet :show="sheet === 'deposit'" :gateways="gateways" :action="urls.depositInsert" @close="sheet = null" />
    <WithdrawSheet :show="sheet === 'withdraw'" :methods="withdrawMethods" :urls="urls" @close="sheet = null" />
</template>
