<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import DataList from '@/Components/UI/DataList.vue';
import Badge from '@/Components/UI/Badge.vue';
import { formatAmount, formatMoney, timeAgo } from '@/utils/format';
import { statusTone } from '@/utils/display';

const props = defineProps({ isAgent: Boolean, widget: Object, trades: Array, urls: Object });

const stats = computed(() => [
    { label: 'Running trades', value: props.widget.runningTrade, icon: 'ri-loader-2-line', cls: 'bg-amber-400/10 text-amber-300', href: props.urls.running },
    { label: 'Completed trades', value: props.widget.completedTrade, icon: 'ri-checkbox-circle-line', cls: 'bg-up/10 text-up', href: props.urls.completed },
    { label: 'Total trades', value: props.widget.totalTrade, icon: 'ri-bar-chart-2-line', cls: 'bg-sky-400/10 text-sky-300', href: props.urls.completed },
    { label: 'Active ads', value: props.widget.activeAd, icon: 'ri-megaphone-line', cls: 'bg-up/10 text-up', href: props.urls.ads },
    { label: 'Inactive ads', value: props.widget.inactiveAd, icon: 'ri-pause-circle-line', cls: 'bg-down/10 text-down', href: props.urls.ads },
    { label: 'Total ads', value: props.widget.totalAd, icon: 'ri-stack-line', cls: 'bg-violet-400/10 text-violet-300', href: props.urls.ads },
]);

const positiveRate = computed(() => (props.widget.feedback ? Math.round((props.widget.positive / props.widget.feedback) * 100) : 0));

const links = computed(() => [
    { label: 'Running orders', icon: 'ri-loader-2-line', href: props.urls.running },
    { label: 'Completed orders', icon: 'ri-check-double-line', href: props.urls.completed },
    { label: 'My ads', icon: 'ri-megaphone-line', href: props.urls.ads },
    { label: 'New ad', icon: 'ri-add-circle-line', href: props.urls.newAd },
    { label: 'Payment methods', icon: 'ri-bank-card-line', href: props.urls.methods },
    { label: 'Feedback', icon: 'ri-chat-smile-2-line', href: props.urls.feedback },
]);

const columns = [
    { key: 'uid', label: 'Order', wide: true },
    { key: 'parties', label: 'Buyer / Seller' },
    { key: 'price', label: 'Price', align: 'right' },
    { key: 'amount', label: 'Amount', align: 'right' },
    { key: 'status', label: 'Status' },
];
</script>

<template>
    <Head title="P2P center" />
    <PageHeader title="P2P center" subtitle="Manage your P2P trades, ads and payment methods" icon="ri-store-2-line">
        <template #actions>
            <Link :href="urls.market" class="btn-ghost"><i class="ri-team-line"></i> P2P market</Link>
            <a v-if="isAgent" :href="urls.newAd" class="btn-primary"><i class="ri-add-line"></i> Post ad</a>
        </template>
    </PageHeader>

    <!-- Quick links -->
    <div class="-mx-4 mb-5 overflow-x-auto px-4 [scrollbar-width:none] sm:mx-0 sm:px-0">
        <div class="flex min-w-max gap-2">
            <a v-for="l in links" :key="l.label" :href="l.href" class="flex items-center gap-2 rounded-xl border border-white/[0.07] bg-ink-900/70 px-3.5 py-2.5 text-sm text-zinc-300 transition hover:border-brand-500/30 hover:text-white">
                <i :class="l.icon" class="text-brand-400"></i>{{ l.label }}
            </a>
        </div>
    </div>

    <template v-if="isAgent">
        <div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            <a v-for="s in stats" :key="s.label" :href="s.href" class="card card-hover p-4">
                <span class="grid h-9 w-9 place-items-center rounded-lg text-lg" :class="s.cls"><i :class="s.icon"></i></span>
                <p class="mt-3 font-mono text-2xl font-bold text-white">{{ s.value }}</p>
                <p class="text-xs text-zinc-500">{{ s.label }}</p>
            </a>
        </div>

        <div class="card mb-5 flex flex-col gap-4 p-5 sm:flex-row sm:items-center">
            <div class="flex-1">
                <p class="text-sm text-zinc-400">Feedback score</p>
                <p class="mt-1 font-mono text-3xl font-bold text-white">{{ positiveRate }}% <span class="text-sm font-normal text-zinc-500">positive</span></p>
                <div class="mt-3 flex h-2 overflow-hidden rounded-full bg-white/[0.05]">
                    <span class="bg-up" :style="{ width: positiveRate + '%' }"></span>
                    <span class="bg-down" :style="{ width: (widget.feedback ? 100 - positiveRate : 0) + '%' }"></span>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center sm:w-80">
                <div class="rounded-xl bg-up/[0.06] p-3"><p class="font-mono text-lg font-bold text-up">{{ widget.positive }}</p><p class="text-[11px] text-zinc-500">Positive</p></div>
                <div class="rounded-xl bg-down/[0.06] p-3"><p class="font-mono text-lg font-bold text-down">{{ widget.negative }}</p><p class="text-[11px] text-zinc-500">Negative</p></div>
                <a :href="urls.feedback" class="rounded-xl bg-white/[0.03] p-3 hover:bg-white/[0.06]"><p class="font-mono text-lg font-bold text-white">{{ widget.feedback }}</p><p class="text-[11px] text-zinc-500">Total</p></a>
            </div>
        </div>
    </template>

    <h2 class="mb-3 font-semibold text-white">Recent trades</h2>
    <DataList :rows="trades" :columns="columns" empty-icon="ri-exchange-line" empty-title="No P2P trades yet" empty-text="Start trading from the P2P market.">
        <template #empty><Link :href="urls.market" class="btn-primary py-2 text-xs">Open P2P market</Link></template>

        <template #cell-uid="{ row }">
            <div class="flex items-center gap-2">
                <Badge :tone="row.side === 'Buy' ? 'success' : 'danger'">{{ row.side }}</Badge>
                <div>
                    <p class="font-mono text-sm text-white">{{ row.uid }}</p>
                    <p class="text-[11px] text-zinc-500">{{ timeAgo(row.date) }}</p>
                </div>
            </div>
        </template>
        <template #cell-parties="{ row }">
            <p class="text-xs"><span class="text-up">B</span> {{ row.buyer }}</p>
            <p class="text-xs"><span class="text-down">S</span> {{ row.seller }}</p>
        </template>
        <template #cell-price="{ row }">
            <p class="font-mono text-sm text-white">{{ formatMoney(row.price) }} {{ row.fiat }}</p>
            <p class="text-[11px] text-zinc-500">{{ row.method }}</p>
        </template>
        <template #cell-amount="{ row }">
            <p class="font-mono text-sm text-white">{{ formatAmount(row.assetAmount) }} {{ row.asset }}</p>
            <p class="font-mono text-[11px] text-zinc-500">{{ formatMoney(row.fiatAmount) }} {{ row.fiat }}</p>
        </template>
        <template #cell-status="{ row }"><Badge :tone="statusTone(row.status)" dot>{{ row.status }}</Badge></template>
        <template #actions="{ row }">
            <a :href="row.url" class="btn-ghost px-3 py-1.5 text-xs"><i class="ri-eye-line"></i> Details</a>
        </template>
    </DataList>
</template>
