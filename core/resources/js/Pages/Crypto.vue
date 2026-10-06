<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import CoinIcon from '@/Components/CoinIcon.vue';
import PageHero from '@/Components/PageHero.vue';
import { formatCompact, formatPercent, formatPrice, trendClass } from '@/utils/format';

const props = defineProps({ listUrl: String });
const site = computed(() => usePage().props.site);

const PAGE_SIZE = 20;
const rows = ref([]);
const total = ref(0);
const search = ref('');
const loading = ref(false);
const sortKey = ref('rank');
const sortDir = ref('asc');

async function load({ append = false } = {}) {
    loading.value = true;
    const params = new URLSearchParams({ skip: append ? rows.value.length : 0, limit: PAGE_SIZE });
    if (search.value.trim()) params.set('search', search.value.trim());
    try {
        const res = await fetch(`${props.listUrl}?${params}`, { headers: { Accept: 'application/json' } });
        const json = await res.json();
        const items = (json.currencies || []).map((c) => ({
            rank: c.ranking,
            name: c.name,
            symbol: c.symbol,
            image: c.image_url,
            price: Number(c.market_data?.price ?? c.rate),
            change1h: Number(c.market_data?.percent_change_1h ?? 0),
            change24h: Number(c.market_data?.percent_change_24h ?? 0),
            change7d: Number(c.market_data?.percent_change_7d ?? 0),
            marketCap: Number(c.market_data?.market_cap ?? 0),
        }));
        rows.value = append ? [...rows.value, ...items] : items;
        total.value = json.total ?? rows.value.length;
    } finally {
        loading.value = false;
    }
}

let debounce;
watch(search, () => {
    clearTimeout(debounce);
    debounce = setTimeout(() => load(), 300);
});
onMounted(load);

const sorted = computed(() => {
    const dir = sortDir.value === 'asc' ? 1 : -1;
    return [...rows.value].sort((a, b) => ((a[sortKey.value] ?? 0) - (b[sortKey.value] ?? 0)) * dir);
});

function sortBy(key) {
    if (sortKey.value === key) sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
    else {
        sortKey.value = key;
        sortDir.value = key === 'rank' ? 'asc' : 'desc';
    }
}

const columns = [
    { key: 'price', label: 'Price' },
    { key: 'change1h', label: '1h', hide: 'hidden md:table-cell' },
    { key: 'change24h', label: '24h' },
    { key: 'change7d', label: '7d', hide: 'hidden md:table-cell' },
    { key: 'marketCap', label: 'Market cap', hide: 'hidden lg:table-cell' },
];

const topGainers = computed(() => [...rows.value].sort((a, b) => b.change24h - a.change24h).slice(0, 3));
const topLosers = computed(() => [...rows.value].sort((a, b) => a.change24h - b.change24h).slice(0, 3));
const largest = computed(() => [...rows.value].sort((a, b) => b.marketCap - a.marketCap).slice(0, 3));
const highlights = computed(() => [
    { title: 'Top gainers', icon: 'ri-arrow-right-up-line text-up', items: topGainers.value },
    { title: 'Top losers', icon: 'ri-arrow-right-down-line text-down', items: topLosers.value },
    { title: 'Largest by market cap', icon: 'ri-vip-crown-line text-amber-400', items: largest.value },
]);
</script>

<template>
    <Head title="Markets" />

    <PageHero eyebrow="Markets" title="Cryptocurrency prices" :subtitle="`Explore the cryptocurrencies available on ${site.name}, with live prices and market data.`" />

    <section class="container-x">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <div v-for="h in highlights" :key="h.title" class="card p-5">
                <h3 class="flex items-center gap-2 text-sm font-semibold text-zinc-300"><i :class="h.icon" class="text-lg"></i> {{ h.title }}</h3>
                <ul class="mt-4 space-y-3">
                    <li v-for="m in h.items" :key="m.symbol" class="flex items-center gap-3">
                        <CoinIcon :src="m.image" :symbol="m.symbol" size="h-7 w-7" />
                        <span class="flex-1 truncate text-sm font-medium text-zinc-200">{{ m.symbol }}</span>
                        <span class="font-mono text-sm text-zinc-300">${{ formatPrice(m.price) }}</span>
                        <span class="w-16 text-right font-mono text-xs font-semibold" :class="trendClass(m.change24h)">{{ formatPercent(m.change24h) }}</span>
                    </li>
                    <li v-if="!h.items.length" class="h-24 animate-pulse rounded-lg bg-white/[0.03]"></li>
                </ul>
            </div>
        </div>
    </section>

    <section class="container-x mt-8">
        <div class="card overflow-hidden">
            <div class="flex flex-col gap-4 border-b border-white/[0.06] p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-white">All cryptocurrencies</h2>
                    <p class="text-xs text-zinc-500">{{ total }} assets listed</p>
                </div>
                <label class="relative block w-full sm:w-72">
                    <i class="ri-search-line pointer-events-none absolute top-1/2 left-3.5 -translate-y-1/2 text-zinc-500"></i>
                    <input v-model="search" type="search" placeholder="Search by name or symbol" class="field py-2.5 pl-10" />
                </label>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] tracking-wider text-zinc-500 uppercase">
                            <th class="px-5 py-3 font-medium">
                                <button class="inline-flex items-center gap-1 hover:text-zinc-300" @click="sortBy('rank')">
                                    # Asset <i v-if="sortKey === 'rank'" :class="sortDir === 'asc' ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'"></i>
                                </button>
                            </th>
                            <th v-for="c in columns" :key="c.key" class="px-5 py-3 text-right font-medium" :class="c.hide">
                                <button class="inline-flex items-center gap-1 hover:text-zinc-300" @click="sortBy(c.key)">
                                    {{ c.label }} <i v-if="sortKey === c.key" :class="sortDir === 'asc' ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04]">
                        <tr v-for="m in sorted" :key="m.symbol" class="transition hover:bg-white/[0.02]">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 font-mono text-xs text-zinc-500">{{ m.rank }}</span>
                                    <CoinIcon :src="m.image" :symbol="m.symbol" />
                                    <div class="min-w-0">
                                        <p class="font-semibold text-white">{{ m.name }}</p>
                                        <p class="text-xs text-zinc-500">{{ m.symbol }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-semibold text-zinc-100">${{ formatPrice(m.price) }}</td>
                            <td class="hidden px-5 py-4 text-right font-mono text-xs md:table-cell" :class="trendClass(m.change1h)">{{ formatPercent(m.change1h) }}</td>
                            <td class="px-5 py-4 text-right">
                                <span class="inline-block min-w-[4.5rem] rounded-md px-2 py-1 text-center font-mono text-xs font-semibold" :class="[trendClass(m.change24h), m.change24h >= 0 ? 'bg-up/10' : 'bg-down/10']">
                                    {{ formatPercent(m.change24h) }}
                                </span>
                            </td>
                            <td class="hidden px-5 py-4 text-right font-mono text-xs md:table-cell" :class="trendClass(m.change7d)">{{ formatPercent(m.change7d) }}</td>
                            <td class="hidden px-5 py-4 text-right font-mono text-zinc-300 lg:table-cell">${{ formatCompact(m.marketCap) }}</td>
                        </tr>
                        <template v-if="loading && !rows.length">
                            <tr v-for="n in 8" :key="'s' + n">
                                <td colspan="6" class="px-5 py-4"><div class="h-9 animate-pulse rounded-lg bg-white/[0.03]"></div></td>
                            </tr>
                        </template>
                        <tr v-if="!loading && !rows.length">
                            <td colspan="6" class="px-5 py-16 text-center text-zinc-500">
                                <i class="ri-search-eye-line block text-3xl"></i>
                                No assets match “{{ search }}”.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="rows.length < total" class="border-t border-white/[0.06] p-5 text-center">
                <button class="btn-ghost" :disabled="loading" @click="load({ append: true })">
                    <i :class="loading ? 'ri-loader-4-line animate-spin' : 'ri-add-line'"></i> Load more
                </button>
            </div>
        </div>
    </section>
</template>
