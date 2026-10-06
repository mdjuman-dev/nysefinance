<script setup>
// Manual (admin-verified) deposit: show what to pay and where, then collect proof.
// The proof form is the gateway's server-rendered field set, posted natively (files).
import { onMounted, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import QRCode from 'qrcode';
import PageHeader from '@/Components/UI/PageHeader.vue';
import { formatAmount } from '@/utils/format';
import { copyText } from '@/utils/clipboard';
import { useToast } from '@/composables/useToast';

const props = defineProps({ deposit: Object, gateway: Object, formHtml: String, action: String, history: String });

const token = document.querySelector('meta[name="csrf-token"]')?.content;
const qr = ref('');
const busy = ref(false);
const toast = useToast();
async function copyAddress() {
    if (await copyText(props.gateway.address)) toast.success('Address copied');
}

onMounted(async () => {
    if (props.gateway.address) {
        qr.value = await QRCode.toDataURL(props.gateway.address, { margin: 1, width: 220, color: { dark: '#0a0f0c', light: '#ffffff' } }).catch(() => '');
    }
});

const steps = ['Send the exact amount', 'Upload your payment proof', 'We verify and credit your wallet'];
</script>

<template>
    <Head title="Confirm deposit" />
    <PageHeader title="Confirm deposit" :subtitle="`Pay with ${gateway.name}`" icon="ri-bank-card-line">
        <template #actions>
            <Link :href="history" class="btn-ghost"><i class="ri-history-line"></i> Deposit history</Link>
        </template>
    </PageHeader>

    <div class="mx-auto grid max-w-5xl grid-cols-1 gap-5 lg:grid-cols-[1fr_380px]">
        <div class="space-y-5">
            <!-- amount -->
            <section class="card overflow-hidden">
                <div class="bg-gradient-to-r from-brand-500/15 via-brand-400/5 to-transparent p-5 sm:p-6">
                    <p class="text-xs font-medium tracking-wider text-zinc-400 uppercase">Amount to pay</p>
                    <p class="mt-1 font-mono text-3xl font-bold text-white sm:text-4xl">
                        {{ formatAmount(deposit.final) }} <span class="text-lg text-brand-300">{{ deposit.currency }}</span>
                    </p>
                    <p class="mt-1 text-xs text-zinc-500">Reference <span class="font-mono text-zinc-300">#{{ deposit.trx }}</span></p>
                </div>
                <dl class="grid grid-cols-1 divide-y divide-white/[0.05] text-sm sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                    <div class="px-5 py-3"><dt class="text-xs text-zinc-500">Requested</dt><dd class="font-mono text-zinc-100">{{ formatAmount(deposit.amount) }} {{ deposit.currency }}</dd></div>
                    <div class="px-5 py-3"><dt class="text-xs text-zinc-500">Fee</dt><dd class="font-mono text-zinc-100">{{ formatAmount(deposit.charge) }} {{ deposit.currency }}</dd></div>
                    <div class="px-5 py-3"><dt class="text-xs text-zinc-500">Credited to</dt><dd class="text-zinc-100">{{ deposit.wallet || deposit.currency }} wallet</dd></div>
                </dl>
            </section>

            <!-- address -->
            <section v-if="gateway.address" class="card p-5 sm:p-6">
                <h2 class="font-semibold text-white">Deposit address</h2>
                <p class="mb-4 text-sm text-zinc-500">Send only {{ deposit.currency }} to this address.</p>
                <div class="flex flex-col items-center gap-5 sm:flex-row sm:items-start">
                    <div class="shrink-0 rounded-2xl bg-white p-2.5">
                        <img v-if="qr" :src="qr" alt="Deposit address QR code" class="h-40 w-40" />
                        <div v-else class="h-40 w-40 animate-pulse rounded-xl bg-zinc-200"></div>
                    </div>
                    <div class="w-full min-w-0 flex-1">
                        <div class="rounded-xl border border-white/10 bg-ink-850 p-3 font-mono text-sm break-all text-zinc-100">{{ gateway.address }}</div>
                        <button type="button" class="btn-primary mt-3 w-full sm:w-auto" @click="copyAddress">
                            <i class="ri-file-copy-line"></i> Copy address
                        </button>
                        <p class="mt-3 flex items-start gap-1.5 text-xs text-amber-300">
                            <i class="ri-alert-line mt-0.5"></i>Sending another coin or using the wrong network may result in permanent loss.
                        </p>
                    </div>
                </div>
            </section>

            <!-- instructions -->
            <section v-if="gateway.description" class="card p-5 sm:p-6">
                <h2 class="mb-3 font-semibold text-white">Instructions</h2>
                <div class="cms-content text-sm" v-html="gateway.description"></div>
            </section>
        </div>

        <!-- proof -->
        <aside class="space-y-5">
            <ol class="card space-y-3 p-5">
                <li v-for="(s, i) in steps" :key="s" class="flex items-center gap-3 text-sm">
                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full text-xs font-bold" :class="i === 0 ? 'bg-brand-500 text-ink-950' : 'bg-white/[0.06] text-zinc-400'">{{ i + 1 }}</span>
                    <span :class="i === 0 ? 'text-white' : 'text-zinc-400'">{{ s }}</span>
                </li>
            </ol>

            <form :action="action" method="POST" enctype="multipart/form-data" class="card space-y-4 p-5 lg:sticky lg:top-24" @submit="busy = true">
                <input type="hidden" name="_token" :value="token" />
                <div>
                    <h2 class="font-semibold text-white">Payment proof</h2>
                    <p class="text-sm text-zinc-500">After paying, fill in the details below.</p>
                </div>
                <div class="viser-fields space-y-3" v-html="formHtml"></div>
                <button type="submit" class="btn-primary w-full py-3" :disabled="busy">
                    <i :class="busy ? 'ri-loader-4-line animate-spin' : 'ri-send-plane-line'"></i>
                    {{ busy ? 'Submitting…' : 'I have paid' }}
                </button>
            </form>
        </aside>
    </div>
</template>
