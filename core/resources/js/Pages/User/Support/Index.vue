<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import DataList from '@/Components/UI/DataList.vue';
import Badge from '@/Components/UI/Badge.vue';
import { timeAgo } from '@/utils/format';
import { ticketStatusTone, priorityTone } from '@/utils/display';

defineProps({ tickets: Object, urls: Object });

const columns = [
    { key: 'subject', label: 'Ticket', wide: true },
    { key: 'status', label: 'Status' },
    { key: 'priority', label: 'Priority' },
    { key: 'lastReply', label: 'Last reply', align: 'right' },
];
</script>

<template>
    <Head title="Support" />
    <PageHeader title="Support tickets" subtitle="Talk to our team about anything" icon="ri-customer-service-2-line">
        <template #actions>
            <Link :href="urls.open" class="btn-primary"><i class="ri-add-line"></i> New ticket</Link>
        </template>
    </PageHeader>

    <DataList :rows="tickets.data" :columns="columns" :meta="tickets.meta" empty-icon="ri-customer-service-2-line" empty-title="No tickets yet" empty-text="Open a ticket and our support team will get back to you.">
        <template #empty><Link :href="urls.open" class="btn-primary py-2 text-xs">Open a ticket</Link></template>

        <template #card="{ row }">
            <Link :href="row.url" class="flex items-start gap-3">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/[0.04] text-lg text-brand-300"><i class="ri-chat-3-line"></i></span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white">{{ row.subject }}</p>
                    <p class="text-xs text-zinc-500">#{{ row.ticket }} · {{ timeAgo(row.lastReply) }}</p>
                    <div class="mt-2 flex gap-1.5">
                        <Badge :tone="ticketStatusTone(row.status)" dot>{{ row.status }}</Badge>
                        <Badge :tone="priorityTone(row.priority)">{{ row.priority }}</Badge>
                    </div>
                </div>
                <i class="ri-arrow-right-s-line text-xl text-zinc-600"></i>
            </Link>
        </template>

        <template #cell-subject="{ row }">
            <Link :href="row.url" class="group block">
                <p class="font-semibold text-white group-hover:text-brand-300">{{ row.subject }}</p>
                <p class="text-xs text-zinc-500">#{{ row.ticket }}</p>
            </Link>
        </template>
        <template #cell-status="{ row }"><Badge :tone="ticketStatusTone(row.status)" dot>{{ row.status }}</Badge></template>
        <template #cell-priority="{ row }"><Badge :tone="priorityTone(row.priority)">{{ row.priority }}</Badge></template>
        <template #cell-lastReply="{ row }"><span class="text-xs text-zinc-400">{{ timeAgo(row.lastReply) }}</span></template>
    </DataList>
</template>
