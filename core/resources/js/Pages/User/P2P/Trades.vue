<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import SegmentTabs from '@/Components/UI/SegmentTabs.vue';
import DataList from '@/Components/UI/DataList.vue';
import Badge from '@/Components/UI/Badge.vue';
import CoinIcon from '@/Components/CoinIcon.vue';
import { formatAmount, timeAgo } from '@/utils/format';
import { statusTone } from '@/utils/display';

defineProps({ scope: String, trades: Object, tabs: Array, urls: Object });

const columns = [
    { key: 'uid', label: 'Order', wide: true },
    { key: 'parties', label: 'Buyer / Seller' },
    { key: 'amount', label: 'Amount', align: 'right' },
    { key: 'status', label: 'Status', align: 'right' },
];
</script>

<template>
    <Head :title="`${scope === 'running' ? 'Running' : 'Completed'} P2P orders`" />
    <PageHeader title="P2P orders" subtitle="Your trades with other users" icon="ri-exchange-2-line">
        <template #actions>
            <Link :href="urls.center" class="btn-ghost"><i class="ri-store-2-line"></i> P2P center</Link>
            <Link :href="urls.market" class="btn-primary"><i class="ri-add-line"></i> New trade</Link>
        </template>
    </PageHeader>

    <div class="mb-4"><SegmentTabs :tabs="tabs" /></div>

    <DataList :rows="trades.data" :columns="columns" :meta="trades.meta" empty-icon="ri-exchange-2-line" :empty-title="scope === 'running' ? 'No running orders' : 'No completed orders'">
        <template #cell-uid="{ row }">
            <Link :href="row.url" class="flex min-w-0 items-center gap-3">
                <CoinIcon :src="row.assetImage" :symbol="row.asset" size="h-9 w-9" />
                <div class="min-w-0">
                    <p class="flex items-center gap-2 text-sm font-semibold text-white">
                        <span :class="row.side === 'Buy' ? 'text-up' : 'text-down'">{{ row.side }}</span> {{ row.asset }}
                    </p>
                    <p class="font-mono text-[11px] text-zinc-500">#{{ row.uid }} · {{ timeAgo(row.date) }}</p>
                </div>
            </Link>
        </template>
        <template #cell-parties="{ row }">
            <p class="text-sm text-zinc-200">{{ row.buyer }} <span class="text-zinc-600">→</span> {{ row.seller }}</p>
            <p class="text-xs text-zinc-500">{{ row.method }}</p>
        </template>
        <template #cell-amount="{ row }">
            <p class="font-mono text-sm font-semibold text-white">{{ formatAmount(row.fiatAmount) }} {{ row.fiat }}</p>
            <p class="font-mono text-xs text-zinc-500">{{ formatAmount(row.assetAmount) }} {{ row.asset }}</p>
        </template>
        <template #cell-status="{ row }">
            <div class="flex items-center justify-end gap-2">
                <Badge :tone="statusTone(row.status)" dot>{{ row.status }}</Badge>
                <Link :href="row.url" class="grid h-8 w-8 place-items-center rounded-lg text-zinc-400 hover:bg-white/[0.06] hover:text-white" aria-label="Open trade"><i class="ri-arrow-right-s-line"></i></Link>
            </div>
        </template>
    </DataList>
</template>
