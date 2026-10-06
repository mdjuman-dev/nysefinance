<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import Modal from '@/Components/UI/Modal.vue';
import CoinIcon from '@/Components/CoinIcon.vue';
import TvEmbed from '@/Components/Trade/TvEmbed.vue';

const props = defineProps({ categories: Array, isMember: Boolean, urls: Object });

// Remember the last category tab per browser.
const KEY = 'bondCategory';
let saved = null;
try { saved = localStorage.getItem(KEY); } catch {}
const active = ref(props.categories.some((c) => c.key === saved) ? saved : props.categories[0]?.key);
function selectTab(key) {
    active.value = key;
    try { localStorage.setItem(KEY, key); } catch {}
}

const search = ref('');
const view = ref('cards'); // cards | list
const current = computed(() => props.categories.find((c) => c.key === active.value) || { items: [] });
const items = computed(() => {
    const q = search.value.trim().toLowerCase();
    return current.value.items.filter((b) => !q || b.name.toLowerCase().includes(q) || (b.code || '').toLowerCase().includes(q));
});
const total = computed(() => props.categories.reduce((s, c) => s + c.items.length, 0));

/* Membership gate (all bond routes sit behind check.stock) */
const memberModal = ref(false);
const buying = ref(false);
function open(e, bond) {
    if (props.isMember) return; // normal link navigation
    e.preventDefault();
    memberModal.value = true;
}
function buyMembership() {
    buying.value = true;
    router.post(props.urls.member, {}, { onFinish: () => { buying.value = false; memberModal.value = false; } });
}
</script>

<template>
    <Head title="Bonds" />
    <PageHeader title="Bonds" :subtitle="`${total} instruments across bonds, economy, indices and options`" icon="ri-bank-line">
        <template #actions>
            <Link :href="urls.history" class="btn-ghost"><i class="ri-file-history-line"></i> History</Link>
            <Link :href="urls.my" class="btn-primary"><i class="ri-briefcase-4-line"></i> My bonds</Link>
        </template>
    </PageHeader>

    <!-- Category tabs -->
    <div class="-mx-4 mb-4 overflow-x-auto px-4 [scrollbar-width:none] sm:mx-0 sm:px-0">
        <div class="inline-flex min-w-max gap-1 rounded-2xl border border-white/[0.06] bg-ink-900/70 p-1">
            <button v-for="c in categories" :key="c.key" class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition" :class="active === c.key ? 'bg-brand-500 text-ink-950 shadow-lg shadow-brand-500/20' : 'text-zinc-400 hover:text-white'" @click="selectTab(c.key)">
                {{ c.label }}
                <span class="rounded-md px-1.5 text-[11px]" :class="active === c.key ? 'bg-ink-950/15' : 'bg-white/[0.06]'">{{ c.items.length }}</span>
            </button>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="card mb-4 flex items-center gap-2 p-2.5">
        <label class="relative block flex-1">
            <i class="ri-search-line pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-sm text-zinc-500"></i>
            <input v-model="search" type="search" :placeholder="`Search ${current.label || ''}`" class="w-full rounded-lg border border-white/[0.07] bg-white/[0.03] py-2.5 pr-3 pl-9 text-sm text-zinc-200 placeholder:text-zinc-500 focus:border-brand-500/50 focus:outline-none" />
        </label>
        <div class="flex rounded-lg bg-white/[0.04] p-1">
            <button class="grid h-8 w-8 place-items-center rounded-md" :class="view === 'cards' ? 'bg-ink-700 text-white' : 'text-zinc-500'" aria-label="Chart cards" @click="view = 'cards'"><i class="ri-layout-grid-line"></i></button>
            <button class="grid h-8 w-8 place-items-center rounded-md" :class="view === 'list' ? 'bg-ink-700 text-white' : 'text-zinc-500'" aria-label="Compact list" @click="view = 'list'"><i class="ri-list-check"></i></button>
        </div>
    </div>

    <EmptyState v-if="!items.length" icon="ri-bank-line" :title="search ? 'No match' : 'No data available'" :text="search ? `Nothing in ${current.label} matches “${search}”.` : 'There are no instruments in this category yet.'" class="card" />

    <!-- Cards with live mini charts (lazy) -->
    <div v-else-if="view === 'cards'" :key="active" class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
        <a v-for="b in items" :key="b.id" :href="b.url" class="card card-hover group flex flex-col overflow-hidden" @click="open($event, b)">
            <div class="flex items-center gap-3 p-4 pb-2">
                <CoinIcon :src="b.image" :symbol="b.code || b.name" size="h-10 w-10" />
                <div class="min-w-0 flex-1">
                    <p class="truncate font-semibold text-white">{{ b.name }}</p>
                    <p class="truncate font-mono text-[11px] text-zinc-500">{{ b.code }}</p>
                </div>
                <i class="ri-arrow-right-up-line text-lg text-zinc-600 transition group-hover:text-brand-300"></i>
            </div>
            <div class="pointer-events-none h-40">
                <TvEmbed widget="mini-symbol-overview" :config="{ symbol: b.code, dateRange: '1D', chartOnly: false, noTimeScale: false, autosize: true }" />
            </div>
        </a>
    </div>

    <!-- Compact list -->
    <div v-else class="card divide-y divide-white/[0.05] overflow-hidden">
        <a v-for="b in items" :key="b.id" :href="b.url" class="flex items-center gap-3 px-4 py-3.5 transition hover:bg-white/[0.02]" @click="open($event, b)">
            <CoinIcon :src="b.image" :symbol="b.code || b.name" size="h-10 w-10" />
            <div class="min-w-0 flex-1">
                <p class="truncate font-semibold text-white">{{ b.name }}</p>
                <p class="truncate text-xs text-zinc-500">{{ b.about || b.code }}</p>
            </div>
            <span class="hidden font-mono text-xs text-zinc-500 sm:block">{{ b.code }}</span>
            <i class="ri-arrow-right-s-line text-xl text-zinc-600"></i>
        </a>
    </div>

    <Modal :show="memberModal" title="Global Stock Membership" @close="memberModal = false">
        <div class="rounded-2xl border border-amber-400/20 bg-amber-400/[0.06] p-4 text-center">
            <i class="ri-vip-crown-2-fill text-4xl text-amber-300"></i>
            <p class="mt-2 font-mono text-3xl font-bold text-white">20 USDT</p>
            <p class="text-xs text-zinc-400">One-time payment from your USDT wallet</p>
        </div>
        <p class="mt-4 text-sm text-zinc-400">You need a Global Stock Membership to buy bonds.</p>
        <div class="mt-5 grid grid-cols-2 gap-2">
            <button class="btn-ghost" @click="memberModal = false">Not now</button>
            <button class="btn-primary" :disabled="buying" @click="buyMembership"><i :class="buying ? 'ri-loader-4-line animate-spin' : 'ri-check-line'"></i> Confirm</button>
        </div>
    </Modal>
</template>
