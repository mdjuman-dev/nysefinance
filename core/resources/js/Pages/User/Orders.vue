<script setup>
import { computed, reactive, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import SegmentTabs from '@/Components/UI/SegmentTabs.vue';
import DataList from '@/Components/UI/DataList.vue';
import FilterBar from '@/Components/UI/FilterBar.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import { useFilters } from '@/composables/useFilters';
import { useToast } from '@/composables/useToast';
import { postJson, messageText } from '@/utils/http';
import { formatAmount, formatPrice } from '@/utils/format';
import { formatDateTime, orderStatusTone } from '@/utils/display';

const props = defineProps({ tab: String, orders: Object, urls: Object });
const toast = useToast();

const tabs = computed(() => [
    { label: 'Open', href: props.urls.open, active: props.tab === 'open' },
    { label: 'Completed', href: props.urls.completed, active: props.tab === 'completed' },
    { label: 'Canceled', href: props.urls.canceled, active: props.tab === 'canceled' },
    { label: 'All orders', href: props.urls.history, active: props.tab === 'history' },
]);

const { filters } = useFilters(['search', 'order_type', 'order_side']);
const selects = [
    { key: 'order_type', placeholder: 'All types', options: [{ value: 1, label: 'Limit' }, { value: 2, label: 'Market' }] },
    { key: 'order_side', placeholder: 'All sides', options: [{ value: 1, label: 'Buy' }, { value: 2, label: 'Sell' }] },
];

const columns = [
    { key: 'pair', label: 'Pair', wide: true },
    { key: 'side', label: 'Side / Type' },
    { key: 'amount', label: 'Amount', align: 'right' },
    { key: 'rate', label: 'Rate', align: 'right' },
    { key: 'total', label: 'Total', align: 'right' },
    { key: 'filled', label: 'Filled' },
    { key: 'status', label: 'Status' },
];

/* Edit amount / rate (existing JSON endpoint) */
const edit = reactive({ show: false, order: null, field: 'amount', value: '', busy: false });
function openEdit(order, field) {
    Object.assign(edit, { show: true, order, field, value: String(order[field]) });
}
async function saveEdit() {
    edit.busy = true;
    const res = await postJson(props.urls.update.replace('__ID__', edit.order.id), { update_filed: edit.field, [edit.field]: edit.value });
    edit.busy = false;
    if (res.success) {
        toast.success(messageText(res.message) || 'Order updated');
        edit.show = false;
        router.reload({ only: ['orders'], preserveScroll: true });
    } else {
        toast.error(messageText(res.message) || 'Could not update order');
    }
}
</script>

<template>
    <Head title="Orders" />
    <PageHeader title="Orders" subtitle="Track and manage your spot orders" icon="ri-file-list-3-line">
        <template #actions>
            <a :href="urls.trade" class="btn-primary"><i class="ri-add-line"></i> New order</a>
        </template>
    </PageHeader>

    <div class="mb-4"><SegmentTabs :tabs="tabs" /></div>

    <DataList :rows="orders.data" :columns="columns" :meta="orders.meta" empty-icon="ri-file-list-3-line" empty-title="No orders found" empty-text="Orders you place on the trade page will appear here.">
        <template #toolbar>
            <FilterBar :filters="filters" search="Search pair, coin or currency" :selects="selects" />
        </template>

        <template #cell-pair="{ row }">
            <p class="font-semibold text-white">{{ row.pair }}</p>
            <p class="text-xs text-zinc-500">{{ formatDateTime(row.date) }}</p>
        </template>
        <template #cell-side="{ row }">
            <span class="font-semibold capitalize" :class="row.side === 'buy' ? 'text-up' : 'text-down'">{{ row.side }}</span>
            <span class="text-zinc-500"> · {{ row.type }}</span>
        </template>
        <template #cell-amount="{ row }">
            <span class="inline-flex items-center gap-1.5 font-mono">
                {{ formatAmount(row.amount) }} <span class="text-xs text-zinc-500">{{ row.coin }}</span>
                <button v-if="row.status === 'open'" class="text-zinc-500 hover:text-brand-300" aria-label="Edit amount" @click="openEdit(row, 'amount')"><i class="ri-edit-line"></i></button>
            </span>
        </template>
        <template #cell-rate="{ row }">
            <span class="inline-flex items-center gap-1.5 font-mono">
                {{ formatPrice(row.rate) }}
                <button v-if="row.status === 'open'" class="text-zinc-500 hover:text-brand-300" aria-label="Edit rate" @click="openEdit(row, 'rate')"><i class="ri-edit-line"></i></button>
            </span>
        </template>
        <template #cell-total="{ row }">
            <span class="font-mono">{{ formatAmount(row.total) }}</span> <span class="text-xs text-zinc-500">{{ row.market }}</span>
            <p class="text-[11px] text-zinc-500">Fee {{ formatAmount(row.charge) }}</p>
        </template>
        <template #cell-filled="{ row }">
            <div class="flex items-center gap-2">
                <span class="h-1.5 w-16 overflow-hidden rounded-full bg-white/[0.06]"><span class="block h-full rounded-full bg-brand-500" :style="{ width: Math.min(row.filled, 100) + '%' }"></span></span>
                <span class="font-mono text-xs text-zinc-400">{{ Math.round(row.filled) }}%</span>
            </div>
        </template>
        <template #cell-status="{ row }">
            <Badge :tone="orderStatusTone(row.status)" class="capitalize">{{ row.status }}</Badge>
        </template>
    </DataList>

    <Modal :show="edit.show" :title="`Edit ${edit.field}`" @close="edit.show = false">
        <form class="space-y-4" @submit.prevent="saveEdit">
            <p class="text-sm text-zinc-400">{{ edit.order?.pair }} · <span class="capitalize">{{ edit.order?.side }}</span> order</p>
            <div>
                <label class="mb-2 block text-sm font-medium text-zinc-300 capitalize">New {{ edit.field }}</label>
                <input v-model="edit.value" type="number" step="any" min="0" class="field font-mono" required autofocus />
            </div>
            <button type="submit" class="btn-primary w-full py-3" :disabled="edit.busy">
                <i :class="edit.busy ? 'ri-loader-4-line animate-spin' : 'ri-check-line'"></i> Save changes
            </button>
        </form>
    </Modal>
</template>
