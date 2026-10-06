<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import CoinIcon from '@/Components/CoinIcon.vue';
import { formatAmount } from '@/utils/format';

defineProps({ ads: Object, isAgent: Boolean, urls: Object });

const busy = ref(null);
function toggle(ad) {
    busy.value = ad.id;
    router.post(ad.toggle, {}, { preserveScroll: true, onFinish: () => (busy.value = null) });
}
</script>

<template>
    <Head title="My ads" />
    <PageHeader title="My P2P ads" subtitle="Manage the buy and sell offers you publish on the P2P market" icon="ri-megaphone-line">
        <template #actions>
            <Link :href="urls.market" class="btn-ghost"><i class="ri-store-2-line"></i> P2P market</Link>
            <Link v-if="isAgent" :href="urls.create" class="btn-primary"><i class="ri-add-line"></i> New ad</Link>
        </template>
    </PageHeader>

    <div v-if="ads.data.length" class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <article v-for="ad in ads.data" :key="ad.id" class="card p-5">
            <div class="flex items-start justify-between gap-3">
                <div class="flex min-w-0 items-center gap-3">
                    <CoinIcon :src="ad.asset.image" :symbol="ad.asset.symbol" size="h-11 w-11" />
                    <div class="min-w-0">
                        <p class="flex items-center gap-2 font-semibold text-white">
                            <span class="rounded-md px-1.5 py-0.5 text-[11px] font-bold uppercase" :class="ad.type === 'buy' ? 'bg-up/10 text-up' : 'bg-down/10 text-down'">{{ ad.type }}</span>
                            {{ ad.asset.symbol }} <span class="text-zinc-500">/ {{ ad.fiat.symbol }}</span>
                        </p>
                        <p class="text-xs text-zinc-500">{{ ad.asset.name }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold" :class="ad.published && ad.enabled ? 'bg-up/10 text-up' : 'bg-white/[0.05] text-zinc-400'">
                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ !ad.enabled ? 'Disabled' : ad.published ? 'Live' : 'Not published' }}
                </span>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3 rounded-2xl bg-white/[0.02] p-4 text-sm">
                <div>
                    <p class="text-xs text-zinc-500">Price <span v-if="ad.priceType === 'margin'" class="text-sky-300">· margin {{ ad.margin }}%</span></p>
                    <p class="font-mono font-semibold text-white">{{ formatAmount(ad.price) }} {{ ad.fiat.symbol }}</p>
                </div>
                <div>
                    <p class="text-xs text-zinc-500">Limit</p>
                    <p class="font-mono text-zinc-200">{{ formatAmount(ad.min) }} – {{ formatAmount(ad.max) }}</p>
                </div>
                <div>
                    <p class="text-xs text-zinc-500">Payment window</p>
                    <p class="text-zinc-200">{{ ad.window ? ad.window + ' min' : '—' }}</p>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-zinc-500">Methods</p>
                    <p class="truncate text-zinc-200">{{ ad.methods.join(', ') || '—' }}</p>
                </div>
            </div>

            <p v-if="!ad.published" class="mt-3 flex items-start gap-1.5 text-xs text-amber-300">
                <i class="ri-information-line mt-0.5"></i>
                {{ ad.complete ? "Not enough funding wallet balance to publish this sell ad." : 'Finish all steps to publish this ad.' }}
            </p>

            <div class="mt-4 flex gap-2">
                <Link :href="ad.edit" class="btn-ghost flex-1 py-2 text-sm"><i class="ri-edit-line"></i> {{ ad.complete ? 'Edit' : 'Continue' }}</Link>
                <button type="button" class="btn-ghost flex-1 py-2 text-sm" :disabled="busy === ad.id" @click="toggle(ad)">
                    <i :class="busy === ad.id ? 'ri-loader-4-line animate-spin' : ad.enabled ? 'ri-pause-circle-line' : 'ri-play-circle-line'"></i>
                    {{ ad.enabled ? 'Disable' : 'Enable' }}
                </button>
            </div>
        </article>
    </div>
    <div v-else class="card">
        <EmptyState icon="ri-megaphone-line" title="No ads yet" :text="isAgent ? 'Create your first ad to start trading on the P2P market.' : 'Only P2P agents can publish ads.'" />
        <div v-if="isAgent" class="-mt-6 pb-8 text-center"><Link :href="urls.create" class="btn-primary"><i class="ri-add-line"></i> Create ad</Link></div>
    </div>
    <Pagination v-if="ads.meta.last > 1" :meta="ads.meta" class="mt-5" />
</template>
