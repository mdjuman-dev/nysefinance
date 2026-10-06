<script setup>
// P2P ad wizard: 1) side + asset + fiat, 2) price, limits, payment, 3) terms.
// Each step posts to the same save route, which redirects to the next step.
import { computed, onMounted, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import CoinIcon from '@/Components/CoinIcon.vue';
import Modal from '@/Components/UI/Modal.vue';
import { formatAmount } from '@/utils/format';

const props = defineProps({
    step: Number,
    done: String,
    ad: Object,
    old: Object,
    coins: Array,
    fiats: Array,
    market: Number,
    values: Object,
    paymentMethods: Array,
    paymentWindows: Array,
    openMethods: String,
    urls: Object,
});

const BUY = 1;
const SELL = 2;
const FIXED = 1;
const MARGIN = 2;
const locked = computed(() => !!props.ad?.complete); // side/asset/fiat can't change once published

const steps = [
    { n: 1, label: 'Type & asset' },
    { n: 2, label: 'Price & payment' },
    { n: 3, label: 'Terms' },
];
const stepHref = (n) => (props.urls.step ? props.urls.step + n : null);

/* ---------- step 1 ---------- */
const s1 = useForm({
    step: 1,
    type: props.old?.type ? Number(props.old.type) : props.ad?.type || BUY,
    asset: props.old?.asset ? Number(props.old.asset) : props.ad?.asset?.id || '',
    fiat: props.old?.fiat ? Number(props.old.fiat) : props.ad?.fiat?.id || '',
});
const picker = ref(null); // 'asset' | 'fiat'
const search = ref('');
const pickList = computed(() => {
    const list = picker.value === 'asset' ? props.coins : props.fiats;
    const q = search.value.trim().toLowerCase();
    return (list || []).filter((c) => !q || c.symbol.toLowerCase().includes(q) || (c.name || '').toLowerCase().includes(q));
});
const selected = (key) => {
    if (key === 'asset') return props.coins?.find((c) => c.id === s1.asset) || (props.ad?.asset?.id === s1.asset ? props.ad.asset : null);
    return props.fiats?.find((c) => c.id === s1.fiat) || (props.ad?.fiat?.id === s1.fiat ? props.ad.fiat : null);
};
function choose(id) {
    s1[picker.value] = id;
    picker.value = null;
    search.value = '';
}

/* ---------- step 2 ---------- */
const v = props.values || {};
const s2 = useForm({
    step: 2,
    price_type: Number(props.old?.price_type || v.price_type || FIXED),
    price: props.old?.price ?? v.price ?? '',
    margin: Number(props.old?.margin ?? v.margin ?? 100),
    minimum_amount: props.old?.minimum_amount ?? v.minimum_amount ?? '',
    maximum_amount: props.old?.maximum_amount ?? v.maximum_amount ?? '',
    payment_window: Number(props.old?.payment_window || v.payment_window || '') || '',
    payment_method: (props.old?.payment_method || v.payment_method || []).map(Number),
});
if (props.step === 2 && !Number(s2.price) && props.market) s2.price = +props.market.toFixed(4);
const marginPrice = computed(() => (props.market || 0) * (s2.margin / 100));
function setMargin(delta) {
    s2.margin = Math.max(1, Number(s2.margin) + delta);
}
function toggleMethod(id) {
    const i = s2.payment_method.indexOf(id);
    if (i >= 0) s2.payment_method.splice(i, 1);
    else s2.payment_method.push(id);
}
const s2Valid = computed(() => Number(s2.minimum_amount) > 0 && Number(s2.maximum_amount) > Number(s2.minimum_amount) && s2.payment_method.length && s2.payment_window);
onMounted(() => props.openMethods && setTimeout(() => window.open(props.openMethods), 1500));

/* ---------- step 3 ---------- */
const s3 = useForm({
    step: 3,
    payment_details: props.old?.payment_details ?? v.payment_details ?? '',
    terms_of_trade: props.old?.terms_of_trade ?? v.terms_of_trade ?? '',
    auto_replay_text: props.old?.auto_replay_text ?? v.auto_replay_text ?? '',
});

function submit() {
    if (props.step === 1) {
        if (locked.value) return (window.location.href = stepHref(2));
        s1.post(props.urls.save);
    } else if (props.step === 2) {
        s2.transform((d) => ({ ...d, price: d.price_type === MARGIN ? +marginPrice.value.toFixed(8) : d.price })).post(props.urls.save);
    } else {
        s3.post(props.urls.save);
    }
}
const processing = computed(() => s1.processing || s2.processing || s3.processing);
</script>

<template>
    <Head :title="ad ? 'Edit ad' : 'New ad'" />

    <div class="mx-auto max-w-2xl">
        <div class="mb-5 flex items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-white sm:text-2xl">{{ ad?.complete ? 'Edit P2P ad' : 'Post a P2P ad' }}</h1>
                <p class="text-sm text-zinc-500">Buyers and sellers will see this offer on the P2P market</p>
            </div>
            <Link :href="urls.list" class="btn-ghost py-2 text-xs"><i class="ri-list-check"></i> My ads</Link>
        </div>

        <!-- success -->
        <div v-if="done" class="card p-8 text-center">
            <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-up/10 text-3xl text-up"><i class="ri-checkbox-circle-fill"></i></span>
            <h2 class="mt-4 text-lg font-semibold text-white">Ad created</h2>
            <p class="mt-1 text-sm text-zinc-400">{{ done }}</p>
            <div class="mt-6 flex justify-center gap-2">
                <Link :href="urls.list" class="btn-ghost">View my ads</Link>
                <a :href="urls.new" class="btn-primary"><i class="ri-add-line"></i> New ad</a>
            </div>
        </div>

        <template v-else>
            <!-- stepper -->
            <ol class="mb-5 grid grid-cols-3 gap-2">
                <li v-for="s in steps" :key="s.n">
                    <component :is="stepHref(s.n) && s.n !== step ? Link : 'div'" :href="stepHref(s.n)" class="block">
                        <span class="block h-1.5 rounded-full" :class="s.n <= step ? 'bg-brand-500' : 'bg-white/10'"></span>
                        <span class="mt-2 block text-xs font-medium" :class="s.n === step ? 'text-white' : 'text-zinc-500'">{{ s.n }}. {{ s.label }}</span>
                    </component>
                </li>
            </ol>

            <form class="card space-y-5 p-5 sm:p-7" @submit.prevent="submit">
                <!-- STEP 1 -->
                <template v-if="step === 1">
                    <div>
                        <p class="mb-2 text-sm font-medium text-zinc-300">I want to</p>
                        <div class="grid grid-cols-2 gap-2 rounded-2xl bg-white/[0.03] p-1">
                            <button v-for="t in [{ v: BUY, l: 'Buy', c: 'bg-up text-ink-950' }, { v: SELL, l: 'Sell', c: 'bg-down text-white' }]" :key="t.v" type="button" :disabled="locked" class="rounded-xl py-2.5 text-sm font-semibold transition disabled:cursor-not-allowed" :class="s1.type === t.v ? t.c : 'text-zinc-400 hover:text-white'" @click="s1.type = t.v">{{ t.l }}</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div v-for="k in ['asset', 'fiat']" :key="k">
                            <p class="mb-2 text-sm font-medium text-zinc-300">{{ k === 'asset' ? 'Asset' : 'Fiat currency' }}</p>
                            <button type="button" :disabled="locked" class="field flex items-center gap-3 text-left disabled:cursor-not-allowed" :class="s1.errors[k] && 'border-down/60'" @click="picker = k">
                                <template v-if="selected(k)">
                                    <CoinIcon :src="selected(k).image" :symbol="selected(k).symbol" size="h-7 w-7" />
                                    <span class="flex-1 font-semibold text-white">{{ selected(k).symbol }}</span>
                                </template>
                                <span v-else class="flex-1 text-zinc-500">Select {{ k }}</span>
                                <i v-if="!locked" class="ri-arrow-down-s-line text-zinc-500"></i>
                            </button>
                            <p v-if="s1.errors[k]" class="mt-1 text-xs text-down">{{ s1.errors[k] }}</p>
                        </div>
                    </div>
                    <p v-if="locked" class="flex items-center gap-1.5 text-xs text-zinc-500"><i class="ri-lock-line"></i>Type and currencies can't be changed after the ad is published.</p>
                </template>

                <!-- STEP 2 -->
                <template v-else-if="step === 2">
                    <div class="flex items-center justify-between rounded-2xl bg-white/[0.03] px-4 py-3 text-sm">
                        <span class="flex items-center gap-2 text-zinc-400"><CoinIcon :src="ad.asset.image" :symbol="ad.asset.symbol" size="h-6 w-6" /> Market price</span>
                        <span class="font-mono text-white">1 {{ ad.asset.symbol }} ≈ {{ formatAmount(market) }} {{ ad.fiat.symbol }}</span>
                    </div>

                    <div>
                        <p class="mb-2 text-sm font-medium text-zinc-300">Pricing</p>
                        <div class="grid grid-cols-2 gap-2">
                            <label v-for="t in [{ v: FIXED, l: 'Fixed price', h: 'You set the exact price' }, { v: MARGIN, l: 'Floating', h: '% of market price' }]" :key="t.v" class="cursor-pointer">
                                <input v-model="s2.price_type" type="radio" :value="t.v" class="peer sr-only" />
                                <span class="block rounded-xl border border-white/10 p-3 transition peer-checked:border-brand-500 peer-checked:bg-brand-500/10">
                                    <span class="block text-sm font-semibold text-white">{{ t.l }}</span>
                                    <span class="block text-[11px] text-zinc-500">{{ t.h }}</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div v-if="s2.price_type === MARGIN" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <p class="mb-2 text-sm font-medium text-zinc-300">Margin</p>
                            <div class="flex items-center rounded-xl border border-white/10 bg-ink-850">
                                <button type="button" class="grid h-12 w-12 place-items-center text-zinc-400 hover:text-white" @click="setMargin(-1)"><i class="ri-subtract-line"></i></button>
                                <input v-model.number="s2.margin" type="number" step="any" min="1" class="w-full bg-transparent text-center font-mono text-lg text-white focus:outline-none" />
                                <span class="pr-1 text-zinc-500">%</span>
                                <button type="button" class="grid h-12 w-12 place-items-center text-zinc-400 hover:text-white" @click="setMargin(1)"><i class="ri-add-line"></i></button>
                            </div>
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-medium text-zinc-300">Your price</p>
                            <p class="field font-mono">{{ formatAmount(marginPrice) }} {{ ad.fiat.symbol }}</p>
                        </div>
                    </div>
                    <label v-else class="block">
                        <span class="mb-2 block text-sm font-medium text-zinc-300">Price per {{ ad.asset.symbol }}</span>
                        <span class="flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
                            <input v-model="s2.price" type="number" step="any" min="0" class="w-full bg-transparent px-4 py-3 font-mono text-lg text-white focus:outline-none" required />
                            <span class="pr-4 text-sm text-zinc-500">{{ ad.fiat.symbol }}</span>
                        </span>
                        <span v-if="s2.errors.price" class="mt-1 block text-xs text-down">{{ s2.errors.price }}</span>
                    </label>

                    <div class="grid grid-cols-2 gap-3">
                        <label v-for="k in ['minimum_amount', 'maximum_amount']" :key="k" class="block">
                            <span class="mb-2 block text-sm font-medium text-zinc-300">{{ k === 'minimum_amount' ? 'Min order' : 'Max order' }}</span>
                            <span class="flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60" :class="s2.errors[k] && 'border-down/60'">
                                <input v-model="s2[k]" type="number" step="any" min="0" class="w-full min-w-0 bg-transparent px-3 py-3 font-mono text-white focus:outline-none" required />
                                <span class="pr-3 text-xs text-zinc-500">{{ ad.fiat.symbol }}</span>
                            </span>
                            <span v-if="s2.errors[k]" class="mt-1 block text-xs text-down">{{ s2.errors[k] }}</span>
                        </label>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <p class="text-sm font-medium text-zinc-300">Payment methods</p>
                            <a :href="urls.methods" target="_blank" class="text-xs text-brand-300 hover:underline">Manage my methods</a>
                        </div>
                        <div v-if="paymentMethods.length" class="flex flex-wrap gap-2">
                            <button v-for="m in paymentMethods" :key="m.id" type="button" class="rounded-full border px-3 py-1.5 text-sm transition" :class="s2.payment_method.includes(m.id) ? 'border-brand-500 bg-brand-500/10 text-white' : 'border-white/10 text-zinc-400 hover:border-white/20'" @click="toggleMethod(m.id)">
                                <i v-if="s2.payment_method.includes(m.id)" class="ri-check-line text-brand-300"></i> {{ m.name }}
                            </button>
                        </div>
                        <p v-else class="text-sm text-zinc-500">No payment methods support {{ ad.fiat.symbol }} yet.</p>
                        <p v-if="ad.type === SELL" class="mt-2 text-xs text-zinc-500">For sell ads, add your account details for each selected method first.</p>
                    </div>

                    <div>
                        <p class="mb-2 text-sm font-medium text-zinc-300">Payment window</p>
                        <div class="flex flex-wrap gap-2">
                            <label v-for="w in paymentWindows" :key="w.id" class="cursor-pointer">
                                <input v-model="s2.payment_window" type="radio" :value="w.id" class="peer sr-only" />
                                <span class="block rounded-xl border border-white/10 px-4 py-2 text-sm text-zinc-300 transition peer-checked:border-brand-500 peer-checked:bg-brand-500/10 peer-checked:text-white">{{ w.minute }} min</span>
                            </label>
                        </div>
                    </div>
                </template>

                <!-- STEP 3 -->
                <template v-else>
                    <label v-for="f in [{ k: 'payment_details', l: 'Payment details', h: 'How the counterparty should pay or receive.', r: true }, { k: 'terms_of_trade', l: 'Terms of trade', h: 'Rules buyers and sellers must follow.', r: true }, { k: 'auto_replay_text', l: 'Auto reply', h: 'Sent automatically when someone opens a trade on this ad.', r: false }]" :key="f.k" class="block">
                        <span class="mb-1 block text-sm font-medium text-zinc-300">{{ f.l }} <span v-if="!f.r" class="text-zinc-500">(optional)</span></span>
                        <span class="mb-2 block text-xs text-zinc-500">{{ f.h }}</span>
                        <textarea v-model="s3[f.k]" rows="4" class="field" :required="f.r"></textarea>
                        <span v-if="s3.errors[f.k]" class="mt-1 block text-xs text-down">{{ s3.errors[f.k] }}</span>
                    </label>
                </template>

                <div class="flex items-center justify-between gap-3 border-t border-white/[0.06] pt-5">
                    <Link v-if="step > 1" :href="stepHref(step - 1)" class="btn-ghost"><i class="ri-arrow-left-line"></i> Back</Link>
                    <span v-else></span>
                    <button type="submit" class="btn-primary px-6" :disabled="processing || (step === 1 && (!s1.asset || !s1.fiat)) || (step === 2 && !s2Valid)">
                        <i v-if="processing" class="ri-loader-4-line animate-spin"></i>
                        {{ step === 3 ? (ad?.complete ? 'Save changes' : 'Publish ad') : 'Continue' }}
                        <i v-if="step < 3 && !processing" class="ri-arrow-right-line"></i>
                    </button>
                </div>
            </form>
        </template>
    </div>

    <Modal :show="!!picker" :title="picker === 'asset' ? 'Select asset' : 'Select fiat'" @close="picker = null">
        <div class="relative mb-3">
            <i class="ri-search-line absolute top-1/2 left-3 -translate-y-1/2 text-zinc-500"></i>
            <input v-model="search" class="field pl-9" placeholder="Search" />
        </div>
        <ul class="-mx-2 max-h-[55vh] overflow-y-auto">
            <li v-for="c in pickList" :key="c.id">
                <button type="button" class="flex w-full items-center gap-3 rounded-xl px-2 py-2.5 text-left hover:bg-white/[0.05]" @click="choose(c.id)">
                    <CoinIcon :src="c.image" :symbol="c.symbol" size="h-8 w-8" />
                    <span class="font-semibold text-white">{{ c.symbol }}</span>
                    <span class="truncate text-xs text-zinc-500">{{ c.name }}</span>
                </button>
            </li>
            <li v-if="!pickList.length" class="py-8 text-center text-sm text-zinc-500">Nothing found</li>
        </ul>
    </Modal>
</template>
