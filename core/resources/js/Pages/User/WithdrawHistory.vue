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

defineProps({ withdraws: Object });
const links = usePage().props.shell.links;

const { filters } = useFilters(['search']);
const columns = [
    { key: 'currency', label: 'Currency' },
    { key: 'method', label: 'Method' },
    { key: 'amount', label: 'Amount', align: 'right' },
    { key: 'status', label: 'Status' },
    { key: 'date', label: 'Initiated', align: 'right', wide: true },
];

const modal = reactive({ show: false, items: [], feedback: null });
const openDetails = (row) => Object.assign(modal, { show: true, items: row.details || [], feedback: row.feedback });
</script>

<template>
    <Head title="Withdraw history" />
    <PageHeader title="Withdraw history" subtitle="Your withdrawal requests and payouts" icon="ri-upload-2-line">
        <template #actions>
            <a :href="links.withdraw" class="btn-primary"><i class="ri-upload-2-line"></i> Withdraw</a>
        </template>
    </PageHeader>

    <DataList :rows="withdraws.data" :columns="columns" :meta="withdraws.meta" empty-icon="ri-upload-2-line" empty-title="No withdrawals yet">
        <template #toolbar><FilterBar :filters="filters" search="Search by transaction or currency" /></template>

        <template #cell-currency="{ row }">
            <div class="flex items-center gap-2.5">
                <CoinIcon :src="row.image" :symbol="row.currency" size="h-8 w-8" />
                <div>
                    <p class="font-semibold text-white">{{ row.currency }}</p>
                    <p class="text-[11px] text-zinc-500">{{ row.wallet }}</p>
                </div>
            </div>
        </template>
        <template #cell-method="{ row }">
            <p class="font-medium text-zinc-100">{{ row.method || '—' }}</p>
            <p class="font-mono text-[11px] text-zinc-500">{{ row.trx }}</p>
        </template>
        <template #cell-amount="{ row }">
            <p class="font-mono font-semibold text-white">{{ formatAmount(row.receive) }} <span class="text-xs font-normal text-zinc-500">{{ row.payout }}</span></p>
            <p class="font-mono text-[11px] text-zinc-500">{{ formatAmount(row.amount) }} − <span class="text-down">{{ formatAmount(row.charge) }}</span> fee</p>
        </template>
        <template #cell-status="{ row }"><Badge :tone="statusTone(row.status)" dot>{{ row.status }}</Badge></template>
        <template #cell-date="{ row }">
            <p class="text-xs text-zinc-300">{{ formatDateTime(row.date) }}</p>
            <p class="text-[11px] text-zinc-500">{{ timeAgo(row.date) }}</p>
        </template>
        <template #actions="{ row }">
            <button class="btn-ghost px-3 py-1.5 text-xs" @click="openDetails(row)"><i class="ri-eye-line"></i> Details</button>
        </template>
    </DataList>

    <DetailsModal :show="modal.show" title="Withdrawal details" :items="modal.items" :feedback="modal.feedback" @close="modal.show = false" />
</template>
