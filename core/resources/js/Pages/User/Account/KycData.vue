<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';

const props = defineProps({ status: Number, rejected: String, rows: Array, formUrl: String });

const state = computed(
    () =>
        ({
            1: { label: 'Verified', text: 'Your identity has been verified.', icon: 'ri-shield-check-fill', cls: 'border-up/25 bg-up/[0.06] text-up' },
            2: { label: 'Under review', text: 'We are reviewing your documents. This usually takes 1–2 business days.', icon: 'ri-time-line', cls: 'border-sky-400/25 bg-sky-400/[0.06] text-sky-300' },
            0: { label: props.rejected ? 'Rejected' : 'Not verified', text: props.rejected || 'Submit your KYC to unlock withdrawals and higher limits.', icon: props.rejected ? 'ri-close-circle-line' : 'ri-error-warning-line', cls: 'border-amber-400/25 bg-amber-400/[0.06] text-amber-300' },
        })[props.status] || {},
);
</script>

<template>
    <Head title="KYC data" />
    <PageHeader title="Identity verification" subtitle="The details you submitted for KYC" icon="ri-passport-line">
        <template v-if="status === 0" #actions>
            <Link :href="formUrl" class="btn-primary"><i class="ri-upload-cloud-2-line"></i> {{ rejected ? 'Resubmit KYC' : 'Start KYC' }}</Link>
        </template>
    </PageHeader>

    <div class="mx-auto max-w-3xl space-y-5">
        <div class="flex items-start gap-3 rounded-2xl border p-4" :class="state.cls">
            <i :class="state.icon" class="text-2xl"></i>
            <div>
                <p class="font-semibold">{{ state.label }}</p>
                <p class="text-sm opacity-80">{{ state.text }}</p>
            </div>
        </div>

        <div v-if="rows.length" class="card divide-y divide-white/[0.05]">
            <div v-for="r in rows" :key="r.label" class="flex flex-wrap items-center justify-between gap-2 px-5 py-3.5">
                <span class="text-sm text-zinc-500">{{ r.label }}</span>
                <a v-if="r.file" :href="r.file" class="inline-flex items-center gap-1.5 text-sm text-brand-300 hover:underline"><i class="ri-attachment-2"></i>Attachment</a>
                <span v-else class="text-right text-sm font-medium break-all text-zinc-100">{{ r.value }}</span>
            </div>
        </div>
        <EmptyState v-else icon="ri-file-search-line" title="No KYC data yet" text="Once you submit your verification, the details appear here." />
    </div>
</template>
