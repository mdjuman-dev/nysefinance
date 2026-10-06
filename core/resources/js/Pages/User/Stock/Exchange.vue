<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import CoinIcon from '@/Components/CoinIcon.vue';
import { formatMoney } from '@/utils/format';
import { formatDateTime } from '@/utils/display';

const props = defineProps({ stock: Object, holder: String, brokers: Array, charge: Number, balance: Number, urls: Object });

const form = useForm({ stock_id: props.stock.id, broker: '', receive_amount: '', contact_email: '', comment: '' });
const submit = () => form.post(props.urls.submit);
</script>

<template>
    <Head title="Transfer stock" />
    <PageHeader title="Transfer stock to a broker" subtitle="Move this holding to an external broker or exchange" icon="ri-exchange-funds-line">
        <template #actions>
            <Link :href="urls.my" class="btn-ghost"><i class="ri-arrow-left-line"></i> My stocks</Link>
        </template>
    </PageHeader>

    <div class="mx-auto grid max-w-5xl grid-cols-1 gap-5 lg:grid-cols-[1fr_1.2fr]">
        <!-- holding -->
        <section class="card h-fit p-5 sm:p-6">
            <div class="flex items-center gap-3">
                <CoinIcon :src="stock.image" :symbol="stock.code || stock.name" size="h-12 w-12" />
                <div class="min-w-0">
                    <h2 class="truncate font-semibold text-white">{{ stock.name }}</h2>
                    <p class="font-mono text-xs text-zinc-500">{{ stock.code }}</p>
                </div>
            </div>
            <dl class="mt-5 divide-y divide-white/[0.05] text-sm">
                <div class="flex justify-between py-2.5"><dt class="text-zinc-500">Holder</dt><dd class="text-zinc-100">{{ holder }}</dd></div>
                <div class="flex justify-between py-2.5"><dt class="text-zinc-500">Position size</dt><dd class="font-mono text-zinc-100">${{ formatMoney(stock.amount) }}</dd></div>
                <div class="flex justify-between py-2.5"><dt class="text-zinc-500">Bought</dt><dd class="text-zinc-100">{{ formatDateTime(stock.date) }}</dd></div>
                <div class="flex justify-between py-2.5">
                    <dt class="text-zinc-500">Type</dt>
                    <dd><span class="rounded-md px-2 py-0.5 text-xs font-semibold" :class="stock.type === 'unfix' ? 'bg-up/10 text-up' : 'bg-violet-400/10 text-violet-300'">{{ stock.type === 'unfix' ? 'Live market' : 'Mutual fund' }}</span></dd>
                </div>
            </dl>
        </section>

        <!-- request -->
        <form class="card space-y-4 p-5 sm:p-6" @submit.prevent="submit">
            <label class="block">
                <span class="mb-1.5 block text-sm font-medium text-zinc-300">Broker &amp; exchange</span>
                <select v-model="form.broker" class="field" required>
                    <option value="" disabled>Choose broker</option>
                    <option v-for="b in brokers" :key="b" :value="b">{{ b }}</option>
                </select>
                <span v-if="form.errors.broker" class="mt-1 block text-xs text-down">{{ form.errors.broker }}</span>
            </label>
            <label class="block">
                <span class="mb-1.5 block text-sm font-medium text-zinc-300">Receiving account</span>
                <input v-model="form.receive_amount" type="email" class="field" placeholder="Receiver email at the broker" required />
            </label>
            <label class="block">
                <span class="mb-1.5 block text-sm font-medium text-zinc-300">Contact email</span>
                <input v-model="form.contact_email" type="email" class="field" placeholder="you@example.com" required />
            </label>
            <label class="block">
                <span class="mb-1.5 block text-sm font-medium text-zinc-300">Comment <span class="text-zinc-500">(optional)</span></span>
                <textarea v-model="form.comment" rows="3" class="field" placeholder="Anything the team should know"></textarea>
            </label>

            <div class="space-y-1.5 rounded-xl border border-white/[0.06] bg-white/[0.02] p-4 text-sm">
                <div class="flex justify-between"><span class="text-zinc-500">Transfer charge (3%)</span><span class="font-mono text-down">${{ formatMoney(charge) }}</span></div>
                <div class="flex justify-between"><span class="text-zinc-500">USDT balance</span><span class="font-mono text-zinc-200">${{ formatMoney(balance) }}</span></div>
            </div>

            <button class="btn-primary w-full py-3" :disabled="form.processing">
                <i :class="form.processing ? 'ri-loader-4-line animate-spin' : 'ri-send-plane-line'"></i> Submit transfer request
            </button>
        </form>
    </div>
</template>
