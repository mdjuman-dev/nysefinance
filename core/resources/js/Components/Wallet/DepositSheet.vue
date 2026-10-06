<script setup>
import { computed, ref } from 'vue';
import Modal from '@/Components/UI/Modal.vue';

// Native form post to user.deposit.insert — same fields as the Blade sidebar
// (gateway, currency, amount, security_pin); the controller redirects to the payment step.
const props = defineProps({ show: Boolean, gateways: Array, action: String });
defineEmits(['close']);

const gateway = ref('');
const amount = ref('');
const pin = ref('');
const submitting = ref(false);

// automatic gateways (e.g. PvPay / Binance Pay) credit instantly, so list them first with their name
const options = computed(() => [
    ...props.gateways.filter((g) => g.automatic),
    { code: 'web3', currency: 'USDT (web3)' },
    ...props.gateways.filter((g) => !g.automatic),
]);
const label = (o) => (o.automatic && o.name ? `${o.currency} · ${o.name} (instant)` : o.currency);
const currency = computed(() => options.value.find((o) => o.code === gateway.value)?.currency || '');
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content;
</script>

<template>
    <Modal :show="show" title="Deposit" @close="$emit('close')">
        <p class="-mt-1 mb-4 text-sm text-zinc-500">Make crypto and fiat deposits in a few steps.</p>
        <form :action="action" method="post" class="space-y-4" @submit="submitting = true">
            <input type="hidden" name="_token" :value="csrf()" />
            <input type="hidden" name="currency" :value="currency" />

            <div>
                <label class="mb-2 block text-sm font-medium text-zinc-300">Deposit currency</label>
                <div class="relative">
                    <select v-model="gateway" name="gateway" class="field appearance-none pr-9" required>
                        <option value="" disabled>Select currency</option>
                        <option v-for="o in options" :key="o.code" :value="o.code">{{ label(o) }}</option>
                    </select>
                    <i class="ri-arrow-down-s-line pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-zinc-500"></i>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-zinc-300">Amount</label>
                <div class="flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
                    <input v-model="amount" type="number" step="any" min="0" name="amount" placeholder="0.00" class="w-full bg-transparent px-4 py-3 font-mono text-sm text-zinc-100 focus:outline-none" required />
                    <span class="pr-4 text-sm font-semibold text-zinc-400">{{ currency.replace(' (web3)', '') || 'USD' }}</span>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-zinc-300">Security PIN</label>
                <input v-model="pin" type="password" inputmode="numeric" name="security_pin" autocomplete="off" placeholder="Enter your security PIN" class="field" required />
            </div>

            <button type="submit" class="btn-primary w-full py-3" :disabled="submitting || !gateway || !amount">
                <i :class="submitting ? 'ri-loader-4-line animate-spin' : 'ri-arrow-right-line'"></i> Continue
            </button>
        </form>
    </Modal>
</template>
