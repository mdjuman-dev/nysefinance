<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import CoinIcon from '@/Components/CoinIcon.vue';
import Modal from '@/Components/UI/Modal.vue';
import TvEmbed from '@/Components/Trade/TvEmbed.vue';

// Shared by bond and stock detail pages (`kind`); stocks can also be bought on the live market.
const props = defineProps({ bond: Object, videos: Array, isMember: Boolean, urls: Object, kind: { type: String, default: 'bond' } });
const noun = computed(() => (props.kind === 'stock' ? 'stock' : 'bond'));
const isStock = computed(() => props.kind === 'stock');

/* Analysis tabs — the same TradingView widgets the Blade page embedded */
const tabs = [
    { key: 'technical', label: 'Analytics', widget: 'technical-analysis', config: { interval: '1m', displayMode: 'multiple', showIntervalTabs: true } },
    { key: 'financials', label: 'Financials', widget: 'financials', config: { displayMode: 'regular' } },
    { key: 'profile', label: 'Profile', widget: 'symbol-profile', config: {} },
    { key: 'events', label: 'Economic', widget: 'events', config: { importanceFilter: '-1,0,1', countryFilter: 'ar,au,br,ca,cn,fr,de,in,id,it,jp,kr,mx,ru,sa,za,tr,gb,us,eu' } },
];
const tab = ref('technical');
const activeTab = computed(() => tabs.find((t) => t.key === tab.value));
const descOpen = ref(false);

/*
 * Buy form → user.stock.buy with the fields the Blade modal sent. Bonds are always "fix"
 * (mutual fund); stocks may also be "unfix" (live market) with a 25/50/100% or custom amount.
 */
const buyOpen = ref(false);
const form = useForm({ id: props.bond.id, type: 'fix', invest_amount: '', invest_time: 'month', unfix_amount: '100', unfix_invest_amount: '' });
const periods = [
    { value: 'month', label: '1 month' },
    { value: 'half_year', label: '6 months' },
    { value: 'year', label: '1 year' },
];
const presets = [50, 100, 500, 1000];
const liveShares = [
    { value: '25', label: '25%' },
    { value: '50', label: '50%' },
    { value: '100', label: '100%' },
    { value: 'custom', label: 'Custom' },
];
const canBuy = computed(() => (form.type === 'fix' ? Number(form.invest_amount) > 0 : form.unfix_amount !== 'custom' || Number(form.unfix_invest_amount) > 0));
function submit() {
    form.post(props.urls.buy, { preserveScroll: true, onSuccess: () => { buyOpen.value = false; form.reset('invest_amount'); } });
}
</script>

<template>
    <Head :title="bond.name" />

    <!-- Header -->
    <div class="mb-4 flex flex-wrap items-center gap-3">
        <Link :href="urls.market" class="grid h-10 w-10 place-items-center rounded-xl border border-white/[0.07] text-lg text-zinc-400 hover:text-white" :aria-label="`Back to ${noun}s`"><i class="ri-arrow-left-line"></i></Link>
        <CoinIcon :src="bond.image" :symbol="bond.code || bond.name" size="h-11 w-11" />
        <div class="min-w-0 flex-1">
            <h1 class="truncate text-lg font-bold text-white sm:text-xl">{{ bond.name }}</h1>
            <p class="flex items-center gap-2 text-xs text-zinc-500">
                <span class="rounded-md bg-brand-500/10 px-1.5 py-0.5 font-semibold text-brand-300">{{ bond.category }}</span>
                <span class="font-mono">{{ bond.code }}</span>
            </p>
        </div>
        <div class="hidden gap-2 sm:flex">
            <Link :href="urls.my" class="btn-ghost"><i class="ri-hand-coin-line"></i> Sell {{ noun }}</Link>
            <button class="btn-primary" @click="buyOpen = true"><i class="ri-add-line"></i> Buy {{ noun }}</button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="min-w-0 space-y-5 lg:col-span-2">
            <!-- Chart -->
            <div class="card h-[420px] overflow-hidden sm:h-[520px]">
                <TvEmbed widget="advanced-chart" :config="{ symbol: bond.code, interval: '1', style: '1', theme: 'dark', timezone: 'Etc/UTC', hide_side_toolbar: true, allow_symbol_change: false, isTransparent: false, backgroundColor: '#0b0d12', gridColor: 'rgba(255,255,255,0.04)', autosize: true }" />
            </div>

            <!-- Analysis tabs -->
            <section class="card overflow-hidden">
                <div class="flex gap-1 overflow-x-auto border-b border-white/[0.05] p-2 [scrollbar-width:none]">
                    <button v-for="t in tabs" :key="t.key" class="shrink-0 rounded-lg px-3.5 py-2 text-sm font-medium transition" :class="tab === t.key ? 'bg-white/[0.08] text-white' : 'text-zinc-500 hover:text-zinc-200'" @click="tab = t.key">{{ t.label }}</button>
                </div>
                <div :key="tab" class="h-[460px]">
                    <TvEmbed :widget="activeTab.widget" :config="{ symbol: bond.code, ...activeTab.config }" />
                </div>
            </section>

            <!-- About (CMS HTML) -->
            <section v-if="bond.summary || bond.description" class="card p-5 sm:p-6">
                <h2 class="font-semibold text-white">About {{ bond.name }}</h2>
                <div class="cms-content mt-3" v-html="bond.summary"></div>
                <template v-if="bond.description">
                    <div class="cms-content relative overflow-hidden" :class="descOpen ? '' : 'max-h-56'" v-html="bond.description"></div>
                    <button class="mt-3 text-sm font-medium text-brand-300 hover:text-brand-200" @click="descOpen = !descOpen">{{ descOpen ? 'Show less' : 'Read more' }}</button>
                </template>
            </section>

            <!-- Videos -->
            <section v-if="videos.length" class="card p-5 sm:p-6">
                <h2 class="mb-4 font-semibold text-white">Videos</h2>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <video v-for="(v, i) in videos" :key="i" controls preload="metadata" class="aspect-video w-full rounded-xl bg-black"><source :src="v" type="video/mp4" /></video>
                </div>
            </section>
        </div>

        <!-- Side: invest card (desktop) -->
        <aside class="hidden lg:block">
            <div class="sticky top-24 overflow-hidden rounded-3xl border border-white/[0.07] bg-gradient-to-br from-ink-800 to-ink-900 p-6">
                <p class="text-sm text-zinc-400">Invest in</p>
                <p class="mt-1 text-xl font-bold text-white">{{ bond.name }}</p>
                <ul class="mt-5 space-y-3 text-sm">
                    <li class="flex items-center gap-2 text-zinc-300"><i class="ri-shield-check-line text-brand-400"></i> {{ isStock ? 'Mutual fund or live market' : 'Held as a mutual-fund position' }}</li>
                    <li class="flex items-center gap-2 text-zinc-300"><i class="ri-time-line text-brand-400"></i> 1, 6 or 12 month holding period</li>
                    <li class="flex items-center gap-2 text-zinc-300"><i class="ri-award-line text-brand-400"></i> Certificate issued on purchase</li>
                </ul>
                <button class="btn-primary mt-6 w-full py-3" @click="buyOpen = true"><i class="ri-add-line"></i> Buy {{ noun }}</button>
                <Link :href="urls.my" class="btn-ghost mt-2 w-full"><i class="ri-hand-coin-line"></i> Sell from My {{ noun }}s</Link>
                <p v-if="bond.info" class="mt-5 rounded-xl bg-white/[0.03] p-3 text-xs leading-5 text-zinc-400"><i class="ri-information-line text-sky-300"></i> {{ bond.info }}</p>
            </div>
        </aside>
    </div>

    <!-- Mobile action bar -->
    <div class="fixed inset-x-0 bottom-[calc(4rem+env(safe-area-inset-bottom))] z-30 grid grid-cols-2 gap-2 border-t border-white/[0.06] bg-ink-950/95 px-4 py-3 backdrop-blur-xl lg:hidden">
        <button class="btn-primary py-3" @click="buyOpen = true">Buy {{ noun }}</button>
        <Link :href="urls.my" class="btn bg-down py-3 text-white">Sell {{ noun }}</Link>
    </div>
    <div class="h-16 lg:hidden"></div>

    <!-- Buy sheet -->
    <Modal :show="buyOpen" :title="`Buy ${noun}`" @close="buyOpen = false">
        <form class="space-y-4" @submit.prevent="submit">
            <div class="flex items-center gap-3 rounded-2xl bg-white/[0.03] p-3">
                <CoinIcon :src="bond.image" :symbol="bond.code || bond.name" size="h-10 w-10" />
                <div class="min-w-0">
                    <p class="truncate font-semibold text-white">{{ bond.name }}</p>
                    <p class="text-xs text-zinc-500">{{ bond.category }} · {{ form.type === 'fix' ? 'Mutual fund' : 'Live market' }}</p>
                </div>
            </div>

            <fieldset v-if="isStock">
                <legend class="mb-2 text-sm font-medium text-zinc-300">Buy in</legend>
                <div class="grid grid-cols-2 gap-2">
                    <label v-for="t in [{ v: 'fix', l: 'Mutual fund', h: 'Fixed holding period' }, { v: 'unfix', l: 'Live market', h: 'Share of your stock wallet' }]" :key="t.v" class="cursor-pointer">
                        <input v-model="form.type" type="radio" :value="t.v" class="peer sr-only" />
                        <span class="block rounded-xl border border-white/10 p-3 transition peer-checked:border-brand-500 peer-checked:bg-brand-500/10">
                            <span class="block text-sm font-semibold text-white">{{ t.l }}</span>
                            <span class="block text-[11px] text-zinc-500">{{ t.h }}</span>
                        </span>
                    </label>
                </div>
            </fieldset>

            <div v-if="form.type === 'unfix'" class="space-y-3">
                <div class="grid grid-cols-4 gap-1.5">
                    <button v-for="p in liveShares" :key="p.value" type="button" class="rounded-lg py-2 text-xs font-semibold transition" :class="form.unfix_amount === p.value ? 'bg-brand-500/15 text-brand-300' : 'bg-white/[0.04] text-zinc-400 hover:bg-white/[0.08]'" @click="form.unfix_amount = p.value">{{ p.label }}</button>
                </div>
                <div v-if="form.unfix_amount === 'custom'" class="flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
                    <span class="pl-4 text-zinc-500">$</span>
                    <input v-model="form.unfix_invest_amount" type="number" step="any" min="0" inputmode="decimal" placeholder="0.00" class="w-full bg-transparent px-2 py-3 font-mono text-lg text-zinc-100 focus:outline-none" />
                    <span class="pr-4 text-sm text-zinc-500">USDT</span>
                </div>
            </div>

            <div v-if="form.type === 'fix'">
                <label class="mb-2 block text-sm font-medium text-zinc-300">Amount</label>
                <div class="flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
                    <span class="pl-4 text-zinc-500">$</span>
                    <input v-model="form.invest_amount" type="number" step="any" min="0" inputmode="decimal" placeholder="0.00" class="w-full bg-transparent px-2 py-3 font-mono text-lg text-zinc-100 focus:outline-none" :required="form.type === 'fix'" />
                    <span class="pr-4 text-sm text-zinc-500">USDT</span>
                </div>
                <div class="mt-2 grid grid-cols-4 gap-1.5">
                    <button v-for="p in presets" :key="p" type="button" class="rounded-lg py-1.5 font-mono text-xs font-semibold transition" :class="Number(form.invest_amount) === p ? 'bg-brand-500/15 text-brand-300' : 'bg-white/[0.04] text-zinc-400 hover:bg-white/[0.08]'" @click="form.invest_amount = String(p)">${{ p }}</button>
                </div>
            </div>

            <fieldset v-if="form.type === 'fix'">
                <legend class="mb-2 text-sm font-medium text-zinc-300">Holding period</legend>
                <div class="grid grid-cols-3 gap-2">
                    <label v-for="p in periods" :key="p.value" class="cursor-pointer">
                        <input v-model="form.invest_time" type="radio" :value="p.value" class="peer sr-only" />
                        <span class="block rounded-xl border border-white/10 py-3 text-center text-sm font-medium text-zinc-300 transition peer-checked:border-brand-500 peer-checked:bg-brand-500/10 peer-checked:text-white">{{ p.label }}</span>
                    </label>
                </div>
            </fieldset>

            <p v-if="bond.info" class="rounded-xl border border-sky-400/15 bg-sky-400/[0.05] p-3 text-xs leading-5 text-zinc-400"><i class="ri-information-line text-sky-300"></i> {{ bond.info }}</p>

            <button type="submit" class="btn-primary w-full py-3" :disabled="form.processing || !canBuy">
                <i :class="form.processing ? 'ri-loader-4-line animate-spin' : 'ri-check-line'"></i> Confirm purchase
            </button>
        </form>
    </Modal>
</template>
