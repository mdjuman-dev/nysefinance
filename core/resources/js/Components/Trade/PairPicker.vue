<script setup>
import { computed, ref, watch } from 'vue';
import CoinIcon from '@/Components/CoinIcon.vue';
import { getJson } from '@/utils/http';
import { formatPrice } from '@/utils/format';

// Pair list from trade.pairs (same endpoint as the Blade sidebar), with favourites.
const props = defineProps({ urls: Object, markets: Array, current: String, inline: Boolean });
const emit = defineEmits(['close']);

const pairs = ref([]);
const favorites = ref([]);
const search = ref('');
const market = ref('');
const onlyFav = ref(false);
const loading = ref(false);

async function load() {
    loading.value = true;
    try {
        const res = await getJson(props.urls.pairs, { marketId: market.value || '', search: search.value || '' });
        pairs.value = res.pairs || [];
        favorites.value = res.favoritePairId || [];
    } catch {} finally {
        loading.value = false;
    }
}
load();
let t;
watch(search, () => {
    clearTimeout(t);
    t = setTimeout(load, 300);
});
watch(market, load);

const list = computed(() => (onlyFav.value ? pairs.value.filter((p) => favorites.value.includes(p.id)) : pairs.value));
const url = (sym) => props.urls.trade.replace('__SYMBOL__', sym);

async function toggleFav(p) {
    if (!props.urls.favorite) return (window.location.href = props.urls.login);
    try {
        await getJson(props.urls.favorite.replace('__SYMBOL__', p.symbol));
        favorites.value = favorites.value.includes(p.id) ? favorites.value.filter((i) => i !== p.id) : [...favorites.value, p.id];
    } catch {}
}
</script>

<template>
    <div class="flex h-full min-h-0 flex-col">
        <div class="space-y-2 p-3">
            <label class="relative block">
                <i class="ri-search-line pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-sm text-zinc-500"></i>
                <input v-model="search" type="search" placeholder="Search pair" class="w-full rounded-lg border border-white/[0.07] bg-white/[0.03] py-2 pr-3 pl-9 text-sm text-zinc-200 placeholder:text-zinc-500 focus:border-brand-500/50 focus:outline-none" />
            </label>
            <div class="flex gap-1 overflow-x-auto [scrollbar-width:none]">
                <button class="shrink-0 rounded-md px-2.5 py-1 text-xs font-semibold" :class="onlyFav ? 'bg-amber-400/15 text-amber-300' : 'text-zinc-500 hover:text-zinc-200'" @click="onlyFav = !onlyFav"><i class="ri-star-fill"></i></button>
                <button class="shrink-0 rounded-md px-2.5 py-1 text-xs font-semibold" :class="!market ? 'bg-white/[0.08] text-white' : 'text-zinc-500 hover:text-zinc-200'" @click="market = ''">All</button>
                <button v-for="m in markets" :key="m.id" class="shrink-0 rounded-md px-2.5 py-1 text-xs font-semibold" :class="market === m.id ? 'bg-white/[0.08] text-white' : 'text-zinc-500 hover:text-zinc-200'" @click="market = m.id">{{ m.name }}</button>
            </div>
        </div>
        <div class="grid grid-cols-[1fr_auto_auto] gap-x-3 px-3 pb-1 text-[10px] tracking-wider text-zinc-500 uppercase"><span>Pair</span><span class="text-right">Price</span><span class="w-12 text-right">1h</span></div>
        <div class="min-h-0 flex-1 overflow-y-auto" :class="inline ? 'max-h-[340px]' : ''">
            <div v-if="loading && !pairs.length" class="space-y-1 p-3"><div v-for="n in 8" :key="n" class="h-8 animate-pulse rounded bg-white/[0.03]"></div></div>
            <div v-for="p in list" :key="p.id" class="group grid grid-cols-[1fr_auto_auto] items-center gap-x-3 px-3 py-1.5 text-xs hover:bg-white/[0.03]" :class="p.symbol === current ? 'bg-brand-500/[0.06]' : ''">
                <span class="flex min-w-0 items-center gap-2">
                    <button class="text-zinc-600 hover:text-amber-300" :class="favorites.includes(p.id) ? 'text-amber-300' : ''" aria-label="Favourite" @click="toggleFav(p)"><i :class="favorites.includes(p.id) ? 'ri-star-fill' : 'ri-star-line'"></i></button>
                    <a :href="url(p.symbol)" class="flex min-w-0 items-center gap-2">
                        <CoinIcon :src="p.coin?.image_url" :symbol="p.coin?.symbol" size="h-5 w-5" />
                        <span class="truncate font-semibold text-zinc-100">{{ p.coin?.symbol }}<span class="font-normal text-zinc-500">/{{ p.market?.currency?.symbol }}</span></span>
                    </a>
                </span>
                <a :href="url(p.symbol)" class="text-right font-mono text-zinc-200">{{ formatPrice(p.market_data?.price) }}</a>
                <span class="w-12 text-right font-mono" :class="(p.market_data?.html_classes?.percent_change_1h || '') === 'up' ? 'text-up' : 'text-down'">{{ Number(p.market_data?.percent_change_1h || 0).toFixed(2) }}%</span>
            </div>
            <p v-if="!loading && !list.length" class="p-6 text-center text-xs text-zinc-500">No pairs found</p>
        </div>
    </div>
</template>
