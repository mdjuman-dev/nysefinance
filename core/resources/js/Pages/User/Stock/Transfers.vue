<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import DataList from '@/Components/UI/DataList.vue';
import { formatMoney } from '@/utils/format';
import { formatDateTime } from '@/utils/display';

defineProps({ rows: Object, totals: Object, urls: Object });

const columns = [
    { key: 'amount', label: 'Amount', wide: true },
    { key: 'charge', label: 'Charge', align: 'right' },
    { key: 'date', label: 'Date', align: 'right' },
];
</script>

<template>
    <Head title="Stock transfers" />
    <PageHeader title="Stock transfers" subtitle="Transfers from your stock wallet" icon="ri-arrow-left-right-line">
        <template #actions>
            <Link :href="urls.wallet" class="btn-ghost"><i class="ri-wallet-3-line"></i> Stock wallet</Link>
        </template>
    </PageHeader>

    <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div class="card flex items-center gap-4 p-5">
            <span class="grid h-12 w-12 place-items-center rounded-2xl bg-sky-400/10 text-2xl text-sky-300"><i class="ri-send-plane-line"></i></span>
            <div><p class="text-xs text-zinc-500">Total transferred</p><p class="font-mono text-xl font-bold text-white">${{ formatMoney(totals.amount) }}</p></div>
        </div>
        <div class="card flex items-center gap-4 p-5">
            <span class="grid h-12 w-12 place-items-center rounded-2xl bg-down/10 text-2xl text-down"><i class="ri-percent-line"></i></span>
            <div><p class="text-xs text-zinc-500">Total charges</p><p class="font-mono text-xl font-bold text-white">${{ formatMoney(totals.charge) }}</p></div>
        </div>
    </div>

    <DataList :rows="rows.data" :columns="columns" :meta="rows.meta" empty-icon="ri-arrow-left-right-line" empty-title="No transfers yet">
        <template #cell-amount="{ row }">
            <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-sky-400/10 text-sky-300"><i class="ri-arrow-right-up-line"></i></span>
                <span class="font-mono font-semibold text-white">${{ formatMoney(row.amount) }}</span>
            </div>
        </template>
        <template #cell-charge="{ row }"><span class="font-mono text-down">${{ formatMoney(row.charge) }}</span></template>
        <template #cell-date="{ row }"><span class="text-xs text-zinc-400">{{ formatDateTime(row.date) }}</span></template>
    </DataList>
</template>
