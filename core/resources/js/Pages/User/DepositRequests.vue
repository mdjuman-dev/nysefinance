<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import DataList from '@/Components/UI/DataList.vue';
import Badge from '@/Components/UI/Badge.vue';
import { formatAmount, timeAgo } from '@/utils/format';
import { formatDateTime, statusTone } from '@/utils/display';

defineProps({ deposits: Object });
const links = usePage().props.shell.links;

const columns = [
    { key: 'amount', label: 'Amount' },
    { key: 'charge', label: 'Charge', align: 'right' },
    { key: 'status', label: 'Status' },
    { key: 'date', label: 'Requested', align: 'right' },
];
</script>

<template>
    <Head title="Deposit requests" />
    <PageHeader title="Deposit requests" subtitle="Deposits waiting for confirmation" icon="ri-inbox-archive-line">
        <template #actions>
            <a :href="links.deposit" class="btn-primary"><i class="ri-add-line"></i> New deposit</a>
        </template>
    </PageHeader>

    <DataList :rows="deposits.data" :columns="columns" :meta="deposits.meta" empty-icon="ri-inbox-archive-line" empty-title="No deposit requests">
        <template #cell-amount="{ row }">
            <span class="font-mono font-semibold text-white">{{ formatAmount(row.amount) }}</span> <span class="text-xs text-zinc-500">{{ row.currency }}</span>
        </template>
        <template #cell-charge="{ row }"><span class="font-mono text-zinc-300">{{ formatAmount(row.charge) }}</span></template>
        <template #cell-status="{ row }"><Badge :tone="statusTone(row.status)" dot>{{ row.status }}</Badge></template>
        <template #cell-date="{ row }">
            <p class="text-xs text-zinc-300">{{ formatDateTime(row.date) }}</p>
            <p class="text-[11px] text-zinc-500">{{ timeAgo(row.date) }}</p>
        </template>
        <template #actions="{ row }">
            <a v-if="row.viewUrl" :href="row.viewUrl" target="_blank" class="btn-ghost px-3 py-1.5 text-xs"><i class="ri-external-link-line"></i> Open</a>
            <span v-else class="btn-ghost pointer-events-none px-3 py-1.5 text-xs opacity-40"><i class="ri-external-link-line"></i> Open</span>
        </template>
    </DataList>
</template>
