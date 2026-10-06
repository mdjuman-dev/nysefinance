<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import DataList from '@/Components/UI/DataList.vue';
import CoinIcon from '@/Components/CoinIcon.vue';
import { formatMoney } from '@/utils/format';
import { formatDateTime } from '@/utils/display';

defineProps({ total: Number, rows: Object, urls: Object });

const columns = [
    { key: 'name', label: 'Stock', wide: true },
    { key: 'remark', label: 'Remark' },
    { key: 'amount', label: 'Interest', align: 'right' },
    { key: 'date', label: 'Time', align: 'right' },
];
const today = new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
</script>

<template>
    <Head title="Daily interest" />
    <PageHeader title="Today's interest" :subtitle="today" icon="ri-coins-line">
        <template #actions>
            <Link :href="urls.history" class="btn-ghost"><i class="ri-history-line"></i> All interest</Link>
        </template>
    </PageHeader>

    <section class="card relative mb-5 overflow-hidden p-6">
        <div class="pointer-events-none absolute -top-20 -right-16 h-56 w-56 rounded-full bg-up/15 blur-3xl"></div>
        <p class="text-sm text-zinc-400">Earned today</p>
        <p class="mt-1 font-mono text-4xl font-bold text-up">+${{ formatMoney(total) }}</p>
        <p class="mt-1 text-xs text-zinc-500">{{ rows.meta.total }} payout{{ rows.meta.total === 1 ? '' : 's' }} from your stock holdings</p>
    </section>

    <DataList :rows="rows.data" :columns="columns" :meta="rows.meta" empty-icon="ri-coins-line" empty-title="No interest yet today" empty-text="Interest from your stocks is paid out daily.">
        <template #cell-name="{ row }">
            <div class="flex min-w-0 items-center gap-3">
                <CoinIcon :src="row.image" :symbol="row.name" size="h-9 w-9" />
                <span class="truncate font-medium text-white">{{ row.name }}</span>
            </div>
        </template>
        <template #cell-remark="{ row }"><span class="text-sm text-zinc-400">{{ row.remark || '—' }}</span></template>
        <template #cell-amount="{ row }"><span class="font-mono font-semibold text-up">+${{ formatMoney(row.amount, 4) }}</span></template>
        <template #cell-date="{ row }"><span class="text-xs text-zinc-400">{{ formatDateTime(row.date) }}</span></template>
    </DataList>
</template>
