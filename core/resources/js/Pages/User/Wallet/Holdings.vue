<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import SegmentTabs from '@/Components/UI/SegmentTabs.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import CoinIcon from '@/Components/CoinIcon.vue';
import { getJson } from '@/utils/http';
import { useToast } from '@/composables/useToast';
import { formatMoney } from '@/utils/format';

// Stock wallet and Bond wallet.
const props = defineProps({ kind: String, tabs: Array, frozen: Boolean, available: Number, stats: Object, holdings: Object, transferCharge: Number, urls: Object });
const toast = useToast();
const noun = computed(() => (props.kind === 'bond' ? 'Bond' : 'Stock'));

const hidden = ref(false);
try { hidden.value = localStorage.getItem('viewBalance') === 'yes'; } catch {}
function toggleHidden() {
    hidden.value = !hidden.value;
    try { hidden.value ? localStorage.setItem('viewBalance', 'yes') : localStorage.removeItem('viewBalance'); } catch {}
}
const mask = (t) => (hidden.value ? '••••••' : t);

const cards = computed(() => [
    { label: 'Total earned', value: props.stats.earned, icon: 'ri-coins-line', cls: 'text-up bg-up/10' },
    { label: 'Total assets', value: props.stats.buy, icon: 'ri-briefcase-4-line', cls: 'text-sky-300 bg-sky-400/10' },
    { label: 'Total sold', value: props.stats.sell, icon: 'ri-hand-coin-line', cls: 'text-down bg-down/10' },
    { label: 'Mutual fund', value: props.stats.mutual, icon: 'ri-safe-2-line', cls: 'text-violet-300 bg-violet-400/10' },
    { label: 'Live market', value: props.stats.live, icon: 'ri-pulse-line', cls: 'text-amber-300 bg-amber-400/10' },
]);

/* Live prices for live-market holdings (existing endpoint, refreshed every 20 min like before) */
const live = reactive({});
let timer;
async function loadPrices() {
    const codes = [...new Set(props.holdings.data.filter((h) => !h.fixed && h.stackPrice > 0 && h.code && h.status === 'buy').map((h) => h.code))];
    for (const code of codes) {
        try {
            const res = await getJson(props.urls.livePrice, { code });
            live[code] = res.status === 'success' && res.amount > 0 ? Number(res.amount) : null;
        } catch { live[code] = null; }
    }
}
onMounted(() => { loadPrices(); timer = setInterval(loadPrices, 20 * 60 * 1000); });
onBeforeUnmount(() => clearInterval(timer));

/* Transfer to spot USDT (stock wallet only; same form fields as before) */
const transfer = reactive({ show: false, amount: '', busy: false });
const charge = computed(() => ((Number(transfer.amount) || 0) * props.transferCharge) / 100);
const receive = computed(() => (Number(transfer.amount) || 0) - charge.value);
const canTransfer = computed(() => Number(transfer.amount) > 0 && Number(transfer.amount) <= props.available && receive.value > 0);
function openTransfer() {
    if (props.frozen) return toast.error('Your all transaction has been frozen. Wait until your report solved');
    transfer.show = true;
}
function doTransfer() {
    transfer.busy = true;
    router.post(props.urls.transfer, { amount: transfer.amount }, {
        preserveScroll: true,
        onFinish: () => { transfer.busy = false; transfer.show = false; transfer.amount = ''; },
    });
}
</script>

<template>
    <Head :title="`${noun} wallet`" />
    <div class="mb-4"><SegmentTabs :tabs="tabs" /></div>

    <!-- Balance -->
    <section class="relative mb-5 overflow-hidden rounded-3xl border border-white/[0.07] bg-gradient-to-br from-ink-800 via-ink-900 to-ink-900 p-5 sm:p-7">
        <div class="pointer-events-none absolute -top-24 -right-16 h-64 w-64 rounded-full bg-brand-500/20 blur-3xl"></div>
        <div class="relative flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="flex items-center gap-2 text-sm text-zinc-400">
                    {{ noun }} wallet · available
                    <button class="grid h-7 w-7 place-items-center rounded-lg text-zinc-500 hover:bg-white/[0.06] hover:text-white" :aria-label="hidden ? 'Show balance' : 'Hide balance'" @click="toggleHidden"><i :class="hidden ? 'ri-eye-off-line' : 'ri-eye-line'"></i></button>
                </p>
                <p class="mt-2 font-mono text-[2.1rem] leading-none font-bold text-white sm:text-5xl"><span class="text-zinc-500">$</span>{{ mask(formatMoney(available)) }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button v-if="urls.transfer" class="btn-primary" :disabled="available <= 0" @click="openTransfer"><i class="ri-arrow-left-right-line"></i> Transfer to spot</button>
                <a :href="urls.my" class="btn-ghost"><i class="ri-briefcase-4-line"></i> My {{ noun.toLowerCase() }}s</a>
                <a :href="urls.history" class="btn-ghost"><i class="ri-history-line"></i> History</a>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
        <div v-for="c in cards" :key="c.label" class="card p-4">
            <span class="grid h-9 w-9 place-items-center rounded-lg text-lg" :class="c.cls"><i :class="c.icon"></i></span>
            <p class="mt-3 font-mono text-lg font-bold text-white">${{ mask(formatMoney(c.value)) }}</p>
            <p class="text-xs text-zinc-500">{{ c.label }}</p>
        </div>
    </div>

    <!-- Holdings -->
    <section class="card overflow-hidden">
        <div class="flex items-center justify-between border-b border-white/[0.05] p-4 sm:p-5">
            <h2 class="font-semibold text-white">My {{ noun.toLowerCase() }}s</h2>
            <span class="text-xs text-zinc-500">{{ holdings.meta.total }} total</span>
        </div>
        <EmptyState v-if="!holdings.data.length" :icon="kind === 'bond' ? 'ri-bank-line' : 'ri-stock-line'" :title="`No ${noun.toLowerCase()}s yet`" />
        <ul v-else class="divide-y divide-white/[0.04]">
            <li v-for="h in holdings.data" :key="h.id" class="flex items-center gap-3 px-4 py-3.5 sm:px-5">
                <CoinIcon :src="h.image" :symbol="h.code || h.name" size="h-10 w-10" />
                <div class="min-w-0 flex-1">
                    <p class="truncate font-semibold text-white">{{ h.name }}</p>
                    <div class="mt-0.5 flex flex-wrap items-center gap-1.5">
                        <Badge :tone="h.fixed ? 'violet' : 'info'">{{ h.fixed ? 'Mutual fund' : 'Live market' }}</Badge>
                        <Badge :tone="h.status === 'sell' ? 'danger' : 'success'" class="capitalize">{{ h.status }}</Badge>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-mono font-semibold text-white">${{ mask(formatMoney(h.invest)) }}</p>
                    <p v-if="h.fixed && h.interest" class="font-mono text-[11px] text-up">+{{ formatMoney(h.interest) }} interest</p>
                    <p v-else-if="!h.fixed && h.code && h.code in live" class="font-mono text-[11px]" :class="live[h.code] == null ? 'text-zinc-500' : live[h.code] > h.stackPrice ? 'text-up' : 'text-down'">
                        {{ live[h.code] == null ? '—' : `Market $${formatMoney(live[h.code])}` }}
                    </p>
                </div>
            </li>
        </ul>
        <Pagination :meta="holdings.meta" />
    </section>

    <Modal :show="transfer.show" title="Transfer to spot wallet" @close="transfer.show = false">
        <p class="text-sm text-zinc-400">Move funds from your {{ noun.toLowerCase() }} wallet to your USDT spot wallet.</p>
        <div class="mt-4 flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
            <span class="pl-4 text-zinc-500">$</span>
            <input v-model="transfer.amount" type="number" step="any" min="0" placeholder="0.00" class="w-full bg-transparent px-2 py-3 font-mono text-lg text-zinc-100 focus:outline-none" />
            <button type="button" class="mr-2 rounded-lg bg-brand-500/15 px-2.5 py-1 text-xs font-bold text-brand-300" @click="transfer.amount = String(available)">MAX</button>
        </div>
        <p class="mt-1.5 text-[11px] text-zinc-500">Available ${{ formatMoney(available) }}</p>
        <dl class="mt-4 space-y-1.5 rounded-2xl bg-white/[0.03] p-4 text-sm">
            <div class="flex justify-between"><dt class="text-zinc-500">Charge ({{ transferCharge }}%)</dt><dd class="font-mono text-down">{{ formatMoney(charge) }} USD</dd></div>
            <div class="flex justify-between border-t border-white/[0.06] pt-1.5"><dt class="text-zinc-300">You'll get</dt><dd class="font-mono font-semibold text-white">{{ formatMoney(Math.max(receive, 0)) }} USDT</dd></div>
        </dl>
        <button class="btn-primary mt-5 w-full" :disabled="transfer.busy || !canTransfer" @click="doTransfer"><i v-if="transfer.busy" class="ri-loader-4-line animate-spin"></i> Confirm transfer</button>
    </Modal>
</template>
