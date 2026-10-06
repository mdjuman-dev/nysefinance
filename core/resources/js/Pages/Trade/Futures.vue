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
import FuturesOrderForm from '@/Components/Futures/FuturesOrderForm.vue';
import { binanceSymbol, useBinanceStream } from '@/composables/useBinanceStream';
import { useToast } from '@/composables/useToast';
import { getJson, postJson, messageText } from '@/utils/http';
import { navigate } from '@/utils/spa';
import { formatAmount, formatCompact, formatMoney, formatPercent, formatPrice, timeAgo } from '@/utils/format';

const props = defineProps({ pair: Object, pairs: Array, balances: Object, urls: Object });
const page = usePage();
const signedIn = computed(() => !!page.props.shell);
const toast = useToast();

/* Live ticker for the selected pair */
const ticker = reactive({ price: props.pair.price, change: props.pair.change24h, high: null, low: null, volume: null, dir: null });
useBinanceStream(`${binanceSymbol(props.pair.symbol)}@ticker`, (d) => {
    const p = Number(d.c);
    if (!p) return;
    ticker.dir = p > ticker.price ? 'up' : p < ticker.price ? 'down' : ticker.dir;
    Object.assign(ticker, { price: p, change: Number(d.P), high: Number(d.h), low: Number(d.l), volume: Number(d.q) });
    document.title = `${formatPrice(p)} | ${props.pair.coin.symbol}USDT Perp — ${page.props.site.name}`;
});
const chartSymbol = computed(() => `${props.pair.listedMarket}:${props.pair.symbol.replace('_', '')}`);

/* Account + positions (polled; the server sweeps TP/SL/liquidations on each poll) */
const balances = reactive({ spot: 0, futures: 0, inMargin: 0, ...(props.balances || {}) });
const open = ref([]);
const history = ref([]);
const marks = reactive({});
const loaded = ref(false);

async function loadPositions() {
    if (!props.urls.positions) return;
    try {
        const res = await getJson(props.urls.positions);
        if (!res.success) return;
        const before = new Set(open.value.map((p) => p.id));
        open.value = res.open;
        history.value = res.history;
        Object.assign(balances, res.balances);
        // Tell the user about positions the server settled since the last poll.
        if (loaded.value) {
            res.history.filter((h) => before.has(h.id)).forEach((h) => {
                const label = { take_profit: 'Take-profit hit', stop_loss: 'Stop-loss hit', liquidation: 'Liquidated' }[h.reason] || (h.status === 'liquidated' ? 'Liquidated' : null);
                if (label) (h.status === 'liquidated' ? toast.error : toast.info)(`${label}: ${h.coin} ${h.side} ${h.leverage}x`);
            });
        }
        loaded.value = true;
        refreshMarks();
    } catch {}
}

async function refreshMarks() {
    const symbols = [...new Set(open.value.map((p) => p.symbol.replace('_', '')))].filter((s) => s !== props.pair.symbol.replace('_', ''));
    if (!symbols.length) return;
    try {
        const res = await fetch(`https://api.binance.com/api/v3/ticker/price?symbols=${encodeURIComponent(JSON.stringify(symbols))}`);
        if (res.ok) (await res.json()).forEach((t) => (marks[t.symbol] = Number(t.price)));
    } catch {}
}
const markOf = (p) => (p.symbol === props.pair.symbol ? ticker.price : marks[p.symbol.replace('_', '')] ?? p.entry);
const pnlOf = (p) => ((p.side === 'long' ? markOf(p) - p.entry : p.entry - markOf(p)) * p.size);
const roeOf = (p) => (p.margin ? (pnlOf(p) / p.margin) * 100 : 0);
const totalPnl = computed(() => open.value.reduce((s, p) => s + pnlOf(p), 0));

let timer, markTimer;
onMounted(() => {
    loadPositions();
    timer = setInterval(() => !document.hidden && loadPositions(), 6000);
    markTimer = setInterval(() => !document.hidden && refreshMarks(), 3000);
});
onBeforeUnmount(() => { clearInterval(timer); clearInterval(markTimer); });

function onOpened(newBalances) {
    if (newBalances) Object.assign(balances, newBalances);
    orderSheet.value = false;
    tab.value = 'open';
    loadPositions();
}

/* Close */
const closing = reactive({ show: false, pos: null, busy: false });
async function confirmClose() {
    closing.busy = true;
    const res = await postJson(props.urls.close.replace('__ID__', closing.pos.id));
    closing.busy = false;
    closing.show = false;
    res.success ? toast.success(res.message) : toast.error(messageText(res.message));
    if (res.balances) Object.assign(balances, res.balances);
    loadPositions();
}

/* TP / SL edit */
const tpsl = reactive({ show: false, pos: null, tp: '', sl: '', busy: false });
function editTpSl(p) {
    Object.assign(tpsl, { show: true, pos: p, tp: p.takeProfit ? String(p.takeProfit) : '', sl: p.stopLoss ? String(p.stopLoss) : '' });
}
async function saveTpSl() {
    tpsl.busy = true;
    const res = await postJson(props.urls.tpsl.replace('__ID__', tpsl.pos.id), { take_profit: tpsl.tp || null, stop_loss: tpsl.sl || null });
    tpsl.busy = false;
    if (res.success) { toast.success(res.message); tpsl.show = false; loadPositions(); }
    else toast.error(messageText(res.message));
}

/* Transfer spot ⇄ futures */
const transfer = reactive({ show: false, direction: 'to_futures', amount: '', busy: false });
const transferMax = computed(() => (transfer.direction === 'to_futures' ? balances.spot : balances.futures));
async function doTransfer() {
    transfer.busy = true;
    const res = await postJson(props.urls.transfer, { direction: transfer.direction, amount: transfer.amount });
    transfer.busy = false;
    if (res.success) {
        Object.assign(balances, res.balances);
        toast.success(res.message);
        Object.assign(transfer, { show: false, amount: '' });
    } else toast.error(messageText(res.message));
}

/* Layout */
const mq = window.matchMedia('(min-width: 1024px)');
const isDesktop = ref(mq.matches);
const onMq = (e) => (isDesktop.value = e.matches);
onMounted(() => mq.addEventListener('change', onMq));
onBeforeUnmount(() => mq.removeEventListener('change', onMq));
const mobileTab = ref('chart');
const orderSheet = ref(false);
const pairSheet = ref(false);
const side = ref('long');
const tab = ref('open');
function openOrder(s) { side.value = s; orderSheet.value = true; }
function goPair(sym) { pairSheet.value = false; if (sym !== props.pair.symbol) navigate(props.urls.page.replace('__SYMBOL__', sym)); }
const reasonLabel = (h) => (h.status === 'liquidated' ? 'Liquidated' : { manual: 'Closed', take_profit: 'Take-profit', stop_loss: 'Stop-loss', liquidation: 'Liquidated' }[h.reason] || 'Closed');
</script>

<template>
    <Head :title="`${pair.coin.symbol}USDT Perpetual`" />

    <div :class="signedIn ? '-mx-4 -mt-5 sm:-mx-6 lg:mx-0 lg:mt-0' : 'pt-16 lg:pt-20'">
        <!-- Header -->
        <div class="flex items-center gap-4 overflow-x-auto border-b border-white/[0.06] bg-ink-900/70 px-4 py-3 [scrollbar-width:none] lg:rounded-t-2xl lg:border lg:px-5">
            <button class="flex shrink-0 items-center gap-2.5 rounded-xl py-1 pr-2 hover:bg-white/[0.04]" @click="pairSheet = true">
                <CoinIcon :src="pair.coin.image" :symbol="pair.coin.symbol" size="h-8 w-8" />
                <span class="text-left">
                    <span class="flex items-center gap-1.5 text-base font-bold text-white">{{ pair.coin.symbol }}USDT <span class="rounded bg-amber-400/15 px-1.5 py-0.5 text-[10px] font-semibold text-amber-300">PERP</span><i class="ri-arrow-down-s-line text-zinc-500"></i></span>
                    <span class="block text-[11px] text-zinc-500">Up to {{ pair.maxLeverage }}x · {{ pair.coin.name }}</span>
                </span>
            </button>
            <div class="shrink-0">
                <p class="font-mono text-lg font-bold" :class="ticker.dir === 'down' ? 'text-down' : 'text-up'">{{ formatPrice(ticker.price) }}</p>
                <p class="font-mono text-[11px]" :class="ticker.change >= 0 ? 'text-up' : 'text-down'">{{ formatPercent(ticker.change) }}</p>
            </div>
            <dl class="flex shrink-0 gap-5 text-[11px]">
                <div><dt class="text-zinc-500">24h high</dt><dd class="font-mono text-zinc-200">{{ ticker.high ? formatPrice(ticker.high) : '—' }}</dd></div>
                <div><dt class="text-zinc-500">24h low</dt><dd class="font-mono text-zinc-200">{{ ticker.low ? formatPrice(ticker.low) : '—' }}</dd></div>
                <div><dt class="text-zinc-500">24h vol (USDT)</dt><dd class="font-mono text-zinc-200">{{ ticker.volume ? formatCompact(ticker.volume) : '—' }}</dd></div>
                <div><dt class="text-zinc-500">Fee</dt><dd class="font-mono text-zinc-200">{{ pair.feePercent }}%</dd></div>
            </dl>
        </div>

        <!-- Desktop -->
        <div v-if="isDesktop" class="grid grid-cols-[250px_minmax(0,1fr)_320px] gap-px overflow-hidden rounded-b-2xl border border-t-0 border-white/[0.06] bg-white/[0.06]">
            <div class="bg-ink-900"><OrderBook :pair="{ symbol: pair.symbol, market: 'USDT' }" :last-price="ticker.price" :rows="13" /></div>
            <div class="h-[600px] bg-ink-900"><TradingViewChart :symbol="chartSymbol" /></div>
            <div class="bg-ink-900 p-4">
                <FuturesOrderForm v-model:side="side" :pair="pair" :price="ticker.price" :balances="balances" :urls="urls" :signed-in="signedIn" @opened="onOpened" @transfer="transfer.show = true" />
            </div>
        </div>

        <!-- Mobile -->
        <div v-else>
            <div class="flex border-b border-white/[0.06] bg-ink-900/70 px-2">
                <button v-for="t in [['chart', 'Chart'], ['book', 'Order book']]" :key="t[0]" class="relative px-3 py-3 text-sm font-medium" :class="mobileTab === t[0] ? 'text-white' : 'text-zinc-500'" @click="mobileTab = t[0]">
                    {{ t[1] }}<span v-if="mobileTab === t[0]" class="absolute inset-x-3 bottom-0 h-0.5 rounded-full bg-brand-400"></span>
                </button>
            </div>
            <div v-show="mobileTab === 'chart'" class="h-[360px] bg-ink-900"><TradingViewChart v-if="mobileTab === 'chart'" :symbol="chartSymbol" /></div>
            <div v-if="mobileTab === 'book'" class="bg-ink-900 pb-2"><OrderBook :pair="{ symbol: pair.symbol, market: 'USDT' }" :last-price="ticker.price" :rows="10" /></div>
        </div>

        <!-- Positions -->
        <section v-if="signedIn" class="card mx-4 mt-4 overflow-hidden sm:mx-6 lg:mx-0">
            <div class="flex flex-wrap items-center justify-between gap-3 p-3 sm:p-4">
                <div class="flex rounded-xl bg-white/[0.04] p-1">
                    <button class="rounded-lg px-3 py-1.5 text-xs font-semibold" :class="tab === 'open' ? 'bg-ink-700 text-white' : 'text-zinc-500'" @click="tab = 'open'">Positions ({{ open.length }})</button>
                    <button class="rounded-lg px-3 py-1.5 text-xs font-semibold" :class="tab === 'history' ? 'bg-ink-700 text-white' : 'text-zinc-500'" @click="tab = 'history'">History</button>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <span class="text-zinc-500">Margin in use <span class="font-mono text-zinc-200">{{ formatMoney(balances.inMargin) }}</span></span>
                    <span v-if="open.length" class="text-zinc-500">Unrealized <span class="font-mono font-semibold" :class="totalPnl >= 0 ? 'text-up' : 'text-down'">{{ totalPnl >= 0 ? '+' : '' }}{{ formatMoney(totalPnl) }}</span></span>
                </div>
            </div>

            <!-- Open positions -->
            <template v-if="tab === 'open'">
                <p v-if="!open.length" class="px-5 pb-8 pt-2 text-center text-sm text-zinc-500">No open positions</p>
                <ul v-else class="divide-y divide-white/[0.04] border-t border-white/[0.04]">
                    <li v-for="p in open" :key="p.id" class="p-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-md px-2 py-0.5 text-xs font-bold uppercase" :class="p.side === 'long' ? 'bg-up/15 text-up' : 'bg-down/15 text-down'">{{ p.side }} {{ p.leverage }}x</span>
                            <span class="font-semibold text-white">{{ p.coin }}USDT</span>
                            <span class="ml-auto text-right">
                                <span class="block font-mono text-base font-bold" :class="pnlOf(p) >= 0 ? 'text-up' : 'text-down'">{{ pnlOf(p) >= 0 ? '+' : '' }}{{ formatMoney(pnlOf(p)) }} USDT</span>
                                <span class="block font-mono text-[11px]" :class="roeOf(p) >= 0 ? 'text-up' : 'text-down'">ROE {{ formatPercent(roeOf(p)) }}</span>
                            </span>
                        </div>
                        <dl class="mt-3 grid grid-cols-3 gap-x-3 gap-y-2 text-xs sm:grid-cols-6">
                            <div><dt class="text-zinc-500">Size</dt><dd class="font-mono text-zinc-200">{{ formatAmount(p.size) }}</dd></div>
                            <div><dt class="text-zinc-500">Entry</dt><dd class="font-mono text-zinc-200">{{ formatPrice(p.entry) }}</dd></div>
                            <div><dt class="text-zinc-500">Mark</dt><dd class="font-mono text-zinc-200">{{ formatPrice(markOf(p)) }}</dd></div>
                            <div><dt class="text-zinc-500">Liq. price</dt><dd class="font-mono text-amber-300">{{ formatPrice(p.liquidation) }}</dd></div>
                            <div><dt class="text-zinc-500">Margin</dt><dd class="font-mono text-zinc-200">{{ formatMoney(p.margin) }}</dd></div>
                            <div><dt class="text-zinc-500">TP / SL</dt><dd class="font-mono text-zinc-200"><span class="text-up">{{ p.takeProfit ? formatPrice(p.takeProfit) : '—' }}</span> / <span class="text-down">{{ p.stopLoss ? formatPrice(p.stopLoss) : '—' }}</span></dd></div>
                        </dl>
                        <div class="mt-3 flex gap-2">
                            <button class="btn-ghost flex-1 py-2 text-xs" @click="editTpSl(p)"><i class="ri-equalizer-line"></i> TP / SL</button>
                            <button class="btn flex-1 bg-white/[0.08] py-2 text-xs text-white hover:bg-white/[0.14]" @click="Object.assign(closing, { show: true, pos: p })"><i class="ri-close-circle-line"></i> Close at market</button>
                        </div>
                    </li>
                </ul>
            </template>

            <!-- History -->
            <template v-else>
                <p v-if="!history.length" class="px-5 pb-8 pt-2 text-center text-sm text-zinc-500">No closed positions yet</p>
                <ul v-else class="divide-y divide-white/[0.04] border-t border-white/[0.04]">
                    <li v-for="h in history" :key="h.id" class="flex items-center gap-3 px-4 py-3">
                        <span class="shrink-0 rounded-md px-2 py-0.5 text-[11px] font-bold uppercase" :class="h.side === 'long' ? 'bg-up/15 text-up' : 'bg-down/15 text-down'">{{ h.side }} {{ h.leverage }}x</span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-white">{{ h.coin }}USDT <span class="font-mono text-xs font-normal text-zinc-500">{{ formatPrice(h.entry) }} → {{ formatPrice(h.closePrice) }}</span></p>
                            <p class="text-[11px] text-zinc-500">{{ timeAgo(h.closedAt) }} · {{ reasonLabel(h) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-mono text-sm font-semibold" :class="h.pnl >= 0 ? 'text-up' : 'text-down'">{{ h.pnl >= 0 ? '+' : '' }}{{ formatMoney(h.pnl) }}</p>
                            <Badge :tone="h.status === 'liquidated' ? 'danger' : h.pnl >= 0 ? 'success' : 'neutral'">Paid {{ formatMoney(h.payout) }}</Badge>
                        </div>
                    </li>
                </ul>
            </template>
        </section>
    </div>

    <!-- Mobile long/short bar -->
    <template v-if="!isDesktop">
        <div class="fixed inset-x-0 z-30 grid grid-cols-2 gap-2 border-t border-white/[0.06] bg-ink-950/95 px-4 py-3 backdrop-blur-xl" :class="signedIn ? 'bottom-[calc(4rem+env(safe-area-inset-bottom))]' : 'bottom-0'">
            <button class="btn bg-up py-3 text-ink-950" @click="openOrder('long')">Long</button>
            <button class="btn bg-down py-3 text-white" @click="openOrder('short')">Short</button>
        </div>
        <div class="h-20"></div>
        <Modal :show="orderSheet" :title="`${pair.coin.symbol}USDT Perpetual`" @close="orderSheet = false">
            <FuturesOrderForm v-model:side="side" :pair="pair" :price="ticker.price" :balances="balances" :urls="urls" :signed-in="signedIn" @opened="onOpened" @transfer="orderSheet = false; transfer.show = true" />
        </Modal>
    </template>

    <!-- Pair picker -->
    <Modal :show="pairSheet" title="Futures markets" @close="pairSheet = false">
        <div class="max-h-[60vh] space-y-1 overflow-y-auto">
            <button v-for="p in pairs" :key="p.symbol" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left hover:bg-white/[0.04]" :class="p.symbol === pair.symbol ? 'bg-brand-500/10' : ''" @click="goPair(p.symbol)">
                <CoinIcon :src="p.image" :symbol="p.coin" size="h-8 w-8" />
                <span class="flex-1">
                    <span class="block text-sm font-semibold text-white">{{ p.coin }}USDT <span class="text-[10px] font-normal text-zinc-500">Perp</span></span>
                    <span class="block text-[11px] text-amber-300">up to {{ p.maxLeverage }}x</span>
                </span>
                <span class="text-right">
                    <span class="block font-mono text-sm text-zinc-200">{{ formatPrice(p.price) }}</span>
                    <span class="block font-mono text-[11px]" :class="p.change24h >= 0 ? 'text-up' : 'text-down'">{{ formatPercent(p.change24h) }}</span>
                </span>
            </button>
        </div>
    </Modal>

    <!-- Close confirm -->
    <Modal :show="closing.show" title="Close position" @close="closing.show = false">
        <template v-if="closing.pos">
            <p class="text-sm text-zinc-400">Close your <span class="font-semibold text-white">{{ closing.pos.coin }}USDT {{ closing.pos.side }} {{ closing.pos.leverage }}x</span> position at the market price?</p>
            <div class="mt-4 rounded-2xl bg-white/[0.03] p-4 text-center">
                <p class="text-xs text-zinc-500">Estimated PnL (before fee)</p>
                <p class="font-mono text-2xl font-bold" :class="pnlOf(closing.pos) >= 0 ? 'text-up' : 'text-down'">{{ pnlOf(closing.pos) >= 0 ? '+' : '' }}{{ formatMoney(pnlOf(closing.pos)) }} USDT</p>
            </div>
            <div class="mt-5 grid grid-cols-2 gap-2">
                <button class="btn-ghost" @click="closing.show = false">Cancel</button>
                <button class="btn bg-white text-ink-950 hover:bg-zinc-200" :disabled="closing.busy" @click="confirmClose"><i v-if="closing.busy" class="ri-loader-4-line animate-spin"></i> Close position</button>
            </div>
        </template>
    </Modal>

    <!-- TP / SL -->
    <Modal :show="tpsl.show" title="Take-profit / Stop-loss" @close="tpsl.show = false">
        <template v-if="tpsl.pos">
            <p class="mb-4 text-xs text-zinc-500">{{ tpsl.pos.coin }}USDT {{ tpsl.pos.side }} {{ tpsl.pos.leverage }}x · entry {{ formatPrice(tpsl.pos.entry) }} · liq {{ formatPrice(tpsl.pos.liquidation) }}</p>
            <div class="grid grid-cols-2 gap-3">
                <label class="block"><span class="mb-1.5 block text-sm text-up">Take-profit</span><input v-model="tpsl.tp" type="number" step="any" min="0" placeholder="Not set" class="field font-mono" /></label>
                <label class="block"><span class="mb-1.5 block text-sm text-down">Stop-loss</span><input v-model="tpsl.sl" type="number" step="any" min="0" placeholder="Not set" class="field font-mono" /></label>
            </div>
            <p class="mt-2 text-[11px] text-zinc-500">Leave a field empty to remove it. Triggers close the position at the market price.</p>
            <button class="btn-primary mt-5 w-full" :disabled="tpsl.busy" @click="saveTpSl"><i v-if="tpsl.busy" class="ri-loader-4-line animate-spin"></i> Save</button>
        </template>
    </Modal>

    <!-- Transfer -->
    <Modal :show="transfer.show" title="Transfer USDT" @close="transfer.show = false">
        <div class="flex items-center gap-2 rounded-2xl bg-white/[0.03] p-3">
            <div class="flex-1 text-center">
                <p class="text-[11px] text-zinc-500">From</p>
                <p class="font-semibold text-white">{{ transfer.direction === 'to_futures' ? 'Spot' : 'Futures' }}</p>
                <p class="font-mono text-xs text-zinc-400">{{ formatMoney(transfer.direction === 'to_futures' ? balances.spot : balances.futures) }}</p>
            </div>
            <button class="grid h-10 w-10 place-items-center rounded-xl bg-white/[0.06] text-lg text-brand-300 hover:bg-white/[0.1]" aria-label="Swap direction" @click="transfer.direction = transfer.direction === 'to_futures' ? 'to_spot' : 'to_futures'"><i class="ri-arrow-left-right-line"></i></button>
            <div class="flex-1 text-center">
                <p class="text-[11px] text-zinc-500">To</p>
                <p class="font-semibold text-white">{{ transfer.direction === 'to_futures' ? 'Futures' : 'Spot' }}</p>
                <p class="font-mono text-xs text-zinc-400">{{ formatMoney(transfer.direction === 'to_futures' ? balances.futures : balances.spot) }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
            <input v-model="transfer.amount" type="number" step="any" min="0" placeholder="0.00" class="w-full bg-transparent px-4 py-3 font-mono text-lg text-zinc-100 focus:outline-none" />
            <button type="button" class="mr-2 rounded-lg bg-brand-500/15 px-2.5 py-1 text-xs font-bold text-brand-300" @click="transfer.amount = String(Math.floor(transferMax * 1e6) / 1e6)">MAX</button>
            <span class="pr-4 text-sm text-zinc-500">USDT</span>
        </div>
        <p class="mt-1.5 text-[11px] text-zinc-500">Available {{ formatMoney(transferMax) }} USDT<template v-if="transfer.direction === 'to_spot' && balances.inMargin"> · {{ formatMoney(balances.inMargin) }} is locked in open positions</template></p>
        <button class="btn-primary mt-5 w-full" :disabled="transfer.busy || !Number(transfer.amount) || Number(transfer.amount) > transferMax" @click="doTransfer"><i v-if="transfer.busy" class="ri-loader-4-line animate-spin"></i> Confirm transfer</button>
        <a v-if="urls.deposit && !balances.spot" :href="urls.deposit" class="mt-3 block text-center text-xs text-brand-300">No USDT in spot? Deposit first →</a>
    </Modal>
</template>
