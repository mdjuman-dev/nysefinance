<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import SegmentTabs from '@/Components/UI/SegmentTabs.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import CoinIcon from '@/Components/CoinIcon.vue';
import { getJson, postJson } from '@/utils/http';
import { formatMoney } from '@/utils/format';
import { formatDate } from '@/utils/display';
import { useToast } from '@/composables/useToast';

const props = defineProps({ kind: { type: String, default: 'stock' }, totalInvest: Number, holdings: Array, sold: Array, urls: Object });
// Shared by My stocks and My bonds.
const noun = computed(() => (props.kind === 'bond' ? 'bond' : 'stock'));
const toast = useToast();

const tab = ref('holding');
const tabs = computed(() => [
    { label: 'Holding', value: 'holding', active: tab.value === 'holding', count: props.holdings.length },
    { label: 'Sold', value: 'sold', active: tab.value === 'sold', count: props.sold.length },
]);

/* Live market prices (existing endpoint, refreshed every 20 minutes like before) */
const live = reactive({});
let timer;
async function loadPrices() {
    const codes = [...new Set(props.holdings.filter((h) => !h.fixed && h.stackPrice > 0 && h.code).map((h) => h.code))];
    for (const code of codes) {
        try {
            const res = await getJson(props.urls.livePrice, { code });
            live[code] = res.status === 'success' && res.amount > 0 ? Number(res.amount) : null;
        } catch {
            live[code] = null;
        }
    }
}
onMounted(() => {
    loadPrices();
    timer = setInterval(loadPrices, 20 * 60 * 1000);
});
onBeforeUnmount(() => clearInterval(timer));

const totalInterest = computed(() => props.holdings.reduce((s, h) => s + (h.interest || 0), 0));

/* Certificate / QR */
const qr = reactive({ show: false, svg: null, url: null, name: '' });
async function showCertificate(item) {
    Object.assign(qr, { show: true, svg: null, url: item.certificate, name: item.name });
    const res = await postJson(props.urls.qr, { id: item.id });
    if (res.status === 'success') qr.svg = `data:image/svg+xml;base64,${res.data}`;
    else toast.error(res.message || 'Could not load certificate');
}

/* Sell */
const sell = reactive({ show: false, item: null, busy: false });
function confirmSell() {
    sell.busy = true;
    router.post(sell.item.sellUrl, {}, { preserveScroll: true, onFinish: () => Object.assign(sell, { show: false, busy: false }) });
}

/* Reactive (GET with holding period, as before) */
const reactiveForm = reactive({ show: false, item: null, period: 'month' });
const periods = [
    { value: 'month', label: '1 month' },
    { value: 'three_month', label: '3 months' },
    { value: 'half_year', label: '6 months' },
    { value: 'year', label: '1 year' },
];
function confirmReactive() {
    router.get(reactiveForm.item.reactiveUrl, { invest_time: reactiveForm.period }, { onFinish: () => (reactiveForm.show = false) });
}
</script>

<template>
    <Head :title="`My ${noun}s`" />
    <PageHeader :title="`My ${noun}s`" :subtitle="`Your ${noun} holdings and certificates`" :icon="kind === 'bond' ? 'ri-bank-line' : 'ri-briefcase-4-line'">
        <template #actions>
            <a :href="urls.history" class="btn-ghost"><i class="ri-file-history-line"></i> History</a>
            <a :href="urls.market" class="btn-primary"><i class="ri-add-line"></i> Buy {{ noun }}</a>
        </template>
    </PageHeader>

    <div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-3 lg:gap-5">
        <div class="relative col-span-2 overflow-hidden rounded-3xl border border-white/[0.07] bg-gradient-to-br from-ink-800 to-ink-900 p-5 lg:col-span-1">
            <div class="pointer-events-none absolute -top-16 -right-10 h-40 w-40 rounded-full bg-brand-500/20 blur-3xl"></div>
            <p class="text-sm text-zinc-400">Total invested</p>
            <p class="mt-1 font-mono text-3xl font-bold text-white">${{ formatMoney(totalInvest) }}</p>
        </div>
        <div class="card p-4 sm:p-5">
            <p class="text-xs text-zinc-500 sm:text-sm">Holdings</p>
            <p class="mt-2 font-mono text-2xl font-bold text-white">{{ holdings.length }}</p>
        </div>
        <div class="card p-4 sm:p-5">
            <p class="text-xs text-zinc-500 sm:text-sm">Fund interest earned</p>
            <p class="mt-2 font-mono text-2xl font-bold text-up">${{ formatMoney(totalInterest) }}</p>
        </div>
    </div>

    <div class="mb-4"><SegmentTabs :tabs="tabs" @select="tab = $event" /></div>

    <!-- Holdings -->
    <template v-if="tab === 'holding'">
        <EmptyState v-if="!holdings.length" icon="ri-briefcase-4-line" :title="`No ${noun}s yet`" :text="`Buy your first ${noun} from the market.`" class="card">
            <a :href="urls.market" class="btn-primary py-2 text-xs">Go to market</a>
        </EmptyState>
        <div v-else class="grid grid-cols-1 gap-3 lg:grid-cols-2">
            <article v-for="h in holdings" :key="h.id" class="card p-4 sm:p-5">
                <div class="flex items-start gap-3">
                    <CoinIcon :src="h.image" :symbol="h.code || h.name" size="h-11 w-11" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-semibold text-white">{{ h.name }}</p>
                        <div class="mt-1 flex flex-wrap items-center gap-1.5">
                            <Badge :tone="h.fixed ? 'violet' : 'info'">{{ h.type }}</Badge>
                            <span v-if="h.code" class="font-mono text-xs text-zinc-500">{{ h.code }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] text-zinc-500">Invested</p>
                        <p class="font-mono font-semibold text-white">${{ formatMoney(h.invest) }}</p>
                    </div>
                </div>

                <dl class="mt-4 grid grid-cols-2 gap-3 rounded-2xl bg-white/[0.02] p-3 text-sm">
                    <template v-if="h.fixed">
                        <div><dt class="text-[11px] text-zinc-500">Interest earned</dt><dd class="font-mono text-up">${{ formatMoney(h.interest) }}</dd></div>
                        <div>
                            <dt class="text-[11px] text-zinc-500">{{ h.matured ? 'Unlocked' : 'Unlocks' }}</dt>
                            <dd class="text-zinc-200">{{ h.unlocksAt ? formatDate(h.unlocksAt) : '—' }}</dd>
                        </div>
                    </template>
                    <template v-else>
                        <div><dt class="text-[11px] text-zinc-500">Buy price</dt><dd class="font-mono text-zinc-200">${{ formatMoney(h.stackPrice) }}</dd></div>
                        <div>
                            <dt class="text-[11px] text-zinc-500">Market price</dt>
                            <dd class="font-mono" :class="live[h.code] == null ? 'text-zinc-500' : live[h.code] > h.stackPrice ? 'text-up' : 'text-down'">
                                <template v-if="h.code in live">{{ live[h.code] == null ? '—' : '$' + formatMoney(live[h.code]) }}</template>
                                <span v-else class="inline-block h-4 w-14 animate-pulse rounded bg-white/[0.06]"></span>
                            </dd>
                        </div>
                    </template>
                </dl>

                <div class="mt-4 flex flex-wrap gap-2">
                    <button class="btn-ghost flex-1 px-3 py-2 text-xs" @click="showCertificate(h)"><i class="ri-award-line"></i> Certificate</button>
                    <a v-if="h.exchangeUrl" :href="h.exchangeUrl" class="btn-ghost flex-1 px-3 py-2 text-xs"><i class="ri-swap-line"></i> Exchange</a>
                    <button v-if="h.reactiveUrl" class="btn-ghost flex-1 px-3 py-2 text-xs" @click="Object.assign(reactiveForm, { show: true, item: h })"><i class="ri-restart-line"></i> Reactive</button>
                    <button v-if="h.sellUrl" class="btn flex-1 bg-down/15 px-3 py-2 text-xs text-down hover:bg-down/25" @click="Object.assign(sell, { show: true, item: h })"><i class="ri-hand-coin-line"></i> Sell</button>
                    <span v-if="!h.sellUrl && h.fixed" class="flex flex-1 items-center justify-center gap-1 text-xs text-zinc-500"><i class="ri-lock-line"></i> Locked until maturity</span>
                </div>
            </article>
        </div>
    </template>

    <!-- Sold -->
    <template v-else>
        <EmptyState v-if="!sold.length" icon="ri-hand-coin-line" :title="`No sold ${noun}s`" class="card" />
        <div v-else class="card divide-y divide-white/[0.05] overflow-hidden">
            <div v-for="s in sold" :key="s.id" class="flex items-center gap-3 p-4">
                <CoinIcon :src="s.image" :symbol="s.name" size="h-10 w-10" />
                <div class="min-w-0 flex-1">
                    <p class="truncate font-medium text-white">{{ s.name }}</p>
                    <p class="text-xs text-zinc-500">{{ formatDate(s.date) }}</p>
                </div>
                <p class="font-mono font-semibold text-zinc-200">${{ formatMoney(s.invest) }}</p>
                <button class="grid h-9 w-9 place-items-center rounded-lg text-lg text-zinc-400 hover:bg-white/[0.06] hover:text-white" aria-label="Certificate" @click="showCertificate(s)"><i class="ri-award-line"></i></button>
            </div>
        </div>
    </template>

    <!-- Certificate modal -->
    <Modal :show="qr.show" :title="qr.name" @close="qr.show = false">
        <div class="flex flex-col items-center gap-4 py-2">
            <div class="grid h-48 w-48 place-items-center rounded-2xl bg-white p-3">
                <img v-if="qr.svg" :src="qr.svg" alt="Certificate QR code" class="h-full w-full" />
                <i v-else class="ri-loader-4-line animate-spin text-3xl text-zinc-400"></i>
            </div>
            <p class="text-center text-sm text-zinc-400">Scan to verify this {{ noun }} certificate.</p>
            <a :href="qr.url" target="_blank" class="btn-primary w-full"><i class="ri-external-link-line"></i> View certificate</a>
        </div>
    </Modal>

    <!-- Sell modal -->
    <Modal :show="sell.show" :title="`Sell ${noun}`" @close="sell.show = false">
        <p class="text-sm text-zinc-400">Are you sure you want to sell <span class="font-semibold text-white">{{ sell.item?.name }}</span>?</p>
        <div class="mt-5 grid grid-cols-2 gap-2">
            <button class="btn-ghost" @click="sell.show = false">Cancel</button>
            <button class="btn bg-down text-white hover:bg-down/90" :disabled="sell.busy" @click="confirmSell"><i :class="sell.busy ? 'ri-loader-4-line animate-spin' : 'ri-check-line'"></i> Confirm sell</button>
        </div>
    </Modal>

    <!-- Reactive modal -->
    <Modal :show="reactiveForm.show" :title="`Reactivate ${noun}`" @close="reactiveForm.show = false">
        <p class="mb-3 text-sm text-zinc-400">Choose a new holding period for <span class="font-semibold text-white">{{ reactiveForm.item?.name }}</span>.</p>
        <div class="grid grid-cols-2 gap-2">
            <label v-for="p in periods" :key="p.value" class="cursor-pointer">
                <input v-model="reactiveForm.period" type="radio" :value="p.value" class="peer sr-only" />
                <span class="block rounded-xl border border-white/10 p-3 text-center text-sm font-medium text-zinc-300 transition peer-checked:border-brand-500 peer-checked:bg-brand-500/10 peer-checked:text-white">{{ p.label }}</span>
            </label>
        </div>
        <button class="btn-primary mt-5 w-full" @click="confirmReactive"><i class="ri-restart-line"></i> Confirm</button>
    </Modal>
</template>
