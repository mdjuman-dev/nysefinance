<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import CoinIcon from '@/Components/CoinIcon.vue';
import { useToast } from '@/composables/useToast';
import { copyText } from '@/utils/clipboard';
import { formatAmount, formatCompact, formatMoney, formatPercent, formatPrice, timeAgo, trendClass } from '@/utils/format';

const props = defineProps({
    balance: Object,
    stats: Object,
    wallets: { type: Array, default: () => [] },
    pairs: { type: Array, default: () => [] },
    recentOrders: { type: Array, default: () => [] },
    recentTransactions: { type: Array, default: () => [] },
    urls: Object,
});

const page = usePage();
const shell = computed(() => page.props.shell);
const user = computed(() => shell.value.user);
const links = computed(() => shell.value.links);
const toast = useToast();

/* Greeting */
const greeting = (() => {
    const h = new Date().getHours();
    return h < 12 ? 'Good morning' : h < 18 ? 'Good afternoon' : 'Good evening';
})();
const firstName = computed(() => (user.value.fullname || user.value.username || '').split(' ')[0]);

/* Balance visibility — same localStorage key the Blade dashboard used */
const hidden = ref(false);
try { hidden.value = localStorage.getItem('viewBalance') === 'yes'; } catch {}
function toggleHidden() {
    hidden.value = !hidden.value;
    try { hidden.value ? localStorage.setItem('viewBalance', 'yes') : localStorage.removeItem('viewBalance'); } catch {}
}
const mask = (text) => (hidden.value ? '••••••' : text);

/* Allocation */
const walletTotal = computed(() => props.wallets.reduce((s, w) => s + w.value, 0));
const palette = ['#3fd46e', '#38bdf8', '#a78bfa', '#f59e0b', '#f43f5e', '#64748b'];
const allocation = computed(() =>
    props.wallets.map((w, i) => ({ ...w, color: palette[i % palette.length], pct: walletTotal.value ? (w.value / walletTotal.value) * 100 : 0 })),
);

/* Actions */
const primaryActions = computed(() => [
    { label: 'Deposit', icon: 'ri-add-circle-line', href: links.value.deposit, primary: true },
    { label: 'Withdraw', icon: 'ri-upload-2-line', href: links.value.withdraw },
    { label: 'Transfer', icon: 'ri-arrow-left-right-line', href: links.value.wallet },
    { label: 'Trade', icon: 'ri-exchange-funds-line', href: links.value.trade },
]);

const quick = computed(() => [
    { label: 'Trade', icon: 'ri-exchange-line', href: links.value.trade, tint: 'from-emerald-500/20 text-emerald-300' },
    { label: 'Stocks', icon: 'ri-stock-line', href: links.value.stocks, tint: 'from-sky-500/20 text-sky-300' },
    { label: 'Referrals', icon: 'ri-user-shared-line', href: links.value.referrals, tint: 'from-violet-500/20 text-violet-300' },
    { label: 'Orders', icon: 'ri-file-list-3-line', href: links.value.orders, tint: 'from-amber-500/20 text-amber-300' },
    { label: 'History', icon: 'ri-history-line', href: links.value.transactions, tint: 'from-teal-500/20 text-teal-300' },
    { label: 'Support', icon: 'ri-customer-service-2-line', href: links.value.support, tint: 'from-pink-500/20 text-pink-300' },
    { label: 'P2P', icon: 'ri-team-line', href: links.value.p2p, tint: 'from-indigo-500/20 text-indigo-300' },
    { label: 'More', icon: 'ri-apps-2-line', href: links.value.more, tint: 'from-zinc-500/20 text-zinc-300' },
]);

const statCards = computed(() => [
    { label: 'Open orders', value: props.stats.openOrders, icon: 'ri-time-line', cls: 'text-sky-300 bg-sky-500/10' },
    { label: 'Completed', value: props.stats.completedOrders, icon: 'ri-checkbox-circle-line', cls: 'text-up bg-up/10' },
    { label: 'Canceled', value: props.stats.canceledOrders, icon: 'ri-close-circle-line', cls: 'text-down bg-down/10' },
    { label: 'Total trades', value: props.stats.totalTrades, icon: 'ri-bar-chart-2-line', cls: 'text-amber-300 bg-amber-500/10' },
]);

/* Markets */
const marketTabs = [
    { key: 'hot', label: 'Hot' },
    { key: 'gainers', label: 'Gainers' },
    { key: 'losers', label: 'Losers' },
    { key: 'volume', label: 'Volume' },
];
const marketTab = ref('hot');
const marketSearch = ref('');
const showAllMarkets = ref(false);
const markets = computed(() => {
    const q = marketSearch.value.trim().toUpperCase();
    let list = props.pairs.filter((p) => !q || p.symbol.includes(q) || p.name.toUpperCase().includes(q));
    const sorters = {
        hot: (a, b) => b.marketCap - a.marketCap,
        gainers: (a, b) => b.change24h - a.change24h,
        losers: (a, b) => a.change24h - b.change24h,
        volume: (a, b) => b.volume - a.volume,
    };
    return [...list].sort(sorters[marketTab.value]);
});
const visibleMarkets = computed(() => (showAllMarkets.value || marketSearch.value ? markets.value : markets.value.slice(0, 8)));
const tradeUrl = (symbol) => props.urls.trade.replace('__SYMBOL__', symbol);

/* Activity */
const activityTab = ref('orders');
const statusStyle = {
    open: 'bg-sky-500/10 text-sky-300',
    pending: 'bg-amber-500/10 text-amber-300',
    positioned: 'bg-violet-500/10 text-violet-300',
    completed: 'bg-up/10 text-up',
    canceled: 'bg-zinc-500/15 text-zinc-400',
};

/* Promotions — link to real product pages */
const promos = computed(() => [
    { title: 'Classic Trade', text: 'Copy proven strategies', icon: 'ri-copper-coin-line', href: props.urls.classic, grad: 'from-emerald-500/25 to-teal-500/5' },
    { title: 'Futures', text: 'Long or short with leverage', icon: 'ri-line-chart-line', href: props.urls.futures, grad: 'from-sky-500/25 to-indigo-500/5' },
    { title: 'Token Splash', text: 'Join the prize pool', icon: 'ri-drop-line', href: props.urls.tokenSplash, grad: 'from-violet-500/25 to-fuchsia-500/5' },
    { title: 'Puzzle Hunt', text: 'Solve and earn rewards', icon: 'ri-puzzle-line', href: props.urls.puzzle, grad: 'from-amber-500/25 to-orange-500/5' },
]);

async function copy(text, label) {
    if (await copyText(text)) toast.success(`${label} copied`);
    else toast.error('Could not copy to clipboard');
}

/* Live Ethereum feed (same endpoint the Blade dashboard polled) */
const chain = ref([]);
const chainState = ref('loading');
let chainTimer;
async function loadChain() {
    if (document.hidden) return;
    try {
        const res = await fetch(props.urls.ercFeed, { headers: { Accept: 'application/json' } });
        const json = await res.json();
        if (json.status === 'success') {
            chain.value = json.data.slice(0, 6);
            chainState.value = 'ready';
        } else if (!chain.value.length) chainState.value = 'error';
    } catch {
        if (!chain.value.length) chainState.value = 'error';
    }
}
onMounted(() => {
    loadChain();
    chainTimer = setInterval(loadChain, 20000);
});
onBeforeUnmount(() => clearInterval(chainTimer));
</script>

<template>
    <Head title="Dashboard" />

    <!-- Greeting -->
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3 lg:mb-7">
        <div>
            <p class="text-sm text-zinc-500">{{ greeting }},</p>
            <h1 class="text-2xl font-bold tracking-tight text-white lg:text-3xl">{{ firstName }} <span class="inline-block origin-bottom-right animate-[wave_2s_ease-in-out_1]">👋</span></h1>
        </div>
        <a :href="links.trade" class="btn-primary hidden sm:inline-flex"><i class="ri-flashlight-line"></i> Quick trade</a>
    </div>

    <!-- KYC notice -->
    <a
        v-if="user.kyc !== 'verified' && links.kyc"
        :href="links.kyc"
        class="mb-5 flex items-center gap-3 rounded-2xl border p-4 transition"
        :class="user.kyc === 'pending' ? 'border-amber-400/20 bg-amber-400/[0.06] hover:bg-amber-400/10' : 'border-down/20 bg-down/[0.06] hover:bg-down/10'"
    >
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl text-xl" :class="user.kyc === 'pending' ? 'bg-amber-400/15 text-amber-300' : 'bg-down/15 text-down'">
            <i :class="user.kyc === 'pending' ? 'ri-time-line' : 'ri-shield-user-line'"></i>
        </span>
        <span class="min-w-0 flex-1">
            <span class="block text-sm font-semibold text-white">{{ user.kyc === 'pending' ? 'Verification under review' : 'Verify your identity' }}</span>
            <span class="block text-xs text-zinc-400">{{ user.kyc === 'pending' ? 'We will notify you once your documents are approved.' : 'Complete KYC to unlock withdrawals and higher limits.' }}</span>
        </span>
        <i class="ri-arrow-right-s-line text-xl text-zinc-500"></i>
    </a>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <!-- Portfolio -->
        <section class="relative overflow-hidden rounded-3xl border border-white/[0.07] bg-gradient-to-br from-ink-800 via-ink-900 to-ink-900 p-5 sm:p-7 lg:col-span-2">
            <div class="pointer-events-none absolute -top-24 -right-16 h-64 w-64 rounded-full bg-brand-500/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 left-1/3 h-48 w-48 rounded-full bg-sky-500/10 blur-3xl"></div>

            <div class="relative">
                <div class="flex items-center justify-between">
                    <p class="flex items-center gap-2 text-sm text-zinc-400">
                        Total balance
                        <button class="grid h-7 w-7 place-items-center rounded-lg text-base text-zinc-500 transition hover:bg-white/[0.06] hover:text-white" :aria-label="hidden ? 'Show balance' : 'Hide balance'" @click="toggleHidden">
                            <i :class="hidden ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                        </button>
                    </p>
                    <span class="rounded-full border border-white/10 bg-white/[0.04] px-2.5 py-1 text-[11px] font-medium text-zinc-400">{{ balance.currency }}</span>
                </div>

                <p class="mt-2 font-mono text-[2.1rem] leading-none font-bold tracking-tight text-white sm:text-5xl">
                    <span class="text-zinc-500">$</span>{{ mask(formatMoney(balance.estimated)) }}
                </p>

                <div class="mt-4 flex flex-wrap gap-x-6 gap-y-2 text-sm">
                    <p class="text-zinc-400">Stock assets <span class="ml-1 font-mono font-semibold text-zinc-100">{{ mask(formatMoney(balance.stock)) }}</span></p>
                    <p class="text-zinc-400">Spot wallets <span class="ml-1 font-mono font-semibold text-zinc-100">{{ mask(formatMoney(walletTotal)) }}</span></p>
                </div>

                <!-- Actions -->
                <!-- phone: round icon actions -->
                <div class="mt-6 grid grid-cols-4 gap-2 sm:hidden">
                    <a v-for="a in primaryActions" :key="a.label" :href="a.href" class="flex flex-col items-center gap-1.5">
                        <span
                            class="grid h-12 w-12 place-items-center rounded-2xl text-xl transition active:scale-95"
                            :class="a.primary ? 'bg-brand-500 text-ink-950 shadow-lg shadow-brand-500/30' : 'bg-white/[0.07] text-zinc-100'"
                        >
                            <i :class="a.icon"></i>
                        </span>
                        <span class="text-xs font-medium text-zinc-300">{{ a.label }}</span>
                    </a>
                </div>
                <!-- tablet/desktop: buttons -->
                <div class="mt-8 hidden flex-wrap gap-3 sm:flex">
                    <a v-for="a in primaryActions" :key="a.label" :href="a.href" :class="a.primary ? 'btn-primary' : 'btn-ghost'">
                        <i :class="a.icon" class="text-base"></i> {{ a.label }}
                    </a>
                </div>
            </div>
        </section>

        <!-- Allocation -->
        <section class="card flex flex-col p-5 sm:p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-white">Asset allocation</h2>
                <a :href="links.wallet" class="text-xs font-medium text-brand-300 hover:text-brand-200">View all</a>
            </div>
            <template v-if="allocation.length">
                <div class="mt-5 flex h-2.5 overflow-hidden rounded-full bg-white/[0.05]">
                    <span v-for="w in allocation" :key="w.symbol" :style="{ width: w.pct + '%', background: w.color }" class="h-full first:rounded-l-full last:rounded-r-full"></span>
                </div>
                <ul class="mt-5 space-y-3.5">
                    <li v-for="w in allocation" :key="w.symbol" class="flex items-center gap-3">
                        <CoinIcon :src="w.image" :symbol="w.symbol" size="h-8 w-8" />
                        <div class="min-w-0 flex-1">
                            <p class="flex items-center gap-1.5 text-sm font-semibold text-white">
                                <span class="h-2 w-2 rounded-full" :style="{ background: w.color }"></span>{{ w.symbol }}
                            </p>
                            <p class="truncate font-mono text-xs text-zinc-500">{{ mask(formatAmount(w.balance)) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-mono text-sm font-semibold text-zinc-100">${{ mask(formatMoney(w.value)) }}</p>
                            <p class="text-xs text-zinc-500">{{ w.pct.toFixed(1) }}%</p>
                        </div>
                    </li>
                </ul>
            </template>
            <div v-else class="flex flex-1 flex-col items-center justify-center py-8 text-center">
                <span class="grid h-14 w-14 place-items-center rounded-2xl bg-white/[0.04] text-2xl text-zinc-500"><i class="ri-wallet-3-line"></i></span>
                <p class="mt-3 text-sm font-medium text-zinc-300">No funded wallets yet</p>
                <p class="mt-1 text-xs text-zinc-500">Make your first deposit to start trading.</p>
                <a :href="links.deposit" class="btn-primary mt-4 py-2 text-xs">Deposit now</a>
            </div>
        </section>
    </div>

    <!-- Quick access -->
    <section class="mt-5">
        <div class="grid grid-cols-4 gap-x-2 gap-y-4 rounded-3xl border border-white/[0.06] bg-ink-900/60 p-4 sm:gap-3 sm:p-5 lg:grid-cols-8">
            <a v-for="q in quick" :key="q.label" :href="q.href" class="group flex flex-col items-center gap-2 rounded-2xl p-1 text-center transition sm:p-2 sm:hover:bg-white/[0.03]">
                <span class="grid h-12 w-12 place-items-center rounded-2xl bg-gradient-to-br to-transparent text-[22px] ring-1 ring-white/[0.06] transition group-hover:scale-105 group-active:scale-95" :class="q.tint">
                    <i :class="q.icon"></i>
                </span>
                <span class="text-xs font-medium text-zinc-300">{{ q.label }}</span>
            </a>
        </div>
    </section>

    <!-- Stats -->
    <section class="mt-5 grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-5">
        <div v-for="s in statCards" :key="s.label" class="card p-4 sm:p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs text-zinc-500 sm:text-sm">{{ s.label }}</p>
                <span class="grid h-8 w-8 place-items-center rounded-lg text-base" :class="s.cls"><i :class="s.icon"></i></span>
            </div>
            <p class="mt-3 font-mono text-2xl font-bold text-white">{{ Number(s.value).toLocaleString() }}</p>
        </div>
    </section>

    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">
        <!-- Markets -->
        <section class="card min-w-0 overflow-hidden lg:col-span-2">
            <div class="flex flex-col gap-3 p-4 pb-3 sm:flex-row sm:items-center sm:justify-between sm:p-5 sm:pb-3">
                <h2 class="font-semibold text-white">Markets</h2>
                <label class="relative block sm:w-56">
                    <i class="ri-search-line pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-sm text-zinc-500"></i>
                    <input v-model="marketSearch" type="search" placeholder="Search pair" class="w-full rounded-lg border border-white/[0.06] bg-white/[0.03] py-2 pr-3 pl-9 text-sm text-zinc-200 placeholder:text-zinc-500 focus:border-brand-500/50 focus:outline-none" />
                </label>
            </div>
            <div class="flex gap-1 overflow-x-auto px-4 pb-3 [scrollbar-width:none] sm:px-5">
                <button
                    v-for="t in marketTabs"
                    :key="t.key"
                    class="shrink-0 rounded-full px-3.5 py-1.5 text-xs font-semibold transition"
                    :class="marketTab === t.key ? 'bg-white/[0.08] text-white' : 'text-zinc-500 hover:text-zinc-200'"
                    @click="marketTab = t.key"
                >
                    {{ t.label }}
                </button>
            </div>
            <div class="grid grid-cols-[1fr_auto_5.5rem] gap-x-3 border-y border-white/[0.04] px-4 py-2 text-[11px] font-medium tracking-wider text-zinc-500 uppercase sm:px-5">
                <span>Pair</span><span class="text-right">Last price</span><span class="text-right">24h</span>
            </div>
            <ul class="divide-y divide-white/[0.04]">
                <li v-for="m in visibleMarkets" :key="m.symbol">
                    <a :href="tradeUrl(m.symbol)" class="grid grid-cols-[1fr_auto_5.5rem] items-center gap-x-3 px-4 py-3 transition hover:bg-white/[0.02] active:bg-white/[0.04] sm:px-5">
                        <span class="flex min-w-0 items-center gap-3">
                            <CoinIcon :src="m.image" :symbol="m.base" size="h-8 w-8" />
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold text-white">{{ m.base }}<span class="font-normal text-zinc-500">/{{ m.symbol.split('_')[1] }}</span></span>
                                <span class="block truncate text-xs text-zinc-500">Vol {{ formatCompact(m.volume) }}</span>
                            </span>
                        </span>
                        <span class="text-right font-mono text-sm font-semibold text-zinc-100">{{ formatPrice(m.price) }}</span>
                        <span class="rounded-lg py-1.5 text-center font-mono text-xs font-bold" :class="m.change24h >= 0 ? 'bg-up/15 text-up' : 'bg-down/15 text-down'">
                            {{ formatPercent(m.change24h) }}
                        </span>
                    </a>
                </li>
                <li v-if="!visibleMarkets.length" class="px-5 py-10 text-center text-sm text-zinc-500">No pairs match “{{ marketSearch }}”.</li>
            </ul>
            <button v-if="!marketSearch && markets.length > 8" class="flex w-full items-center justify-center gap-1 border-t border-white/[0.04] py-3 text-sm font-medium text-brand-300 transition hover:bg-white/[0.02]" @click="showAllMarkets = !showAllMarkets">
                {{ showAllMarkets ? 'Show less' : `Show all ${markets.length} pairs` }}
                <i :class="showAllMarkets ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'"></i>
            </button>
        </section>

        <!-- Side column -->
        <div class="min-w-0 space-y-5">
            <!-- Referral -->
            <section class="relative overflow-hidden rounded-3xl border border-brand-500/20 bg-gradient-to-br from-brand-600/25 via-ink-900 to-ink-900 p-5 sm:p-6">
                <i class="ri-gift-2-line pointer-events-none absolute -right-4 -bottom-6 text-[120px] text-brand-500/10"></i>
                <h2 class="font-semibold text-white">Invite friends, earn together</h2>
                <p class="mt-1 text-sm text-zinc-400">Share your link and earn commission on every referral.</p>
                <div class="relative mt-4 flex items-center gap-2 rounded-xl border border-white/10 bg-ink-950/60 p-1.5 pl-3">
                    <span class="min-w-0 flex-1 truncate font-mono text-xs text-zinc-400">{{ shell.referralLink }}</span>
                    <button class="btn-primary shrink-0 px-3 py-1.5 text-xs" @click="copy(shell.referralLink, 'Referral link')"><i class="ri-file-copy-line"></i> Copy</button>
                </div>
                <a :href="links.referrals" class="relative mt-3 inline-flex items-center gap-1 text-xs font-medium text-brand-300 hover:text-brand-200">View my referrals <i class="ri-arrow-right-line"></i></a>
            </section>

            <!-- Explore -->
            <section class="card p-5 sm:p-6">
                <h2 class="font-semibold text-white">Explore &amp; earn</h2>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <a v-for="p in promos" :key="p.title" :href="p.href" class="group rounded-2xl border border-white/[0.06] bg-gradient-to-br p-4 transition hover:border-white/15" :class="p.grad">
                        <i :class="p.icon" class="text-2xl text-white/90"></i>
                        <p class="mt-3 text-sm font-semibold text-white">{{ p.title }}</p>
                        <p class="mt-0.5 text-[11px] leading-4 text-zinc-400">{{ p.text }}</p>
                    </a>
                </div>
            </section>
        </div>
    </div>

    <!-- Activity -->
    <section class="card mt-5 overflow-hidden">
        <div class="flex items-center justify-between gap-3 p-4 sm:p-5">
            <div class="flex rounded-xl bg-white/[0.04] p-1">
                <button class="rounded-lg px-3.5 py-1.5 text-xs font-semibold transition sm:text-sm" :class="activityTab === 'orders' ? 'bg-ink-700 text-white shadow' : 'text-zinc-500'" @click="activityTab = 'orders'">Recent orders</button>
                <button class="rounded-lg px-3.5 py-1.5 text-xs font-semibold transition sm:text-sm" :class="activityTab === 'transactions' ? 'bg-ink-700 text-white shadow' : 'text-zinc-500'" @click="activityTab = 'transactions'">Transactions</button>
            </div>
            <a :href="activityTab === 'orders' ? links.orders : links.transactions" class="text-xs font-medium text-brand-300 hover:text-brand-200">View all</a>
        </div>

        <!-- Orders -->
        <template v-if="activityTab === 'orders'">
            <div v-if="!recentOrders.length" class="px-5 pb-12 pt-6 text-center">
                <i class="ri-file-list-3-line text-3xl text-zinc-600"></i>
                <p class="mt-2 text-sm text-zinc-400">You have not placed any orders yet.</p>
                <a :href="links.trade" class="btn-ghost mt-4 py-2 text-xs">Start trading</a>
            </div>
            <template v-else>
                <ul class="divide-y divide-white/[0.04] border-t border-white/[0.04] md:hidden">
                    <li v-for="o in recentOrders" :key="o.id" class="flex items-center gap-3 px-4 py-3.5">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl text-lg" :class="o.side === 'buy' ? 'bg-up/10 text-up' : 'bg-down/10 text-down'">
                            <i :class="o.side === 'buy' ? 'ri-arrow-left-down-line' : 'ri-arrow-right-up-line'"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-white">{{ o.pair }} <span class="text-xs font-normal text-zinc-500">· {{ o.type }}</span></p>
                            <p class="text-xs text-zinc-500">{{ timeAgo(o.date) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-mono text-sm text-zinc-100">{{ formatAmount(o.amount) }}</p>
                            <span class="mt-0.5 inline-block rounded-md px-1.5 py-0.5 text-[10px] font-semibold capitalize" :class="statusStyle[o.status]">{{ o.status }}</span>
                        </div>
                    </li>
                </ul>
                <div class="hidden overflow-x-auto md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-y border-white/[0.04] text-left text-[11px] tracking-wider text-zinc-500 uppercase">
                                <th class="px-5 py-2.5 font-medium">Pair</th>
                                <th class="px-5 py-2.5 font-medium">Side</th>
                                <th class="px-5 py-2.5 text-right font-medium">Price</th>
                                <th class="px-5 py-2.5 text-right font-medium">Amount</th>
                                <th class="px-5 py-2.5 font-medium">Filled</th>
                                <th class="px-5 py-2.5 font-medium">Status</th>
                                <th class="px-5 py-2.5 text-right font-medium">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.04]">
                            <tr v-for="o in recentOrders" :key="o.id" class="transition hover:bg-white/[0.02]">
                                <td class="px-5 py-3 font-semibold text-white">{{ o.pair }} <span class="block text-xs font-normal text-zinc-500">{{ o.type }}</span></td>
                                <td class="px-5 py-3"><span class="font-semibold capitalize" :class="o.side === 'buy' ? 'text-up' : 'text-down'">{{ o.side }}</span></td>
                                <td class="px-5 py-3 text-right font-mono text-zinc-200">{{ formatPrice(o.rate) }}</td>
                                <td class="px-5 py-3 text-right font-mono text-zinc-200">{{ formatAmount(o.amount) }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="h-1.5 w-16 overflow-hidden rounded-full bg-white/[0.06]"><span class="block h-full rounded-full bg-brand-500" :style="{ width: Math.min(o.filled, 100) + '%' }"></span></span>
                                        <span class="font-mono text-xs text-zinc-400">{{ Math.round(o.filled) }}%</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3"><span class="rounded-md px-2 py-1 text-xs font-semibold capitalize" :class="statusStyle[o.status]">{{ o.status }}</span></td>
                                <td class="px-5 py-3 text-right text-xs text-zinc-500">{{ timeAgo(o.date) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </template>

        <!-- Transactions -->
        <template v-else>
            <div v-if="!recentTransactions.length" class="px-5 pb-12 pt-6 text-center">
                <i class="ri-arrow-left-right-line text-3xl text-zinc-600"></i>
                <p class="mt-2 text-sm text-zinc-400">No transactions yet.</p>
            </div>
            <ul v-else class="divide-y divide-white/[0.04] border-t border-white/[0.04]">
                <li v-for="t in recentTransactions" :key="t.trx + t.date" class="flex items-center gap-3 px-4 py-3.5 sm:px-5">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl text-lg" :class="t.type === '+' ? 'bg-up/10 text-up' : 'bg-down/10 text-down'">
                        <i :class="t.type === '+' ? 'ri-arrow-down-line' : 'ri-arrow-up-line'"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-100">{{ t.details }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ timeAgo(t.date) }} · <span class="font-mono">{{ t.trx }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="font-mono text-sm font-semibold" :class="t.type === '+' ? 'text-up' : 'text-down'">{{ t.type }}{{ formatAmount(t.amount) }}</p>
                        <p class="hidden font-mono text-xs text-zinc-500 sm:block">Bal {{ mask(formatAmount(t.balance)) }}</p>
                    </div>
                </li>
            </ul>
        </template>
    </section>

    <!-- On-chain activity -->
    <section class="card mt-5 overflow-hidden">
        <div class="flex items-center justify-between p-4 sm:p-5">
            <h2 class="flex items-center gap-2 font-semibold text-white">
                <i class="ri-links-line text-brand-400"></i> Live Ethereum activity
            </h2>
            <span class="flex items-center gap-1.5 text-[11px] font-semibold text-up"><span class="h-1.5 w-1.5 animate-pulse-dot rounded-full bg-up"></span>LIVE</span>
        </div>
        <ul v-if="chainState === 'ready'" class="divide-y divide-white/[0.04] border-t border-white/[0.04]">
            <li v-for="tx in chain" :key="tx.url">
                <a :href="tx.url" target="_blank" rel="noopener" class="grid grid-cols-[auto_1fr_auto] items-center gap-3 px-4 py-3 transition hover:bg-white/[0.02] sm:px-5">
                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-white/[0.04] text-zinc-400"><i class="ri-file-text-line"></i></span>
                    <span class="min-w-0">
                        <span class="block truncate font-mono text-xs text-brand-300">{{ tx.trx_hash }}</span>
                        <span class="block truncate font-mono text-[11px] text-zinc-500">{{ tx.from }} → {{ tx.to }}</span>
                    </span>
                    <span class="font-mono text-xs text-zinc-200">{{ tx.value_amount }}</span>
                </a>
            </li>
        </ul>
        <div v-else-if="chainState === 'loading'" class="space-y-2 border-t border-white/[0.04] p-4">
            <div v-for="n in 3" :key="n" class="h-10 animate-pulse rounded-lg bg-white/[0.03]"></div>
        </div>
        <p v-else class="border-t border-white/[0.04] px-5 py-8 text-center text-sm text-zinc-500">On-chain feed is unavailable right now.</p>
    </section>
</template>

<style>
@keyframes wave {
    0%, 60%, 100% { transform: rotate(0); }
    10%, 30% { transform: rotate(14deg); }
    20% { transform: rotate(-8deg); }
    40% { transform: rotate(-4deg); }
    50% { transform: rotate(10deg); }
}
</style>
