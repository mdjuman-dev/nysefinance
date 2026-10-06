<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import DataList from '@/Components/UI/DataList.vue';
import FilterBar from '@/Components/UI/FilterBar.vue';
import CoinIcon from '@/Components/CoinIcon.vue';
import { useFilters } from '@/composables/useFilters';
import { formatAmount } from '@/utils/format';
import { formatDateTime } from '@/utils/display';
import { timeAgo } from '@/utils/format';

const props = defineProps({ transactions: Object, currencies: Array, remarks: Array });

const { filters } = useFilters(['search', 'symbol', 'trx_type', 'remark']);
const selects = computed(() => [
    { key: 'symbol', placeholder: 'All currencies', options: props.currencies.map((c) => ({ value: c, label: c })) },
    { key: 'trx_type', placeholder: 'In & out', options: [{ value: '+', label: 'Incoming (+)' }, { value: '-', label: 'Outgoing (−)' }] },
    { key: 'remark', placeholder: 'Any remark', options: props.remarks },
]);

const columns = [
    { key: 'details', label: 'Transaction', wide: true },
    { key: 'currency', label: 'Wallet' },
    { key: 'amount', label: 'Amount', align: 'right' },
    { key: 'balance', label: 'Balance after', align: 'right' },
    { key: 'date', label: 'Date', align: 'right' },
];
</script>

<template>
    <Head title="Transactions" />
    <PageHeader title="Transactions" subtitle="Every credit and debit across your wallets" icon="ri-arrow-left-right-line" />

    <DataList :rows="transactions.data" :columns="columns" :meta="transactions.meta" empty-icon="ri-arrow-left-right-line" empty-title="No transactions found">
        <template #toolbar>
            <FilterBar :filters="filters" search="Search by transaction number" :selects="selects" />
        </template>

        <template #card="{ row }">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl text-lg" :class="row.type === '+' ? 'bg-up/10 text-up' : 'bg-down/10 text-down'">
                    <i :class="row.type === '+' ? 'ri-arrow-down-line' : 'ri-arrow-up-line'"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-zinc-100">{{ row.details }}</p>
                    <p class="truncate text-xs text-zinc-500">{{ timeAgo(row.date) }} · {{ row.currency }} {{ row.wallet }}</p>
                </div>
                <div class="text-right">
                    <p class="font-mono text-sm font-semibold" :class="row.type === '+' ? 'text-up' : 'text-down'">{{ row.type }}{{ formatAmount(row.amount) }}</p>
                    <p class="font-mono text-[11px] text-zinc-500">Bal {{ formatAmount(row.balance) }}</p>
                </div>
            </div>
            <p class="mt-2 font-mono text-[11px] text-zinc-600">{{ row.trx }}</p>
        </template>

        <template #cell-details="{ row }">
            <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl" :class="row.type === '+' ? 'bg-up/10 text-up' : 'bg-down/10 text-down'">
                    <i :class="row.type === '+' ? 'ri-arrow-down-line' : 'ri-arrow-up-line'"></i>
                </span>
                <div class="min-w-0">
                    <p class="max-w-xs truncate font-medium text-zinc-100">{{ row.details }}</p>
                    <p class="font-mono text-xs text-zinc-500">{{ row.trx }}</p>
                </div>
            </div>
        </template>
        <template #cell-currency="{ row }">
            <div class="flex items-center gap-2">
                <CoinIcon :src="row.image" :symbol="row.currency" size="h-6 w-6" />
                <div>
                    <p class="text-sm font-semibold text-white">{{ row.currency }}</p>
                    <p class="text-[11px] text-zinc-500">{{ row.wallet }}</p>
                </div>
            </div>
        </template>
        <template #cell-amount="{ row }">
            <span class="font-mono font-semibold" :class="row.type === '+' ? 'text-up' : 'text-down'">{{ row.type }}{{ formatAmount(row.amount) }}</span>
        </template>
        <template #cell-balance="{ row }"><span class="font-mono text-zinc-300">{{ formatAmount(row.balance) }}</span></template>
        <template #cell-date="{ row }">
            <p class="text-xs text-zinc-300">{{ formatDateTime(row.date) }}</p>
            <p class="text-[11px] text-zinc-500">{{ timeAgo(row.date) }}</p>
        </template>
    </DataList>
</template>
