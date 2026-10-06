<script setup>
import { computed, reactive, ref, watch } from 'vue';
import { useToast } from '@/composables/useToast';
import { postJson, messageText } from '@/utils/http';
import { formatAmount, formatMoney, formatPrice } from '@/utils/format';

/**
 * Market order with leverage + optional TP/SL → POST user.futures.open.
 * The preview mirrors FuturesEngine (the server recomputes everything at its own mark price).
 */
const props = defineProps({
    pair: Object,
    price: Number,
    balances: Object,
    urls: Object,
    signedIn: Boolean,
    side: { type: String, default: 'long' },
});
const emit = defineEmits(['opened', 'transfer', 'update:side']);
const toast = useToast();

const side = computed({ get: () => props.side, set: (v) => emit('update:side', v) });
const isLong = computed(() => side.value === 'long');

// Leverage is remembered per pair.
const levKey = `futLev:${props.pair.symbol}`;
let savedLev = 0;
try { savedLev = Number(localStorage.getItem(levKey)); } catch {}
const leverage = ref(Math.min(Math.max(savedLev || Math.min(10, props.pair.maxLeverage), 1), props.pair.maxLeverage));
watch(leverage, (v) => { try { localStorage.setItem(levKey, String(v)); } catch {} });
const levPresets = computed(() => [1, 2, 5, 10, 20, 50, 100, 125].filter((x) => x <= props.pair.maxLeverage));

const form = reactive({ margin: '', tp: '', sl: '' });
const useTpSl = ref(false);
const busy = ref(false);
const pct = ref(0);

const available = computed(() => props.balances?.futures ?? 0);
const feeRate = computed(() => props.pair.feePercent / 100);
function setPercent(p) {
    pct.value = p;
    // leave room for the opening fee: margin + margin×lev×fee ≤ balance
    const max = available.value / (1 + leverage.value * feeRate.value);
    form.margin = p ? String(Math.floor(((max * p) / 100) * 100) / 100) : '';
}
watch(leverage, () => pct.value && setPercent(pct.value));

const margin = computed(() => Number(form.margin) || 0);
const notional = computed(() => margin.value * leverage.value);
const size = computed(() => (props.price ? notional.value / props.price : 0));
const fee = computed(() => notional.value * feeRate.value);
const liq = computed(() => {
    const mm = props.pair.mmPercent / 100;
    const p = props.price || 0;
    return Math.max(isLong.value ? p * (1 - 1 / leverage.value + mm) : p * (1 + 1 / leverage.value - mm), 0);
});
const liqDistance = computed(() => (props.price ? Math.abs((liq.value - props.price) / props.price) * 100 : 0));
const tpPnl = computed(() => (Number(form.tp) ? (isLong.value ? Number(form.tp) - props.price : props.price - Number(form.tp)) * size.value : null));
const slPnl = computed(() => (Number(form.sl) ? (isLong.value ? Number(form.sl) - props.price : props.price - Number(form.sl)) * size.value : null));
const insufficient = computed(() => margin.value + fee.value > available.value + 1e-9);

async function submit() {
    if (!props.signedIn) return (window.location.href = props.urls.login);
    if (margin.value < props.pair.minMargin) return toast.error(`Minimum margin is ${props.pair.minMargin} USDT`);
    busy.value = true;
    const res = await postJson(props.urls.open, {
        pair: props.pair.symbol,
        side: side.value,
        margin: form.margin,
        leverage: leverage.value,
        take_profit: useTpSl.value ? form.tp || null : null,
        stop_loss: useTpSl.value ? form.sl || null : null,
    });
    busy.value = false;
    if (res.success) {
        toast.success(res.message);
        Object.assign(form, { margin: '', tp: '', sl: '' });
        pct.value = 0;
        emit('opened', res.balances);
    } else {
        toast.error(messageText(res.message) || 'Could not open the position');
    }
}
</script>

<template>
    <form class="flex flex-col gap-3" @submit.prevent="submit">
        <div class="grid grid-cols-2 gap-1 rounded-xl bg-white/[0.04] p-1">
            <button type="button" class="rounded-lg py-2 text-sm font-semibold transition" :class="isLong ? 'bg-up text-ink-950' : 'text-zinc-400 hover:text-white'" @click="side = 'long'">Long</button>
            <button type="button" class="rounded-lg py-2 text-sm font-semibold transition" :class="!isLong ? 'bg-down text-white' : 'text-zinc-400 hover:text-white'" @click="side = 'short'">Short</button>
        </div>

        <!-- Account -->
        <div class="flex items-center justify-between rounded-xl bg-white/[0.03] px-3 py-2 text-xs">
            <span class="text-zinc-500">Futures balance</span>
            <span class="flex items-center gap-2">
                <span class="font-mono text-zinc-100">{{ signedIn ? formatMoney(available) : '—' }} USDT</span>
                <button v-if="signedIn" type="button" class="rounded-md bg-brand-500/15 px-2 py-0.5 font-semibold text-brand-300 hover:bg-brand-500/25" @click="emit('transfer')"><i class="ri-arrow-left-right-line"></i> Transfer</button>
            </span>
        </div>

        <!-- Leverage -->
        <div>
            <div class="mb-1.5 flex items-center justify-between text-xs">
                <span class="text-zinc-400">Leverage</span>
                <span class="rounded-md bg-amber-400/15 px-2 py-0.5 font-mono text-sm font-bold text-amber-300">{{ leverage }}x</span>
            </div>
            <input v-model.number="leverage" type="range" min="1" :max="pair.maxLeverage" step="1" class="w-full accent-amber-400" aria-label="Leverage" />
            <div class="mt-1.5 flex flex-wrap gap-1">
                <button v-for="l in levPresets" :key="l" type="button" class="rounded-md px-2 py-1 font-mono text-[11px] font-semibold transition" :class="leverage === l ? 'bg-amber-400/20 text-amber-300' : 'bg-white/[0.04] text-zinc-400 hover:bg-white/[0.08]'" @click="leverage = l">{{ l }}x</button>
                <span class="ml-auto self-center text-[10px] text-zinc-500">max {{ pair.maxLeverage }}x</span>
            </div>
        </div>

        <!-- Margin -->
        <label class="flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
            <span class="w-16 shrink-0 pl-3 text-xs text-zinc-500">Margin</span>
            <input v-model="form.margin" type="number" step="any" min="0" inputmode="decimal" placeholder="0.00" class="w-full min-w-0 bg-transparent px-2 py-2.5 text-right font-mono text-sm text-zinc-100 focus:outline-none" required @input="pct = 0" />
            <span class="w-14 shrink-0 pr-3 text-right text-xs text-zinc-500">USDT</span>
        </label>
        <div class="grid grid-cols-4 gap-1.5">
            <button v-for="p in [25, 50, 75, 100]" :key="p" type="button" class="rounded-lg py-1.5 text-[11px] font-semibold transition" :class="pct === p ? (isLong ? 'bg-up/20 text-up' : 'bg-down/20 text-down') : 'bg-white/[0.04] text-zinc-400 hover:bg-white/[0.08]'" @click="setPercent(p)">{{ p }}%</button>
        </div>

        <!-- TP / SL -->
        <label class="flex cursor-pointer items-center gap-2 text-xs text-zinc-400">
            <input v-model="useTpSl" type="checkbox" class="h-4 w-4 rounded accent-brand-500" /> Take-profit / Stop-loss
        </label>
        <div v-if="useTpSl" class="grid grid-cols-2 gap-2">
            <div>
                <input v-model="form.tp" type="number" step="any" min="0" placeholder="TP price" class="w-full rounded-xl border border-white/10 bg-ink-850 px-3 py-2.5 font-mono text-sm text-zinc-100 focus:border-up/60 focus:outline-none" />
                <p v-if="tpPnl != null" class="mt-1 font-mono text-[10px]" :class="tpPnl >= 0 ? 'text-up' : 'text-down'">≈ {{ tpPnl >= 0 ? '+' : '' }}{{ formatMoney(tpPnl) }} USDT</p>
            </div>
            <div>
                <input v-model="form.sl" type="number" step="any" min="0" placeholder="SL price" class="w-full rounded-xl border border-white/10 bg-ink-850 px-3 py-2.5 font-mono text-sm text-zinc-100 focus:border-down/60 focus:outline-none" />
                <p v-if="slPnl != null" class="mt-1 font-mono text-[10px]" :class="slPnl >= 0 ? 'text-up' : 'text-down'">≈ {{ slPnl >= 0 ? '+' : '' }}{{ formatMoney(slPnl) }} USDT</p>
            </div>
        </div>

        <!-- Preview -->
        <dl class="space-y-1.5 rounded-xl bg-white/[0.02] p-3 text-xs">
            <div class="flex justify-between"><dt class="text-zinc-500">Position size</dt><dd class="font-mono text-zinc-200">{{ formatAmount(size) }} {{ pair.coin.symbol }} <span class="text-zinc-500">· ${{ formatMoney(notional) }}</span></dd></div>
            <div class="flex justify-between"><dt class="text-zinc-500">Entry ≈</dt><dd class="font-mono text-zinc-200">{{ formatPrice(price) }}</dd></div>
            <div class="flex justify-between"><dt class="text-zinc-500">Liq. price ≈</dt><dd class="font-mono" :class="liqDistance < 5 ? 'text-down' : 'text-amber-300'">{{ margin ? formatPrice(liq) : '—' }}<span v-if="margin" class="text-zinc-500"> ({{ liqDistance.toFixed(1) }}%)</span></dd></div>
            <div class="flex justify-between"><dt class="text-zinc-500">Fee ({{ pair.feePercent }}%)</dt><dd class="font-mono text-zinc-200">{{ formatAmount(fee) }} USDT</dd></div>
            <div class="flex justify-between border-t border-white/[0.05] pt-1.5"><dt class="text-zinc-400">Cost</dt><dd class="font-mono font-semibold" :class="insufficient && margin ? 'text-down' : 'text-white'">{{ formatMoney(margin + fee) }} USDT</dd></div>
        </dl>

        <template v-if="signedIn">
            <button type="submit" class="btn w-full py-3 text-sm" :class="isLong ? 'bg-up text-ink-950 hover:bg-up/90' : 'bg-down text-white hover:bg-down/90'" :disabled="busy || !margin || insufficient">
                <i v-if="busy" class="ri-loader-4-line animate-spin"></i>
                {{ insufficient && margin ? 'Insufficient balance' : `${isLong ? 'Open Long' : 'Open Short'} ${leverage}x` }}
            </button>
            <button v-if="!available" type="button" class="-mt-1 text-xs font-medium text-brand-300 hover:text-brand-200" @click="emit('transfer')">Transfer USDT from spot to start trading →</button>
        </template>
        <div v-else class="grid grid-cols-2 gap-2">
            <a :href="urls.login" class="btn-ghost">Log in</a>
            <a :href="urls.register" class="btn-primary">Sign up</a>
        </div>
        <p class="text-[10px] leading-4 text-zinc-500">Leveraged trading is high risk. If the price reaches the liquidation price, the whole margin of the position is lost.</p>
    </form>
</template>
