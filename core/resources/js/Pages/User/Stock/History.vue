<script setup>
import { computed, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import SegmentTabs from '@/Components/UI/SegmentTabs.vue';
import DataList from '@/Components/UI/DataList.vue';
import Badge from '@/Components/UI/Badge.vue';
import { formatMoney } from '@/utils/format';
import { formatDateTime } from '@/utils/display';

const props = defineProps({ kind: { type: String, default: 'stock' }, tab: String, rows: Object, urls: Object });
// Shared by stock and bond history.
const noun = computed(() => (props.kind === 'bond' ? 'Bond' : 'Stock'));

const tabs = computed(() => [
    { label: 'Transactions', href: props.urls.transactions, active: props.tab === 'transactions' },
    { label: 'Interest', href: props.urls.interests, active: props.tab === 'interests' },
]);

// The controller splits filter_date on "-", so send slash-separated dates.
const params = new URLSearchParams(window.location.search);
const [initFrom, initTo] = (params.get('filter_date') || '').split('-');
const from = ref(initFrom ? initFrom.trim().replaceAll('/', '-') : '');
const to = ref(initTo ? initTo.trim().split(' ')[0].replaceAll('/', '-') : '');

function applyDates() {
    const query = {};
    if (from.value) query.filter_date = `${from.value.replaceAll('-', '/')}-${(to.value || new Date().toISOString().slice(0, 10)).replaceAll('-', '/')} 23:59:59`;
    router.get(window.location.pathname, query, { preserveState: true, preserveScroll: true, replace: true });
}
function clearDates() {
    from.value = '';
    to.value = '';
    applyDates();
}

const typeTone = (t) => ({ buy: 'success', sell: 'danger', exchange: 'info', interest: 'brand' })[t] || 'neutral';
const columns = computed(() => [
    { key: 'stock', label: noun.value, wide: true },
    ...(props.tab === 'transactions' ? [{ key: 'type', label: 'Type' }] : []),
    { key: 'amount', label: 'Amount', align: 'right' },
    { key: 'remark', label: 'Remark' },
    { key: 'date', label: 'Date', align: 'right' },
]);
</script>

<template>
    <Head :title="`${noun} history`" />
    <PageHeader :title="`${noun} history`" subtitle="Purchases, sales and interest payouts" icon="ri-file-history-line">
        <template #actions>
            <a :href="urls.my" class="btn-ghost"><i class="ri-briefcase-4-line"></i> My {{ noun.toLowerCase() }}s</a>
        </template>
    </PageHeader>

    <div class="mb-4"><SegmentTabs :tabs="tabs" /></div>

    <DataList :rows="rows.data" :columns="columns" :meta="rows.meta" empty-icon="ri-file-history-line" :empty-title="tab === 'interests' ? 'No interest payouts yet' : `No ${noun.toLowerCase()} transactions yet`">
        <template #toolbar>
            <form class="flex flex-col gap-2 border-b border-white/[0.05] p-3 sm:flex-row sm:items-end sm:p-4" @submit.prevent="applyDates">
                <label class="flex-1">
                    <span class="mb-1 block text-[11px] font-medium tracking-wide text-zinc-500 uppercase">From</span>
                    <input v-model="from" type="date" class="w-full rounded-lg border border-white/[0.07] bg-ink-850 px-3 py-2 text-sm text-zinc-200 [color-scheme:dark] focus:outline-none" />
                </label>
                <label class="flex-1">
                    <span class="mb-1 block text-[11px] font-medium tracking-wide text-zinc-500 uppercase">To</span>
                    <input v-model="to" type="date" class="w-full rounded-lg border border-white/[0.07] bg-ink-850 px-3 py-2 text-sm text-zinc-200 [color-scheme:dark] focus:outline-none" />
                </label>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1 py-2"><i class="ri-filter-3-line"></i> Filter</button>
                    <button v-if="from" type="button" class="btn-ghost py-2" @click="clearDates">Clear</button>
                </div>
            </form>
        </template>

        <template #cell-stock="{ row }"><span class="font-medium text-white">{{ row.stock || 'N/A' }}</span></template>
        <template #cell-type="{ row }"><Badge :tone="typeTone(row.type)" class="uppercase">{{ row.type }}</Badge></template>
        <template #cell-amount="{ row }">
            <span class="font-mono font-semibold" :class="tab === 'interests' ? 'text-up' : 'text-white'">${{ formatMoney(row.amount) }}</span>
        </template>
        <template #cell-remark="{ row }"><span class="text-sm text-zinc-400">{{ row.remark || '—' }}</span></template>
        <template #cell-date="{ row }"><span class="text-xs text-zinc-400">{{ formatDateTime(row.date) }}</span></template>
    </DataList>
</template>
