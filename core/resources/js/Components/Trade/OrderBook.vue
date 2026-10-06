<script setup>
import { computed, ref } from 'vue';
import { binanceSymbol, useBinanceStream } from '@/composables/useBinanceStream';
import { formatAmount, formatPrice } from '@/utils/format';

// Live depth from Binance (the Blade order book rendered the same stream).
const props = defineProps({ pair: Object, lastPrice: Number, rows: { type: Number, default: 10 } });
const emit = defineEmits(['pick']);

const bids = ref([]);
const asks = ref([]);
const view = ref('all'); // all | buy | sell

useBinanceStream(`${binanceSymbol(props.pair.symbol)}@depth20@1000ms`, (d) => {
    const map = (l) => l.map(([p, q]) => ({ price: +p, qty: +q, total: +p * +q })).filter((r) => r.qty > 0);
    if (d.bids) bids.value = map(d.bids);
    if (d.asks) asks.value = map(d.asks);
});

const count = computed(() => (view.value === 'all' ? props.rows : props.rows * 2));
const shownAsks = computed(() => asks.value.slice(0, count.value).reverse());
const shownBids = computed(() => bids.value.slice(0, count.value));
const maxQty = computed(() => Math.max(1e-12, ...shownAsks.value.map((r) => r.qty), ...shownBids.value.map((r) => r.qty)));
const spread = computed(() => (asks.value[0] && bids.value[0] ? asks.value[0].price - bids.value[0].price : null));
const loading = computed(() => !bids.value.length && !asks.value.length);
</script>

<template>
    <div class="flex h-full flex-col text-xs">
        <div class="flex items-center justify-between px-3 py-2.5">
            <h3 class="text-sm font-semibold text-white">Order book</h3>
            <div class="flex gap-1">
                <button v-for="v in ['all', 'buy', 'sell']" :key="v" class="grid h-7 w-7 place-items-center rounded-md transition" :class="view === v ? 'bg-white/[0.08]' : 'opacity-50 hover:opacity-100'" :aria-label="`Show ${v}`" @click="view = v">
                    <span class="flex h-3.5 w-3.5 flex-col gap-px">
                        <span class="flex-1 rounded-[1px]" :class="v === 'buy' ? 'bg-up' : 'bg-down'"></span>
                        <span class="flex-1 rounded-[1px]" :class="v === 'sell' ? 'bg-down' : 'bg-up'"></span>
                    </span>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-3 px-3 pb-1.5 text-[10px] tracking-wider text-zinc-500 uppercase">
            <span>Price ({{ pair.market }})</span><span class="text-right">Amount</span><span class="text-right">Total</span>
        </div>

        <div v-if="loading" class="flex-1 space-y-1 px-3 py-2">
            <div v-for="n in 12" :key="n" class="h-5 animate-pulse rounded bg-white/[0.03]"></div>
        </div>

        <template v-else>
            <div v-if="view !== 'buy'" class="flex flex-1 flex-col justify-end overflow-hidden">
                <button v-for="r in shownAsks" :key="'a' + r.price" class="relative grid grid-cols-3 px-3 py-[3px] font-mono hover:bg-white/[0.03]" @click="emit('pick', r.price)">
                    <span class="absolute inset-y-0 right-0 bg-down/10" :style="{ width: (r.qty / maxQty) * 100 + '%' }"></span>
                    <span class="relative text-left text-down">{{ formatPrice(r.price) }}</span>
                    <span class="relative text-right text-zinc-300">{{ formatAmount(r.qty) }}</span>
                    <span class="relative text-right text-zinc-500">{{ formatAmount(r.total) }}</span>
                </button>
            </div>

            <div class="flex items-center justify-between border-y border-white/[0.05] px-3 py-2">
                <span class="font-mono text-base font-bold" :class="lastPrice >= (bids[0]?.price || 0) ? 'text-up' : 'text-down'">{{ formatPrice(lastPrice) }}</span>
                <span v-if="spread != null" class="text-[10px] text-zinc-500">Spread {{ formatPrice(spread) }}</span>
            </div>

            <div v-if="view !== 'sell'" class="flex-1 overflow-hidden">
                <button v-for="r in shownBids" :key="'b' + r.price" class="relative grid w-full grid-cols-3 px-3 py-[3px] font-mono hover:bg-white/[0.03]" @click="emit('pick', r.price)">
                    <span class="absolute inset-y-0 right-0 bg-up/10" :style="{ width: (r.qty / maxQty) * 100 + '%' }"></span>
                    <span class="relative text-left text-up">{{ formatPrice(r.price) }}</span>
                    <span class="relative text-right text-zinc-300">{{ formatAmount(r.qty) }}</span>
                    <span class="relative text-right text-zinc-500">{{ formatAmount(r.total) }}</span>
                </button>
            </div>
        </template>
    </div>
</template>
