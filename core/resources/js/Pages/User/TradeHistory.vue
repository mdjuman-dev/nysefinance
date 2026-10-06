<script setup>
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import DataList from '@/Components/UI/DataList.vue';
import FilterBar from '@/Components/UI/FilterBar.vue';
import { useFilters } from '@/composables/useFilters';
import { formatAmount, formatPrice } from '@/utils/format';
import { formatDateTime } from '@/utils/display';

defineProps({ trades: Object });

const { filters } = useFilters(['search', 'trade_side']);
const selects = [{ key: 'trade_side', placeholder: 'All sides', options: [{ value: 1, label: 'Buy' }, { value: 2, label: 'Sell' }] }];

const columns = [
    { key: 'pair', label: 'Pair', wide: true },
    { key: 'side', label: 'Side' },
    { key: 'rate', label: 'Rate', align: 'right' },
    { key: 'amount', label: 'Amount', align: 'right' },
    { key: 'date', label: 'Trade date', align: 'right' },
];
</script>

<template>
    <Head title="Trade history" />
    <PageHeader title="Trade history" subtitle="Every fill from your spot orders" icon="ri-history-line" />

    <DataList :rows="trades.data" :columns="columns" :meta="trades.meta" empty-icon="ri-history-line" empty-title="No trades yet" empty-text="Filled orders will show up here.">
        <template #toolbar>
            <FilterBar :filters="filters" search="Search pair, coin or currency" :selects="selects" />
        </template>
        <template #cell-pair="{ row }">
            <p class="font-semibold text-white">{{ row.pair }}</p>
            <p class="text-xs text-zinc-500">Ordered {{ formatDateTime(row.orderDate) }}</p>
        </template>
        <template #cell-side="{ row }">
            <span class="inline-flex items-center gap-1 font-semibold capitalize" :class="row.side === 'buy' ? 'text-up' : 'text-down'">
                <i :class="row.side === 'buy' ? 'ri-arrow-left-down-line' : 'ri-arrow-right-up-line'"></i>{{ row.side }}
            </span>
        </template>
        <template #cell-rate="{ row }"><span class="font-mono">{{ formatPrice(row.rate) }}</span> <span class="text-xs text-zinc-500">{{ row.market }}</span></template>
        <template #cell-amount="{ row }"><span class="font-mono">{{ formatAmount(row.amount) }}</span> <span class="text-xs text-zinc-500">{{ row.coin }}</span></template>
        <template #cell-date="{ row }"><span class="text-xs text-zinc-400">{{ formatDateTime(row.date) }}</span></template>
    </DataList>
</template>
