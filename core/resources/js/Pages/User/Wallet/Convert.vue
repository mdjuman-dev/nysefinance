<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import CoinIcon from '@/Components/CoinIcon.vue';
import Modal from '@/Components/UI/Modal.vue';
import { formatAmount, formatMoney } from '@/utils/format';
import { getJson } from '@/utils/http';

const props = defineProps({ coins: Array, urls: Object });

const pick = (sym, fallback) => props.coins.find((c) => c.symbol === sym) || fallback;
const from = ref(pick('USDT', props.coins[0])?.symbol || '');
const to = ref(pick('BTC', props.coins.find((c) => c.symbol !== from.value))?.symbol || '');
const amount = ref('');

const fromCoin = computed(() => props.coins.find((c) => c.symbol === from.value));
const toCoin = computed(() => props.coins.find((c) => c.symbol === to.value));

/* ---------- quote ---------- */
const rate = ref(null);
const quoting = ref(false);
const quoteError = ref('');
let qTimer;
let seq = 0;
async function fetchRate() {
    rate.value = null;
    quoteError.value = '';
    if (!from.value || !to.value || from.value === to.value) return;
    const id = ++seq;
    quoting.value = true;
    const res = await getJson(props.urls.rate, { from_coin: from.value, to_coin: to.value }).catch(() => null);
    if (id !== seq) return;
    quoting.value = false;
    if (res?.status === 'success') rate.value = Number(res.convert_rate);
    else quoteError.value = res?.message || 'Rate unavailable';
}
watch([from, to], () => {
    clearTimeout(qTimer);
    qTimer = setTimeout(fetchRate, 150);
}, { immediate: true });

const receive = computed(() => (rate.value && Number(amount.value) > 0 ? Number(amount.value) * rate.value : 0));
const insufficient = computed(() => Number(amount.value) > (fromCoin.value?.balance || 0));
const canConvert = computed(() => rate.value && Number(amount.value) > 0 && !insufficient.value);

function swap() {
    [from.value, to.value] = [to.value, from.value];
    amount.value = '';
}
const setPct = (p) => (amount.value = +((fromCoin.value?.balance || 0) * p).toFixed(8) || '');

/* ---------- coin picker ---------- */
const picker = ref(null); // 'from' | 'to'
const search = ref('');
const filtered = computed(() => {
    const q = search.value.trim().toLowerCase();
    return props.coins.filter((c) => !q || c.symbol.toLowerCase().includes(q) || (c.name || '').toLowerCase().includes(q));
});
function choose(sym) {
    if (picker.value === 'from') {
        if (sym === to.value) to.value = from.value;
        from.value = sym;
    } else {
        if (sym === from.value) from.value = to.value;
        to.value = sym;
    }
    picker.value = null;
    search.value = '';
}

/* ---------- confirm ---------- */
const confirming = ref(false);
const form = useForm({ from_coin: '', to_coin: '', fromAmount: '' });
function submit() {
    form.from_coin = from.value;
    form.to_coin = to.value;
    form.fromAmount = amount.value;
    form.post(props.urls.convert, {
        preserveScroll: true,
        onSuccess: () => {
            confirming.value = false;
            amount.value = '';
            fetchRate();
        },
        onError: () => (confirming.value = false),
    });
}
</script>

<template>
    <Head title="Convert" />

    <div class="mx-auto max-w-lg">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-white sm:text-2xl">Convert</h1>
                <p class="text-sm text-zinc-500">Instantly swap between your spot balances · zero fees</p>
            </div>
            <Link :href="urls.wallet" class="btn-ghost py-2 text-xs"><i class="ri-wallet-3-line"></i> Spot wallet</Link>
        </div>

        <div class="card p-4 sm:p-5">
            <!-- from -->
            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4">
                <div class="mb-3 flex items-center justify-between text-xs">
                    <span class="text-zinc-500">From</span>
                    <span class="text-zinc-500">Available <span class="font-mono text-zinc-300">{{ formatAmount(fromCoin?.balance || 0) }}</span></span>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" class="flex shrink-0 items-center gap-2 rounded-xl bg-white/[0.05] py-2 pr-3 pl-2 transition hover:bg-white/[0.09]" @click="picker = 'from'">
                        <CoinIcon :src="fromCoin?.image" :symbol="from" size="h-7 w-7" />
                        <span class="font-semibold text-white">{{ from || 'Select' }}</span>
                        <i class="ri-arrow-down-s-line text-zinc-500"></i>
                    </button>
                    <input v-model="amount" type="number" step="any" min="0" inputmode="decimal" placeholder="0.00" class="w-full min-w-0 bg-transparent text-right font-mono text-2xl font-semibold text-white placeholder:text-zinc-600 focus:outline-none" />
                </div>
                <div class="mt-3 flex items-center justify-between gap-2">
                    <div class="flex gap-1.5">
                        <button v-for="p in [0.25, 0.5, 0.75, 1]" :key="p" type="button" class="rounded-lg bg-white/[0.05] px-2 py-1 text-[11px] font-semibold text-zinc-400 transition hover:bg-brand-500/15 hover:text-brand-300" @click="setPct(p)">{{ p === 1 ? 'MAX' : p * 100 + '%' }}</button>
                    </div>
                    <span v-if="Number(amount) > 0 && fromCoin" class="text-xs text-zinc-500">≈ ${{ formatMoney(Number(amount) * fromCoin.usd) }}</span>
                </div>
                <p v-if="insufficient" class="mt-2 text-xs text-down">Insufficient {{ from }} balance</p>
            </div>

            <!-- swap -->
            <div class="relative z-10 -my-3 flex justify-center">
                <button type="button" class="grid h-10 w-10 place-items-center rounded-xl border-4 border-ink-900 bg-brand-500 text-lg text-ink-950 transition hover:rotate-180 hover:bg-brand-400" aria-label="Swap coins" @click="swap">
                    <i class="ri-arrow-up-down-line"></i>
                </button>
            </div>

            <!-- to -->
            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4">
                <div class="mb-3 flex items-center justify-between text-xs">
                    <span class="text-zinc-500">To</span>
                    <span class="text-zinc-500">Balance <span class="font-mono text-zinc-300">{{ formatAmount(toCoin?.balance || 0) }}</span></span>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" class="flex shrink-0 items-center gap-2 rounded-xl bg-white/[0.05] py-2 pr-3 pl-2 transition hover:bg-white/[0.09]" @click="picker = 'to'">
                        <CoinIcon :src="toCoin?.image" :symbol="to" size="h-7 w-7" />
                        <span class="font-semibold text-white">{{ to || 'Select' }}</span>
                        <i class="ri-arrow-down-s-line text-zinc-500"></i>
                    </button>
                    <p class="w-full min-w-0 truncate text-right font-mono text-2xl font-semibold" :class="receive ? 'text-white' : 'text-zinc-600'">{{ receive ? formatAmount(receive) : '0.00' }}</p>
                </div>
            </div>

            <!-- rate -->
            <div class="mt-4 flex items-center justify-between rounded-xl px-1 text-sm">
                <span class="text-zinc-500">Rate</span>
                <span v-if="quoting" class="h-4 w-36 animate-pulse rounded bg-white/[0.06]"></span>
                <span v-else-if="rate" class="font-mono text-zinc-200">1 {{ from }} ≈ {{ formatAmount(rate) }} {{ to }}</span>
                <span v-else class="text-xs text-down">{{ quoteError || '—' }}</span>
            </div>

            <button type="button" class="btn-primary mt-4 w-full py-3.5" :disabled="!canConvert" @click="confirming = true">
                {{ !amount ? 'Enter an amount' : insufficient ? 'Insufficient balance' : 'Preview conversion' }}
            </button>
        </div>
        <p class="mt-3 text-center text-xs text-zinc-500">Final rate is fetched again when you confirm.</p>
    </div>

    <!-- coin picker -->
    <Modal :show="!!picker" :title="picker === 'from' ? 'Convert from' : 'Convert to'" @close="picker = null">
        <div class="relative mb-3">
            <i class="ri-search-line absolute top-1/2 left-3 -translate-y-1/2 text-zinc-500"></i>
            <input v-model="search" class="field pl-9" placeholder="Search coin" autofocus />
        </div>
        <ul class="-mx-2 max-h-[55vh] overflow-y-auto">
            <li v-for="c in filtered" :key="c.symbol">
                <button type="button" class="flex w-full items-center gap-3 rounded-xl px-2 py-2.5 text-left transition hover:bg-white/[0.05]" :class="(picker === 'from' ? from : to) === c.symbol && 'bg-brand-500/10'" @click="choose(c.symbol)">
                    <CoinIcon :src="c.image" :symbol="c.symbol" size="h-8 w-8" />
                    <span class="min-w-0 flex-1">
                        <span class="block text-sm font-semibold text-white">{{ c.symbol }}</span>
                        <span class="block truncate text-xs text-zinc-500">{{ c.name }}</span>
                    </span>
                    <span class="font-mono text-sm" :class="c.balance > 0 ? 'text-zinc-200' : 'text-zinc-600'">{{ formatAmount(c.balance) }}</span>
                </button>
            </li>
            <li v-if="!filtered.length" class="py-8 text-center text-sm text-zinc-500">No coins found</li>
        </ul>
    </Modal>

    <!-- confirm -->
    <Modal :show="confirming" title="Confirm conversion" @close="confirming = false">
        <div class="space-y-4">
            <div class="flex items-center justify-between rounded-2xl bg-white/[0.03] p-4">
                <div class="text-center">
                    <CoinIcon :src="fromCoin?.image" :symbol="from" size="h-10 w-10 mx-auto" />
                    <p class="mt-2 font-mono font-semibold text-white">{{ formatAmount(amount) }}</p>
                    <p class="text-xs text-zinc-500">{{ from }}</p>
                </div>
                <i class="ri-arrow-right-line text-2xl text-brand-300"></i>
                <div class="text-center">
                    <CoinIcon :src="toCoin?.image" :symbol="to" size="h-10 w-10 mx-auto" />
                    <p class="mt-2 font-mono font-semibold text-up">≈ {{ formatAmount(receive) }}</p>
                    <p class="text-xs text-zinc-500">{{ to }}</p>
                </div>
            </div>
            <div class="space-y-1.5 text-sm">
                <div class="flex justify-between"><span class="text-zinc-500">Rate</span><span class="font-mono text-zinc-200">1 {{ from }} ≈ {{ formatAmount(rate) }} {{ to }}</span></div>
                <div class="flex justify-between"><span class="text-zinc-500">Fee</span><span class="font-mono text-up">0</span></div>
            </div>
            <p v-if="form.errors.fromAmount || form.errors.to_coin" class="text-xs text-down">{{ form.errors.fromAmount || form.errors.to_coin }}</p>
            <button type="button" class="btn-primary w-full py-3" :disabled="form.processing" @click="submit">
                <i :class="form.processing ? 'ri-loader-4-line animate-spin' : 'ri-check-line'"></i>
                {{ form.processing ? 'Converting…' : 'Convert now' }}
            </button>
        </div>
    </Modal>
</template>
