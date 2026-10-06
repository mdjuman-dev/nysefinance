<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import SegmentTabs from '@/Components/UI/SegmentTabs.vue';
import DataList from '@/Components/UI/DataList.vue';
import { formatMoney } from '@/utils/format';
import { formatDate } from '@/utils/display';

const props = defineProps({ tabs: Array, frozen: Boolean, totals: Object, interests: Object });

const cards = computed(() => [
    { label: 'Total invested', value: props.totals.buy, icon: 'ri-arrow-down-circle-line', cls: 'text-sky-300 bg-sky-400/10' },
    { label: 'Total sold', value: props.totals.sell, icon: 'ri-arrow-up-circle-line', cls: 'text-down bg-down/10' },
    { label: 'Total interest', value: props.totals.interest, icon: 'ri-coins-line', cls: 'text-up bg-up/10' },
]);
const columns = [
    { key: 'name', label: 'Copy trade', wide: true },
    { key: 'amount', label: 'Interest', align: 'right' },
    { key: 'date', label: 'Date', align: 'right' },
];
</script>

<template>
    <Head title="Copy wallet" />
    <div class="mb-4"><SegmentTabs :tabs="tabs" /></div>

    <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div v-for="c in cards" :key="c.label" class="card flex items-center gap-4 p-5">
            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl text-2xl" :class="c.cls"><i :class="c.icon"></i></span>
            <div>
                <p class="text-xs text-zinc-500">{{ c.label }}</p>
                <p class="font-mono text-xl font-bold text-white">${{ formatMoney(c.value) }}</p>
            </div>
        </div>
    </div>

    <h2 class="mb-3 font-semibold text-white">Interest payouts</h2>
    <DataList :rows="interests.data" :columns="columns" :meta="interests.meta" empty-icon="ri-coins-line" empty-title="No interest payouts yet">
        <template #cell-name="{ row }">
            <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-up/10 text-up"><i class="ri-coins-line"></i></span>
                <span class="font-medium text-white">{{ row.name || 'Copy trade' }}</span>
            </div>
        </template>
        <template #cell-amount="{ row }"><span class="font-mono font-semibold text-up">+${{ formatMoney(row.amount) }}</span></template>
        <template #cell-date="{ row }"><span class="text-xs text-zinc-400">{{ formatDate(row.date) }}</span></template>
    </DataList>
</template>
