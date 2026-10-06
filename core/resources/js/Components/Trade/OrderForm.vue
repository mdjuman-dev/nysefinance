<script setup>
import { computed, reactive, ref, watch } from 'vue';
import { useToast } from '@/composables/useToast';
import { postJson, messageText } from '@/utils/http';
import { formatAmount, formatPrice } from '@/utils/format';

/**
 * Order form posting to user.order.save/{symbol} with the same fields as the
 * Blade buy/sell forms: order_side (1 buy / 2 sell), order_type (1 limit), rate, amount.
 * OrderController@save ignores the submitted rate and fills at the pair's current
 * market price, so the price here is shown read-only as that market price.
 */
const props = defineProps({
    pair: Object,
    balances: Object,
    urls: Object,
    side: { type: String, default: 'buy' },
    marketPrice: Number,
    signedIn: Boolean,
    fixedSide: Boolean,
});
const emit = defineEmits(['placed', 'update:side']);
const toast = useToast();

const side = computed({ get: () => props.side, set: (v) => emit('update:side', v) });
const isBuy = computed(() => side.value === 'buy');

const form = reactive({ amount: '', total: '' });
const busy = ref(false);
const pct = ref(0);

const rate = () => props.marketPrice || props.pair.price || 0;
watch(() => props.marketPrice, () => form.amount && recalcFromAmount());
function recalcFromAmount() {
    const a = Number(form.amount);
    form.total = a ? String(+(a * rate()).toFixed(8)) : '';
}
function recalcFromTotal() {
    const t = Number(form.total);
    form.amount = t && rate() ? String(+(t / rate()).toFixed(8)) : '';
}

const available = computed(() => (isBuy.value ? props.balances.market : props.balances.coin));
const availableSymbol = computed(() => (isBuy.value ? props.pair.market : props.pair.coin.symbol));
const feePct = computed(() => (isBuy.value ? props.pair.buyFee : props.pair.sellFee));
const fee = computed(() => (Number(form.total) || 0) * feePct.value / 100);

// Percent of balance: buy spends quote currency (total), sell spends coin (amount) — as before.
function setPercent(p) {
    pct.value = p;
    if (!props.signedIn || available.value <= 0) return;
    const value = (available.value / 100) * p;
    if (isBuy.value) {
        form.total = String(+value.toFixed(8));
        recalcFromTotal();
    } else {
        form.amount = String(+value.toFixed(8));
        recalcFromAmount();
    }
}
watch(side, () => {
    form.amount = '';
    form.total = '';
    pct.value = 0;
});

async function submit() {
    if (!props.signedIn) return (window.location.href = props.urls.login);
    busy.value = true;
    const res = await postJson(props.urls.save, {
        order_side: isBuy.value ? 1 : 2,
        order_type: 1,
        rate: rate(),
        amount: form.amount,
    }, { asForm: true });
    busy.value = false;
    if (res.success) {
        toast.success(messageText(res.message) || 'Order placed');
        form.amount = '';
        form.total = '';
        pct.value = 0;
        emit('placed', res.data || {});
    } else {
        toast.error(messageText(res.message) || 'Could not place order');
    }
}
</script>

<template>
    <form class="flex flex-col gap-3" @submit.prevent="submit">
        <div v-if="!fixedSide" class="grid grid-cols-2 gap-1 rounded-xl bg-white/[0.04] p-1">
            <button type="button" class="rounded-lg py-2 text-sm font-semibold transition" :class="isBuy ? 'bg-up text-ink-950' : 'text-zinc-400 hover:text-white'" @click="side = 'buy'">Buy</button>
            <button type="button" class="rounded-lg py-2 text-sm font-semibold transition" :class="!isBuy ? 'bg-down text-white' : 'text-zinc-400 hover:text-white'" @click="side = 'sell'">Sell</button>
        </div>

        <div class="flex items-center justify-between text-xs">
            <span class="text-zinc-500"><span v-if="fixedSide" class="mr-1 font-semibold" :class="isBuy ? 'text-up' : 'text-down'">{{ isBuy ? 'Buy' : 'Sell' }}</span>Market price</span>
            <span class="text-zinc-400">
                Avbl <span class="font-mono text-zinc-200">{{ signedIn ? formatAmount(available) : '—' }}</span> {{ availableSymbol }}
                <a v-if="urls.deposit" :href="urls.deposit" class="ml-1 text-brand-300 hover:text-brand-200" aria-label="Deposit"><i class="ri-add-circle-line"></i></a>
            </span>
        </div>

        <div class="flex items-center rounded-xl border border-white/[0.06] bg-white/[0.02]" title="Orders fill at the current market price">
            <span class="w-16 shrink-0 pl-3 text-xs text-zinc-500">Price</span>
            <span class="w-full min-w-0 px-2 py-2.5 text-right font-mono text-sm text-zinc-300">≈ {{ formatPrice(rate()) }}</span>
            <span class="w-14 shrink-0 pr-3 text-right text-xs text-zinc-500">{{ pair.market }}</span>
        </div>
        <label class="flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
            <span class="w-16 shrink-0 pl-3 text-xs text-zinc-500">Amount</span>
            <input v-model="form.amount" type="number" step="any" min="0" name="amount" class="w-full min-w-0 bg-transparent px-2 py-2.5 text-right font-mono text-sm text-zinc-100 focus:outline-none" required @input="recalcFromAmount" />
            <span class="w-14 shrink-0 pr-3 text-right text-xs text-zinc-500">{{ pair.coin.symbol }}</span>
        </label>

        <div class="grid grid-cols-5 gap-1.5">
            <button v-for="p in [0, 25, 50, 75, 100]" :key="p" type="button" class="rounded-lg py-1.5 text-[11px] font-semibold transition" :class="pct === p && p ? (isBuy ? 'bg-up/20 text-up' : 'bg-down/20 text-down') : 'bg-white/[0.04] text-zinc-400 hover:bg-white/[0.08]'" @click="setPercent(p)">{{ p }}%</button>
        </div>

        <label class="flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
            <span class="w-16 shrink-0 pl-3 text-xs text-zinc-500">Total</span>
            <input v-model="form.total" type="number" step="any" min="0" class="w-full min-w-0 bg-transparent px-2 py-2.5 text-right font-mono text-sm text-zinc-100 focus:outline-none" placeholder="0.00" @input="recalcFromTotal" />
            <span class="w-14 shrink-0 pr-3 text-right text-xs text-zinc-500">{{ pair.market }}</span>
        </label>
        <p class="-mt-1 text-right text-[11px] text-zinc-500">Fee {{ feePct }}%<template v-if="fee"> · {{ formatAmount(fee) }} {{ pair.market }}</template></p>

        <button v-if="signedIn" type="submit" class="btn w-full py-3 text-sm" :class="isBuy ? 'bg-up text-ink-950 hover:bg-up/90' : 'bg-down text-white hover:bg-down/90'" :disabled="busy || !form.amount">
            <i v-if="busy" class="ri-loader-4-line animate-spin"></i> {{ isBuy ? 'Buy' : 'Sell' }} {{ pair.coin.symbol }}
        </button>
        <div v-else class="grid grid-cols-2 gap-2">
            <a :href="urls.login" class="btn-ghost">Log in</a>
            <a :href="urls.register" class="btn-primary">Sign up</a>
        </div>
    </form>
</template>
