<script>
import SiteLayout from '@/Layouts/SiteLayout.vue';
import UserLayout from '@/Layouts/UserLayout.vue';

export default {
    layout: (h, page) => h(page.props.shell ? UserLayout : SiteLayout, () => page),
};
</script>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import CoinIcon from '@/Components/CoinIcon.vue';
import Modal from '@/Components/UI/Modal.vue';
import Badge from '@/Components/UI/Badge.vue';
import TradingViewChart from '@/Components/Trade/TradingViewChart.vue';
import OrderBook from '@/Components/Trade/OrderBook.vue';
import OrderForm from '@/Components/Trade/OrderForm.vue';
import PairPicker from '@/Components/Trade/PairPicker.vue';
import { binanceSymbol, useBinanceStream } from '@/composables/useBinanceStream';
import { getJson } from '@/utils/http';
import { formatAmount, formatCompact, formatPercent, formatPrice, timeAgo } from '@/utils/format';
import { orderStatusTone } from '@/utils/display';

const props = defineProps({ pair: Object, balances: Object, markets: Array, urls: Object });
const page = usePage();
const signedIn = computed(() => !!page.props.shell);

/* Live ticker (Binance, as the Blade pair header did) */
const ticker = reactive({ price: props.pair.price, change: props.pair.change24h, high: null, low: null, volume: null, quoteVolume: null, dir: null });
useBinanceStream(`${binanceSymbol(props.pair.symbol)}@ticker`, (d) => {
    const p = Number(d.c);
    if (!p) return;
    ticker.dir = p > ticker.price ? 'up' : p < ticker.price ? 'down' : ticker.dir;
    Object.assign(ticker, { price: p, change: Number(d.P), high: Number(d.h), low: Number(d.l), volume: Number(d.v), quoteVolume: Number(d.q) });
    document.title = `${formatPrice(p)} | ${props.pair.coin.symbol}/${props.pair.market} — ${page.props.site.name}`;
});

const balances = reactive({ ...props.balances });
const side = ref('buy');
const chartSymbol = computed(() => `${props.pair.listedMarket}:${props.pair.symbol.replace('_', '')}`);

/* Market trades (local endpoint, polled — Blade used pusher for the same list) */
const trades = ref([]);
async function loadTrades() {
    try {
        const res = await getJson(props.urls.history);
        if (res.success) trades.value = (res.trades || []).slice(0, 30);
    } catch {}
}

/* My orders for this pair */
const orders = ref([]);
const orderTab = ref('open');
async function loadOrders() {
    if (!props.urls.orders) return;
    try {
        const res = await getJson(props.urls.orders, { status: orderTab.value === 'open' ? 'open' : 'all' });
        if (res.success) orders.value = (res.orders || []).filter((o) => o.pair?.symbol === props.pair.symbol).slice(0, 30);
    } catch {}
}
const statusName = (s) => ({ 0: 'open', 1: 'completed', 2: 'pending', 3: 'positioned', 9: 'canceled' })[s] || 'open';

let timer;
onMounted(() => {
    loadTrades();
    loadOrders();
    timer = setInterval(() => {
        if (document.hidden) return;
        loadTrades();
        loadOrders();
    }, 15000);
});
onBeforeUnmount(() => clearInterval(timer));

function onPlaced(data) {
    if (data.wallet_balance != null) {
        if (Number(data.order?.order_side) === 1) balances.market = Number(data.wallet_balance);
        else balances.coin = Number(data.wallet_balance);
    }
    orderSheet.value = false;
    orderTab.value = 'open';
    loadOrders();
}

/* Render only one layout so the chart and sockets are not duplicated. */
const mq = window.matchMedia('(min-width: 1024px)');
const isDesktop = ref(mq.matches);
const onMq = (e) => (isDesktop.value = e.matches);
onMounted(() => mq.addEventListener('change', onMq));
onBeforeUnmount(() => mq.removeEventListener('change', onMq));

/* Mobile */
const mobileTab = ref('chart');
const orderSheet = ref(false);
const pairSheet = ref(false);
function openOrder(s) {
    side.value = s;
    orderSheet.value = true;
}
// Orders fill at market price, so picking a book level just opens the form on phones.
function pick() {
    if (!isDesktop.value) openOrder(side.value);
}
</script>

<template>
    <Head :title="`${formatPrice(pair.price)} | ${pair.coin.symbol}/${pair.market}`" />

    <div :class="signedIn ? '-mx-4 -mt-5 sm:-mx-6 lg:mx-0 lg:mt-0' : 'pt-16 lg:pt-20'">
        <!-- Pair header -->
        <div class="flex items-center gap-4 overflow-x-auto border-b border-white/[0.06] bg-ink-900/70 px-4 py-3 [scrollbar-width:none] lg:rounded-t-2xl lg:border lg:px-5">
            <button class="flex shrink-0 items-center gap-2.5 rounded-xl py-1 pr-2 hover:bg-white/[0.04]" @click="pairSheet = true">
                <CoinIcon :src="pair.coin.image" :symbol="pair.coin.symbol" size="h-8 w-8" />
                <span class="text-left">
                    <span class="flex items-center gap-1 text-base font-bold text-white">{{ pair.coin.symbol }}/{{ pair.market }} <i class="ri-arrow-down-s-line text-zinc-500"></i></span>
                    <span class="block text-[11px] text-zinc-500">{{ pair.coin.name }}</span>
                </span>
            </button>
            <div class="shrink-0">
                <p class="font-mono text-lg font-bold" :class="ticker.dir === 'down' ? 'text-down' : 'text-up'">{{ formatPrice(ticker.price) }}</p>
                <p class="font-mono text-[11px]" :class="ticker.change >= 0 ? 'text-up' : 'text-down'">{{ formatPercent(ticker.change) }}</p>
            </div>
            <dl class="flex shrink-0 gap-5 text-[11px]">
                <div><dt class="text-zinc-500">24h high</dt><dd class="font-mono text-zinc-200">{{ ticker.high ? formatPrice(ticker.high) : '—' }}</dd></div>
                <div><dt class="text-zinc-500">24h low</dt><dd class="font-mono text-zinc-200">{{ ticker.low ? formatPrice(ticker.low) : '—' }}</dd></div>
                <div><dt class="text-zinc-500">24h vol ({{ pair.coin.symbol }})</dt><dd class="font-mono text-zinc-200">{{ ticker.volume ? formatCompact(ticker.volume) : '—' }}</dd></div>
                <div class="hidden sm:block"><dt class="text-zinc-500">24h vol ({{ pair.market }})</dt><dd class="font-mono text-zinc-200">{{ ticker.quoteVolume ? formatCompact(ticker.quoteVolume) : '—' }}</dd></div>
            </dl>
        </div>

        <!-- Desktop terminal -->
        <div v-if="isDesktop" class="grid gap-px overflow-hidden bg-white/[0.06] lg:grid-cols-[260px_minmax(0,1fr)_300px] lg:rounded-b-2xl lg:border lg:border-t-0 lg:border-white/[0.06]">
            <div class="bg-ink-900"><OrderBook :pair="pair" :last-price="ticker.price" :rows="12" @pick="pick" /></div>
            <div class="flex min-w-0 flex-col gap-px">
                <div class="h-[460px] bg-ink-900"><TradingViewChart :symbol="chartSymbol" /></div>
                <div class="grid grid-cols-2 gap-px">
                    <div class="bg-ink-900 p-4"><OrderForm :pair="pair" :balances="balances" :urls="urls" side="buy" fixed-side :market-price="ticker.price" :signed-in="signedIn" @placed="onPlaced" /></div>
                    <div class="bg-ink-900 p-4"><OrderForm :pair="pair" :balances="balances" :urls="urls" side="sell" fixed-side :market-price="ticker.price" :signed-in="signedIn" @placed="onPlaced" /></div>
                </div>
            </div>
            <div class="flex flex-col gap-px">
                <div class="bg-ink-900"><PairPicker :urls="urls" :markets="markets" :current="pair.symbol" inline /></div>
                <div class="flex-1 bg-ink-900">
                    <h3 class="px-3 py-2.5 text-sm font-semibold text-white">Market trades</h3>
                    <div class="grid grid-cols-3 px-3 pb-1 text-[10px] tracking-wider text-zinc-500 uppercase"><span>Price</span><span class="text-right">Amount</span><span class="text-right">Time</span></div>
                    <div class="max-h-[420px] overflow-y-auto">
                        <div v-for="t in trades" :key="t.id" class="grid grid-cols-3 px-3 py-[3px] font-mono text-xs">
                            <span :class="Number(t.trade_side) === 1 ? 'text-up' : 'text-down'">{{ formatPrice(t.rate) }}</span>
                            <span class="text-right text-zinc-300">{{ formatAmount(t.amount) }}</span>
                            <span class="text-right text-zinc-500">{{ (t.formatted_date || '').split(' ')[1] }}</span>
                        </div>
                        <p v-if="!trades.length" class="p-6 text-center text-xs text-zinc-500">No trades yet</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile -->
        <div v-else>
            <div class="flex border-b border-white/[0.06] bg-ink-900/70 px-2">
                <button v-for="t in [['chart', 'Chart'], ['book', 'Order book'], ['trades', 'Trades']]" :key="t[0]" class="relative px-3 py-3 text-sm font-medium" :class="mobileTab === t[0] ? 'text-white' : 'text-zinc-500'" @click="mobileTab = t[0]">
                    {{ t[1] }}<span v-if="mobileTab === t[0]" class="absolute inset-x-3 bottom-0 h-0.5 rounded-full bg-brand-400"></span>
                </button>
            </div>
            <div v-show="mobileTab === 'chart'" class="h-[380px] bg-ink-900"><TradingViewChart v-if="mobileTab === 'chart'" :symbol="chartSymbol" /></div>
            <div v-if="mobileTab === 'book'" class="bg-ink-900 pb-2"><OrderBook :pair="pair" :last-price="ticker.price" :rows="10" @pick="pick" /></div>
            <div v-if="mobileTab === 'trades'" class="bg-ink-900 pb-2">
                <div class="grid grid-cols-3 px-4 py-2 text-[10px] tracking-wider text-zinc-500 uppercase"><span>Price</span><span class="text-right">Amount</span><span class="text-right">Time</span></div>
                <div v-for="t in trades" :key="t.id" class="grid grid-cols-3 px-4 py-1 font-mono text-xs">
                    <span :class="Number(t.trade_side) === 1 ? 'text-up' : 'text-down'">{{ formatPrice(t.rate) }}</span>
                    <span class="text-right text-zinc-300">{{ formatAmount(t.amount) }}</span>
                    <span class="text-right text-zinc-500">{{ (t.formatted_date || '').split(' ')[1] }}</span>
                </div>
                <p v-if="!trades.length" class="p-6 text-center text-xs text-zinc-500">No trades yet</p>
            </div>
        </div>

        <!-- My orders -->
        <section v-if="signedIn" class="card mx-4 mt-4 overflow-hidden sm:mx-6 lg:mx-0">
            <div class="flex items-center justify-between gap-3 p-3 sm:p-4">
                <div class="flex rounded-xl bg-white/[0.04] p-1">
                    <button class="rounded-lg px-3 py-1.5 text-xs font-semibold" :class="orderTab === 'open' ? 'bg-ink-700 text-white' : 'text-zinc-500'" @click="orderTab = 'open'; loadOrders()">Open orders</button>
                    <button class="rounded-lg px-3 py-1.5 text-xs font-semibold" :class="orderTab === 'all' ? 'bg-ink-700 text-white' : 'text-zinc-500'" @click="orderTab = 'all'; loadOrders()">Order history</button>
                </div>
                <a :href="urls.allOrders" class="text-xs font-medium text-brand-300 hover:text-brand-200">All orders</a>
            </div>
            <div v-if="!orders.length" class="px-5 pb-8 pt-2 text-center text-sm text-zinc-500">No {{ orderTab === 'open' ? 'open orders' : 'orders' }} for {{ pair.coin.symbol }}/{{ pair.market }}</div>
            <ul v-else class="divide-y divide-white/[0.04] border-t border-white/[0.04]">
                <li v-for="o in orders" :key="o.id" class="flex items-center gap-3 px-4 py-3">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg text-sm" :class="Number(o.order_side) === 1 ? 'bg-up/10 text-up' : 'bg-down/10 text-down'">
                        <i :class="Number(o.order_side) === 1 ? 'ri-arrow-left-down-line' : 'ri-arrow-right-up-line'"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-white">{{ Number(o.order_side) === 1 ? 'Buy' : 'Sell' }} <span class="font-mono font-normal text-zinc-400">{{ formatAmount(o.amount) }} @ {{ formatPrice(o.rate) }}</span></p>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="h-1 w-16 overflow-hidden rounded-full bg-white/[0.06]"><span class="block h-full bg-brand-500" :style="{ width: Math.min(Number(o.filed_percentage), 100) + '%' }"></span></span>
                            <span class="text-[11px] text-zinc-500">{{ Math.round(o.filed_percentage) }}% · {{ timeAgo(o.created_at) }}</span>
                        </div>
                    </div>
                    <Badge :tone="orderStatusTone(statusName(o.status))" class="capitalize">{{ statusName(o.status) }}</Badge>
                </li>
            </ul>
        </section>
    </div>

    <!-- Mobile buy/sell bar -->
    <div class="fixed inset-x-0 z-30 grid grid-cols-2 gap-2 border-t border-white/[0.06] bg-ink-950/95 px-4 py-3 backdrop-blur-xl lg:hidden" :class="signedIn ? 'bottom-[calc(4rem+env(safe-area-inset-bottom))]' : 'bottom-0'">
        <button class="btn bg-up py-3 text-ink-950" @click="openOrder('buy')">Buy {{ pair.coin.symbol }}</button>
        <button class="btn bg-down py-3 text-white" @click="openOrder('sell')">Sell {{ pair.coin.symbol }}</button>
    </div>
    <div class="h-20 lg:hidden"></div>

    <Modal :show="orderSheet" :title="`${pair.coin.symbol}/${pair.market}`" @close="orderSheet = false">
        <OrderForm v-model:side="side" :pair="pair" :balances="balances" :urls="urls" :market-price="ticker.price" :signed-in="signedIn" @placed="onPlaced" />
    </Modal>

    <Modal :show="pairSheet" title="Select pair" size="max-w-md" @close="pairSheet = false">
        <div class="-mx-5 h-[60vh] sm:-mx-6"><PairPicker :urls="urls" :markets="markets" :current="pair.symbol" /></div>
    </Modal>
</template>
