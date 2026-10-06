<script setup>
import { reactive } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import DataList from '@/Components/UI/DataList.vue';
import FilterBar from '@/Components/UI/FilterBar.vue';
import Badge from '@/Components/UI/Badge.vue';
import DetailsModal from '@/Components/UI/DetailsModal.vue';
import CoinIcon from '@/Components/CoinIcon.vue';
import { useFilters } from '@/composables/useFilters';
import { formatAmount, timeAgo } from '@/utils/format';
import { formatDateTime, statusTone } from '@/utils/display';

defineProps({ deposits: Object });
const links = usePage().props.shell.links;

const { filters } = useFilters(['search']);
const columns = [
    { key: 'currency', label: 'Currency' },
    { key: 'gateway', label: 'Gateway' },
    { key: 'amount', label: 'Amount', align: 'right' },
    { key: 'status', label: 'Status' },
    { key: 'date', label: 'Initiated', align: 'right', wide: true },
];

const modal = reactive({ show: false, items: [], feedback: null });
const openDetails = (row) => Object.assign(modal, { show: true, items: row.details || [], feedback: row.feedback });
</script>

<template>
    <Head title="Deposit history" />
    <PageHeader title="Deposit history" subtitle="All deposits made to your wallets" icon="ri-download-2-line">
        <template #actions>
            <a :href="links.deposit" class="btn-primary"><i class="ri-add-line"></i> Deposit</a>
        </template>
    </PageHeader>

    <DataList :rows="deposits.data" :columns="columns" :meta="deposits.meta" empty-icon="ri-download-2-line" empty-title="No deposits yet" empty-text="Fund your wallet to start trading.">
        <template #toolbar><FilterBar :filters="filters" search="Search by transaction or currency" /></template>
        <template #empty><a :href="links.deposit" class="btn-primary py-2 text-xs">Make a deposit</a></template>

        <template #cell-currency="{ row }">
            <div class="flex items-center gap-2.5">
                <CoinIcon :src="row.image" :symbol="row.currency" size="h-8 w-8" />
                <div>
                    <p class="font-semibold text-white">{{ row.currency }}</p>
                    <p class="text-[11px] text-zinc-500">{{ row.wallet }}</p>
                </div>
            </div>
        </template>
        <template #cell-gateway="{ row }">
            <p class="font-medium text-zinc-100">{{ row.gateway || '—' }}</p>
            <p class="font-mono text-[11px] text-zinc-500">{{ row.trx }}</p>
        </template>
        <template #cell-amount="{ row }">
            <p class="font-mono font-semibold text-white">{{ formatAmount(row.total) }}</p>
            <p class="font-mono text-[11px] text-zinc-500">{{ formatAmount(row.amount) }} + <span class="text-down">{{ formatAmount(row.charge) }}</span> fee</p>
        </template>
        <template #cell-status="{ row }"><Badge :tone="statusTone(row.status)" dot>{{ row.status }}</Badge></template>
        <template #cell-date="{ row }">
            <p class="text-xs text-zinc-300">{{ formatDateTime(row.date) }}</p>
            <p class="text-[11px] text-zinc-500">{{ timeAgo(row.date) }}</p>
        </template>
        <template #actions="{ row }">
            <button class="btn-ghost px-3 py-1.5 text-xs" :disabled="!row.details && !row.feedback" @click="openDetails(row)"><i class="ri-eye-line"></i> Details</button>
        </template>
    </DataList>

    <DetailsModal :show="modal.show" title="Deposit details" :items="modal.items" :feedback="modal.feedback" @close="modal.show = false" />
</template>
