<script>
import { h } from 'vue';
import SiteLayout from '@/Layouts/SiteLayout.vue';
import UserLayout from '@/Layouts/UserLayout.vue';

// Public page: signed-in users get the app shell, guests the marketing site.
export default {
    layout: (hFn, page) => hFn(page.props.shell ? UserLayout : SiteLayout, () => page),
};
</script>

<script setup>
import { computed, reactive, ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import EmptyState from '@/Components/UI/EmptyState.vue';
import Modal from '@/Components/UI/Modal.vue';
import { useToast } from '@/composables/useToast';
import { getJson, postJson, messageText } from '@/utils/http';
import { formatAmount, formatMoney } from '@/utils/format';

const props = defineProps({
    filters: Object,
    coins: Array,
    currencies: Array,
    paymentMethods: Array,
    countries: Array,
    ads: Array,
    total: Number,
    take: Number,
    urls: Object,
});
const page = usePage();
const signedIn = computed(() => !!page.props.shell);
const toast = useToast();

/* Filters → same path the Blade URLBuilder produced: /p2p/{type}/{coin}/{currency}/{paymentMethod}/{region}/{amount} */
const f = reactive({ ...props.filters });
const loading = ref(false);
function visit({ take = 20 } = {}) {
    const anyTail = f.amount || f.region !== 'all';
    const parts = [f.type, f.coin || 'all'];
    if (f.currency !== 'all' || f.paymentMethod !== 'all' || anyTail) parts.push(f.currency || 'all');
    if (f.paymentMethod !== 'all' || anyTail) parts.push(f.paymentMethod || 'all');
    if (anyTail) parts.push(f.region || 'all');
    if (f.amount) parts.push(f.amount);
    loading.value = true;
    router.get(`${props.urls.base}/${parts.join('/')}`, take > 20 ? { take } : {}, {
        preserveState: true,
        preserveScroll: take > 20,
        replace: take > 20,
        only: ['ads', 'total', 'take', 'filters'],
        onFinish: () => (loading.value = false),
    });
}
function setType(t) { f.type = t; visit(); }
function setCurrency(c) { f.currency = c; f.paymentMethod = 'all'; visit(); }
const currencyMethods = computed(() => (f.currency === 'all' ? [] : props.paymentMethods.filter((p) => p.currencies.includes(f.currency))));
const showFilters = ref(false);
const activeFilterCount = computed(() => [f.currency !== 'all', f.paymentMethod !== 'all', f.region !== 'all', !!f.amount].filter(Boolean).length);
function resetFilters() { Object.assign(f, { currency: 'all', paymentMethod: 'all', region: 'all', amount: '' }); visit(); }

/* Trade request flow (existing endpoints) */
const trade = reactive({ show: false, ad: null, detail: null, busy: false, fiat: '', asset: '', method: '', submitting: false });
const methodForm = reactive({ show: false, title: '', html: '' });

async function startTrade(ad) {
    if (!signedIn.value) {
        toast.error('Please log in to your account');
        return (window.location.href = props.urls.login);
    }
    trade.busy = ad.id;
    try {
        const status = await getJson(props.urls.checkStatus);
        if (status.status === 'success') return toast.error(status.message);
        const res = await getJson(props.urls.request.replace('__ID__', ad.id), { type: f.type });
        if (!res.success) return toast.error(messageText(res.message) || 'Something went wrong');
        Object.assign(trade, { show: true, ad, detail: res.data.detail, fiat: '', asset: '', method: '' });
    } catch {
        toast.error('Something went wrong');
    } finally {
        trade.busy = false;
    }
}
const round = (n, d = 8) => (Number.isFinite(n) ? String(+n.toFixed(d)) : '');
const onFiat = () => (trade.asset = trade.fiat ? round(Number(trade.fiat) / trade.detail.price) : '');
const onAsset = () => (trade.fiat = trade.asset ? round(Number(trade.asset) * trade.detail.price, 2) : '');
const withinLimit = computed(() => {
    const v = Number(trade.fiat);
    return trade.detail && v >= trade.detail.min && v <= trade.detail.max;
});

async function submitTrade() {
    trade.submitting = true;
    const res = await postJson(props.urls.save.replace('__ID__', trade.ad.id), {
        type: f.type,
        fiat_amount: trade.fiat,
        asset_amount: trade.asset,
        payment_method: trade.method,
    }, { asForm: true });
    trade.submitting = false;
    if (res.success) return (window.location.href = res.data.url);
    if (res.data?.ad_payment_method) Object.assign(methodForm, { show: true, title: res.data.title, html: res.data.html });
    toast.error(messageText(res.message) || 'Something went wrong');
}

// The payment-method form is server-rendered; submit it via fetch like before.
async function submitMethodForm(e) {
    const form = e.target.closest('form');
    if (!form) return;
    e.preventDefault();
    const res = await fetch(form.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
        body: new FormData(form),
    }).then((r) => r.json()).catch(() => ({}));
    if (res.success) {
        methodForm.show = false;
        toast.success(messageText(res.message) || 'Saved');
    } else toast.error(messageText(res.message) || 'Something went wrong');
}

const initials = (n) => (n || '?').split(' ').filter(Boolean).map((w) => w[0]).slice(0, 2).join('').toUpperCase();
</script>

<template>
    <Head title="P2P trading" />

    <div :class="signedIn ? '' : 'container-x pt-28 lg:pt-32'">
        <!-- Header -->
        <div class="mb-5 flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-white sm:text-2xl">P2P trading</h1>
                <p class="mt-0.5 text-sm text-zinc-500">Buy and sell crypto directly with other users</p>
            </div>
            <a v-if="urls.dashboard" :href="urls.dashboard" class="btn-ghost"><i class="ri-store-2-line"></i> P2P center</a>
        </div>

        <!-- Buy / Sell + coin -->
        <div class="card mb-3 p-3 sm:p-4">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                <div class="grid grid-cols-2 gap-1 rounded-xl bg-white/[0.04] p-1 lg:w-60">
                    <button class="rounded-lg py-2 text-sm font-semibold transition" :class="f.type === 'buy' ? 'bg-up text-ink-950' : 'text-zinc-400'" @click="setType('buy')">Buy</button>
                    <button class="rounded-lg py-2 text-sm font-semibold transition" :class="f.type === 'sell' ? 'bg-down text-white' : 'text-zinc-400'" @click="setType('sell')">Sell</button>
                </div>
                <div class="-mx-1 flex flex-1 gap-1 overflow-x-auto px-1 [scrollbar-width:none]">
                    <button v-for="c in coins" :key="c.symbol" class="shrink-0 rounded-lg px-3.5 py-2 text-sm font-semibold transition" :class="f.coin === c.symbol ? 'bg-white/[0.08] text-white' : 'text-zinc-500 hover:text-zinc-200'" @click="f.coin = c.symbol; visit()">{{ c.symbol }}</button>
                </div>
                <button class="btn-ghost relative py-2 lg:hidden" @click="showFilters = !showFilters">
                    <i class="ri-filter-3-line"></i> Filters
                    <span v-if="activeFilterCount" class="grid h-5 w-5 place-items-center rounded-full bg-brand-500 text-[11px] font-bold text-ink-950">{{ activeFilterCount }}</span>
                </button>
            </div>

            <!-- Filters -->
            <div class="mt-3 grid-cols-2 gap-2 border-t border-white/[0.05] pt-3 sm:grid-cols-4" :class="showFilters ? 'grid' : 'hidden lg:grid'">
                <div class="relative">
                    <select :value="f.currency" class="w-full appearance-none rounded-lg border border-white/[0.07] bg-ink-850 py-2.5 pr-8 pl-3 text-sm text-zinc-200 focus:outline-none" @change="setCurrency($event.target.value)">
                        <option value="all">All currencies</option>
                        <option v-for="c in currencies" :key="c.symbol" :value="c.symbol">{{ c.symbol }} — {{ c.name }}</option>
                    </select>
                    <i class="ri-arrow-down-s-line pointer-events-none absolute top-1/2 right-2.5 -translate-y-1/2 text-zinc-500"></i>
                </div>
                <div class="relative">
                    <select v-model="f.paymentMethod" :disabled="f.currency === 'all'" class="w-full appearance-none rounded-lg border border-white/[0.07] bg-ink-850 py-2.5 pr-8 pl-3 text-sm text-zinc-200 focus:outline-none disabled:opacity-50" @change="visit()">
                        <option value="all">{{ f.currency === 'all' ? 'Pick a currency first' : 'All payments' }}</option>
                        <option v-for="p in currencyMethods" :key="p.slug" :value="p.slug">{{ p.name }}</option>
                    </select>
                    <i class="ri-arrow-down-s-line pointer-events-none absolute top-1/2 right-2.5 -translate-y-1/2 text-zinc-500"></i>
                </div>
                <div class="relative">
                    <select v-model="f.region" class="w-full appearance-none rounded-lg border border-white/[0.07] bg-ink-850 py-2.5 pr-8 pl-3 text-sm text-zinc-200 focus:outline-none" @change="visit()">
                        <option value="all">All regions</option>
                        <option v-for="c in countries" :key="c.code" :value="c.code">{{ c.name }}</option>
                    </select>
                    <i class="ri-arrow-down-s-line pointer-events-none absolute top-1/2 right-2.5 -translate-y-1/2 text-zinc-500"></i>
                </div>
                <div class="flex gap-2">
                    <input v-model="f.amount" type="number" min="0" step="any" placeholder="Amount" class="w-full min-w-0 rounded-lg border border-white/[0.07] bg-white/[0.03] px-3 py-2.5 text-sm text-zinc-200 placeholder:text-zinc-500 focus:outline-none" @change="visit()" />
                    <button v-if="activeFilterCount" class="shrink-0 rounded-lg px-3 text-xs text-zinc-400 hover:bg-white/[0.05] hover:text-white" @click="resetFilters">Reset</button>
                </div>
            </div>
        </div>

        <!-- Ads -->
        <div class="relative">
            <div v-if="loading" class="absolute inset-0 z-10 grid place-items-start justify-center bg-ink-950/40 pt-20 backdrop-blur-[1px]"><i class="ri-loader-4-line animate-spin text-3xl text-brand-400"></i></div>

            <EmptyState v-if="!ads.length" icon="ri-team-line" title="No ads match your filters" text="Try another coin, currency or payment method." class="card" />

            <div v-else class="space-y-3">
                <article v-for="ad in ads" :key="ad.id" class="card grid grid-cols-1 gap-4 p-4 sm:p-5 lg:grid-cols-[1.3fr_1fr_1.2fr_auto] lg:items-center">
                    <!-- advertiser -->
                    <div class="flex items-center gap-3">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-400 to-sky-500 text-xs font-bold text-ink-950">{{ initials(ad.advertiser) }}</span>
                        <div class="min-w-0">
                            <a :href="ad.profile" target="_blank" class="flex items-center gap-1.5 truncate font-semibold text-white hover:text-brand-300">
                                {{ ad.advertiser }} <i v-if="ad.verified" class="ri-verified-badge-fill text-brand-400" title="KYC verified"></i>
                            </a>
                            <p class="text-xs text-zinc-500">{{ ad.trades }} trades · {{ ad.rate.toFixed(2) }}% completion</p>
                        </div>
                    </div>
                    <!-- price -->
                    <div class="flex items-baseline justify-between lg:block">
                        <p class="text-[11px] text-zinc-500 lg:mb-0.5">Price</p>
                        <p class="font-mono text-xl font-bold text-white">{{ formatMoney(ad.price) }} <span class="text-xs font-medium text-zinc-500">{{ ad.fiat }}</span></p>
                    </div>
                    <!-- limits -->
                    <div class="space-y-1 text-xs">
                        <p class="flex justify-between gap-3 lg:justify-start"><span class="text-zinc-500">Available</span><span class="font-mono text-zinc-200">{{ formatAmount(ad.available) }} {{ ad.asset }}</span></p>
                        <p class="flex justify-between gap-3 lg:justify-start"><span class="text-zinc-500">Limit</span><span class="font-mono text-zinc-200">{{ formatMoney(ad.min) }} – {{ formatMoney(ad.max) }} {{ ad.fiat }}</span></p>
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <span v-for="m in ad.methods" :key="m.name" class="inline-flex items-center gap-1.5 rounded-md bg-white/[0.04] px-2 py-0.5 text-[11px] text-zinc-300">
                                <span class="h-2.5 w-1 rounded-full" :style="{ background: '#' + m.color }"></span>{{ m.name }}
                            </span>
                        </div>
                    </div>
                    <!-- action -->
                    <div class="flex items-center justify-between gap-3 border-t border-white/[0.05] pt-3 lg:flex-col lg:items-end lg:border-0 lg:pt-0">
                        <span class="text-xs text-zinc-500"><i class="ri-time-line"></i> {{ ad.minutes }} min</span>
                        <button class="btn min-w-24 py-2" :class="f.type === 'buy' ? 'bg-up text-ink-950 hover:bg-up/90' : 'bg-down text-white hover:bg-down/90'" :disabled="trade.busy === ad.id" @click="startTrade(ad)">
                            <i v-if="trade.busy === ad.id" class="ri-loader-4-line animate-spin"></i>
                            {{ f.type === 'buy' ? 'Buy' : 'Sell' }} {{ ad.asset }}
                        </button>
                    </div>
                </article>
            </div>

            <div v-if="ads.length < total" class="mt-4 text-center">
                <button class="btn-ghost" :disabled="loading" @click="visit({ take: take + 20 })"><i class="ri-add-line"></i> Load more ({{ total - ads.length }} left)</button>
            </div>
        </div>
    </div>

    <!-- Trade sheet -->
    <Modal :show="trade.show" :title="`${f.type === 'buy' ? 'Buy' : 'Sell'} ${trade.detail?.asset || ''}`" size="max-w-lg" @close="trade.show = false">
        <template v-if="trade.detail">
            <div class="flex items-center gap-3 rounded-2xl bg-white/[0.03] p-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-brand-400 to-sky-500 text-xs font-bold text-ink-950">{{ initials(trade.detail.advertiser) }}</span>
                <div class="min-w-0 flex-1">
                    <p class="flex items-center gap-1.5 truncate font-semibold text-white">{{ trade.detail.advertiser }} <i v-if="trade.detail.verified" class="ri-verified-badge-fill text-brand-400"></i></p>
                    <p class="text-xs text-zinc-500">{{ trade.detail.trades }} trades</p>
                </div>
                <span class="rounded-md bg-up/10 px-2 py-1 text-xs text-up"><i class="ri-thumb-up-line"></i> {{ trade.detail.positive ?? 0 }}%</span>
                <span class="rounded-md bg-down/10 px-2 py-1 text-xs text-down"><i class="ri-thumb-down-line"></i> {{ trade.detail.negative ?? 0 }}%</span>
            </div>
            <dl class="mt-3 grid grid-cols-3 gap-2 text-center text-xs">
                <div class="rounded-xl bg-white/[0.02] p-2.5"><dt class="text-zinc-500">Price</dt><dd class="mt-1 font-mono font-semibold text-white">{{ formatMoney(trade.detail.price) }} {{ trade.detail.fiat }}</dd></div>
                <div class="rounded-xl bg-white/[0.02] p-2.5"><dt class="text-zinc-500">Available</dt><dd class="mt-1 font-mono font-semibold text-white">{{ formatAmount(trade.detail.available) }} {{ trade.detail.asset }}</dd></div>
                <div class="rounded-xl bg-white/[0.02] p-2.5"><dt class="text-zinc-500">Pay within</dt><dd class="mt-1 font-semibold text-white">{{ trade.detail.minutes }} min</dd></div>
            </dl>

            <form class="mt-4 space-y-3" @submit.prevent="submitTrade">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-zinc-300">{{ f.type === 'buy' ? 'I want to pay' : 'I will receive' }}</label>
                    <div class="flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
                        <input v-model="trade.fiat" type="number" step="any" min="0" placeholder="0.00" class="w-full bg-transparent px-4 py-3 font-mono text-sm text-zinc-100 focus:outline-none" @input="onFiat" required />
                        <span class="pr-4 text-sm font-semibold text-zinc-400">{{ trade.detail.fiat }}</span>
                    </div>
                    <p class="mt-1 text-[11px]" :class="trade.fiat && !withinLimit ? 'text-down' : 'text-zinc-500'">Limit {{ formatMoney(trade.detail.min) }} – {{ formatMoney(trade.detail.max) }} {{ trade.detail.fiat }}</p>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-zinc-300">{{ f.type === 'buy' ? 'I will receive' : 'I want to sell' }}</label>
                    <div class="flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
                        <input v-model="trade.asset" type="number" step="any" min="0" placeholder="0.00" class="w-full bg-transparent px-4 py-3 font-mono text-sm text-zinc-100 focus:outline-none" @input="onAsset" required />
                        <span class="pr-4 text-sm font-semibold text-zinc-400">{{ trade.detail.asset }}</span>
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-zinc-300">Payment method</label>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        <label v-for="m in trade.detail.methods" :key="m.id" class="cursor-pointer">
                            <input v-model="trade.method" type="radio" :value="m.id" class="peer sr-only" required />
                            <span class="flex items-center gap-2 rounded-xl border border-white/10 p-3 text-sm text-zinc-200 transition peer-checked:border-brand-500 peer-checked:bg-brand-500/10">
                                <span class="h-4 w-1 rounded-full" :style="{ background: '#' + (m.color || '72d914') }"></span>{{ m.name }}
                            </span>
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <button type="button" class="btn-ghost" @click="trade.show = false">Cancel</button>
                    <button type="submit" class="btn" :class="f.type === 'buy' ? 'bg-up text-ink-950 hover:bg-up/90' : 'bg-down text-white hover:bg-down/90'" :disabled="!withinLimit || !trade.method || trade.submitting">
                        <i v-if="trade.submitting" class="ri-loader-4-line animate-spin"></i> {{ f.type === 'buy' ? 'Buy' : 'Sell' }} {{ trade.detail.asset }}
                    </button>
                </div>
            </form>
        </template>
    </Modal>

    <!-- Payment method info required by the trade (server-rendered form) -->
    <Modal :show="methodForm.show" :title="methodForm.title" @close="methodForm.show = false">
        <div class="viser-fields" @submit="submitMethodForm" v-html="methodForm.html"></div>
    </Modal>
</template>

