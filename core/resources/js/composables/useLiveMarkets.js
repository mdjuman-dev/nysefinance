import { onBeforeUnmount, onMounted, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

// Keeps the server-rendered market snapshot fresh by polling /crypto/list.
export function useLiveMarkets(initial, { limit = initial.length, interval = 30000 } = {}) {
    const markets = ref(initial);
    const url = usePage().props.routes.cryptoList;
    let timer;

    async function refresh() {
        try {
            const res = await fetch(`${url}?limit=${limit}`, { headers: { Accept: 'application/json' } });
            const json = await res.json();
            if (!json.success) return;
            markets.value = json.currencies.map((c) => ({
                name: c.name,
                symbol: c.symbol,
                image: c.image_url,
                price: Number(c.market_data?.price ?? c.rate),
                change24h: Number(c.market_data?.percent_change_24h ?? 0),
                marketCap: Number(c.market_data?.market_cap ?? 0),
                volume: Number(c.market_data?.volume_24h ?? 0),
            }));
        } catch {
            // keep the last good snapshot
        }
    }

    onMounted(() => (timer = setInterval(refresh, interval)));
    onBeforeUnmount(() => clearInterval(timer));

    return { markets, refresh };
}
