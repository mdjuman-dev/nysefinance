<script setup>
import { computed, ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import ReferralNode from '@/Components/ReferralNode.vue';
import { useToast } from '@/composables/useToast';
import { copyText } from '@/utils/clipboard';
import { formatMoney } from '@/utils/format';

const props = defineProps({
    viewing: Object,
    referrer: String,
    invest: Number,
    direct: Number,
    showBonus: Boolean,
    tree: Array,
    urls: Object,
});

const shell = usePage().props.shell;
const toast = useToast();
const filter = ref('');

const countAll = (nodes) => nodes.reduce((s, n) => s + 1 + countAll(n.children || []), 0);
const teamSize = computed(() => countAll(props.tree));

async function copyLink() {
    (await copyText(shell.referralLink)) ? toast.success('Referral link copied') : toast.error('Could not copy');
}
</script>

<template>
    <Head title="My referrals" />
    <PageHeader title="My referrals" subtitle="Grow your team and track its investment" icon="ri-share-forward-line">
        <template #actions>
            <span v-if="showBonus" class="inline-flex items-center gap-1.5 rounded-full border border-brand-500/30 bg-brand-500/10 px-3 py-1.5 text-xs font-semibold text-brand-300">
                <i class="ri-gift-line"></i> Sign-up bonus $60
            </span>
        </template>
    </PageHeader>

    <!-- Invite card -->
    <section class="relative mb-5 overflow-hidden rounded-3xl border border-brand-500/20 bg-gradient-to-br from-brand-600/25 via-ink-900 to-ink-900 p-5 sm:p-7">
        <i class="ri-team-line pointer-events-none absolute -right-6 -bottom-10 text-[160px] text-brand-500/10"></i>
        <div class="relative grid grid-cols-1 gap-5 lg:grid-cols-2 lg:items-center">
            <div>
                <h2 class="text-lg font-semibold text-white">Invite friends to {{ $page.props.site.name }}</h2>
                <p class="mt-1 text-sm text-zinc-400">Share your personal link. Everyone who signs up with it joins your team.</p>
                <p v-if="referrer && viewing.isSelf" class="mt-3 text-xs text-zinc-500">You were referred by <span class="font-semibold text-zinc-300">{{ referrer }}</span></p>
            </div>
            <div class="flex items-center gap-2 rounded-2xl border border-white/10 bg-ink-950/60 p-2 pl-4">
                <span class="min-w-0 flex-1 truncate font-mono text-xs text-zinc-400">{{ shell.referralLink }}</span>
                <button class="btn-primary shrink-0 px-4 py-2 text-xs" @click="copyLink"><i class="ri-file-copy-line"></i> Copy link</button>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-5">
        <div class="card p-4 sm:p-5">
            <p class="text-xs text-zinc-500 sm:text-sm">Team investment</p>
            <p class="mt-2 font-mono text-xl font-bold text-white sm:text-2xl">${{ formatMoney(invest) }}</p>
        </div>
        <div class="card p-4 sm:p-5">
            <p class="text-xs text-zinc-500 sm:text-sm">Direct referrals</p>
            <p class="mt-2 font-mono text-xl font-bold text-white sm:text-2xl">{{ direct }}</p>
        </div>
        <div class="card p-4 sm:p-5">
            <p class="text-xs text-zinc-500 sm:text-sm">Team size (6 levels)</p>
            <p class="mt-2 font-mono text-xl font-bold text-white sm:text-2xl">{{ teamSize }}</p>
        </div>
        <div class="card p-4 sm:p-5">
            <p class="text-xs text-zinc-500 sm:text-sm">Stock member</p>
            <p class="mt-2 text-base font-semibold" :class="viewing.member ? 'text-brand-300' : 'text-zinc-500'">
                <i :class="viewing.member ? 'ri-verified-badge-fill' : 'ri-close-circle-line'"></i> {{ viewing.member ? 'Verified' : 'Not yet' }}
            </p>
        </div>
    </div>

    <!-- Tree -->
    <section class="card overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-white/[0.05] p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
            <div class="min-w-0">
                <h2 class="font-semibold text-white">Team tree</h2>
                <p class="truncate text-xs text-zinc-500">
                    <template v-if="viewing.isSelf">Your downline</template>
                    <template v-else>Downline of <span class="text-zinc-300">{{ viewing.name }}</span> (@{{ viewing.username }})</template>
                </p>
            </div>
            <div class="flex gap-2">
                <Link v-if="!viewing.isSelf" :href="urls.self" class="btn-ghost px-3 py-2 text-xs"><i class="ri-arrow-go-back-line"></i> My tree</Link>
                <label class="relative block flex-1 sm:w-60">
                    <i class="ri-search-line pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-sm text-zinc-500"></i>
                    <input v-model="filter" type="search" placeholder="Find a member" class="w-full rounded-lg border border-white/[0.07] bg-white/[0.03] py-2 pr-3 pl-9 text-sm text-zinc-200 placeholder:text-zinc-500 focus:border-brand-500/50 focus:outline-none" />
                </label>
            </div>
        </div>
        <ul v-if="tree.length" class="space-y-0.5 p-2 sm:p-3">
            <ReferralNode v-for="n in tree" :key="n.id" :node="n" :filter="filter" />
        </ul>
        <EmptyState v-else icon="ri-team-line" title="No referrals yet" text="Share your link to start building your team." />
    </section>
</template>
