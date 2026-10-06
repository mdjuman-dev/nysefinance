<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import { timeAgo } from '@/utils/format';

const props = defineProps({ summary: Object, feedbacks: Object, urls: Object });
const rate = computed(() => (props.summary.total ? Math.round((props.summary.positive / props.summary.total) * 100) : 0));
</script>

<template>
    <Head title="P2P feedback" />
    <PageHeader title="Feedback" subtitle="What traders say about you" icon="ri-chat-smile-2-line">
        <template #actions>
            <Link :href="urls.center" class="btn-ghost"><i class="ri-store-2-line"></i> P2P center</Link>
        </template>
    </PageHeader>

    <section class="card mb-5 grid grid-cols-1 gap-5 p-5 sm:grid-cols-[auto_1fr] sm:items-center sm:p-6">
        <div class="text-center sm:pr-6 sm:text-left">
            <p class="text-4xl font-bold text-white">{{ rate }}%</p>
            <p class="text-sm text-zinc-500">positive · {{ summary.total }} reviews</p>
        </div>
        <div class="space-y-2">
            <div class="flex items-center gap-3 text-sm">
                <i class="ri-thumb-up-fill text-up"></i>
                <div class="h-2 flex-1 overflow-hidden rounded-full bg-white/[0.06]"><div class="h-full rounded-full bg-up" :style="{ width: (summary.total ? (summary.positive / summary.total) * 100 : 0) + '%' }"></div></div>
                <span class="w-8 text-right font-mono text-zinc-300">{{ summary.positive }}</span>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <i class="ri-thumb-down-fill text-down"></i>
                <div class="h-2 flex-1 overflow-hidden rounded-full bg-white/[0.06]"><div class="h-full rounded-full bg-down" :style="{ width: (summary.total ? (summary.negative / summary.total) * 100 : 0) + '%' }"></div></div>
                <span class="w-8 text-right font-mono text-zinc-300">{{ summary.negative }}</span>
            </div>
        </div>
    </section>

    <div v-if="feedbacks.data.length" class="space-y-3">
        <article v-for="f in feedbacks.data" :key="f.id" class="card flex gap-4 p-4 sm:p-5">
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl text-lg" :class="f.positive ? 'bg-up/10 text-up' : 'bg-down/10 text-down'">
                <i :class="f.positive ? 'ri-thumb-up-line' : 'ri-thumb-down-line'"></i>
            </span>
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <p class="text-sm font-semibold text-white">{{ f.from || 'Trader' }}</p>
                    <p class="text-xs text-zinc-500">{{ timeAgo(f.date) }}</p>
                </div>
                <p class="mt-1 text-sm text-zinc-300">{{ f.comment }}</p>
            </div>
        </article>
        <Pagination :meta="feedbacks.meta" />
    </div>
    <div v-else class="card">
        <EmptyState icon="ri-chat-smile-2-line" title="No feedback yet" text="Complete P2P trades to start collecting reviews." />
    </div>
</template>
