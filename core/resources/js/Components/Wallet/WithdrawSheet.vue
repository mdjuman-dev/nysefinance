<script setup>
import { computed, nextTick, ref, watch } from 'vue';
import jsQR from 'jsqr';
import Modal from '@/Components/UI/Modal.vue';
import { useToast } from '@/composables/useToast';
import { getJson, postJson, messageText } from '@/utils/http';
import { formatAmount } from '@/utils/format';

/**
 * Native form post to user.withdraw.submit with the same fields as the Blade
 * sidebar: currency, amount, method_code, the method's dynamic form fields,
 * security_pin, withdraw_otp, wallet_type=spot.
 */
const props = defineProps({ show: Boolean, methods: Array, urls: Object });
defineEmits(['close']);
const toast = useToast();

const formEl = ref(null);
const currencies = ref([]);
const currency = ref('');
const amount = ref('');
const methodId = ref('');
const pin = ref('');
const otp = ref('');
const balance = ref(null);
const dynamicHtml = ref('');
const loadingForm = ref(false);
const otpState = ref('idle'); // idle | sending | sent
const submitting = ref(false);

// Currencies come from the same endpoint the select2 widget used.
async function loadCurrencies() {
    if (currencies.value.length) return;
    try {
        const res = await getJson(props.urls.currencies, { type: 'all' });
        currencies.value = (res.currencies?.data || []).map((c) => ({ symbol: c.symbol, name: c.name }));
    } catch {}
}
watch(() => props.show, (open) => open && loadCurrencies(), { immediate: true });

// Only currencies that actually have a withdraw method are useful here.
const withdrawable = computed(() => {
    const withMethods = new Set(props.methods.map((m) => m.currency));
    const listed = currencies.value.filter((c) => withMethods.has(c.symbol));
    const missing = [...withMethods].filter((s) => !listed.some((c) => c.symbol === s)).map((s) => ({ symbol: s, name: s }));
    return [...listed, ...missing];
});
const currencyMethods = computed(() => props.methods.filter((m) => m.currency === currency.value));
const method = computed(() => props.methods.find((m) => String(m.id) === String(methodId.value)));

watch(currency, async (sym) => {
    methodId.value = '';
    dynamicHtml.value = '';
    balance.value = null;
    if (!sym) return;
    try {
        const res = await getJson(props.urls.coinBalance, { currency: sym });
        balance.value = res.status === 'success' ? Number(res.balance) : 0;
    } catch {
        balance.value = 0;
    }
});

watch(methodId, async () => {
    dynamicHtml.value = '';
    if (!method.value?.formId) return;
    loadingForm.value = true;
    try {
        const res = await fetch(`${props.urls.formLoad}?id=${encodeURIComponent(method.value.formId)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        dynamicHtml.value = await res.text();
    } catch {
        toast.error('Failed to load the network form');
    } finally {
        loadingForm.value = false;
    }
});

const charge = computed(() => (method.value ? method.value.fixed + (Number(amount.value) || 0) * method.value.percent / 100 : 0));
const receivable = computed(() => (Number(amount.value) || 0) - charge.value);
const setMax = () => (amount.value = balance.value ? String(balance.value) : '0');

/* QR → address field (same jsQR decode the Blade page did) */
async function scanQr(e) {
    const file = e.target.files[0];
    e.target.value = '';
    const address = formEl.value?.querySelector('input[name="address"]');
    if (!file) return;
    try {
        const url = URL.createObjectURL(file);
        const img = await new Promise((resolve, reject) => {
            const i = new Image();
            i.onload = () => resolve(i);
            i.onerror = reject;
            i.src = url;
        });
        const canvas = document.createElement('canvas');
        canvas.width = img.width;
        canvas.height = img.height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);
        const code = jsQR(ctx.getImageData(0, 0, canvas.width, canvas.height).data, canvas.width, canvas.height, { inversionAttempts: 'dontInvert' });
        URL.revokeObjectURL(url);
        if (code && address) {
            address.value = code.data;
            toast.success('QR code scanned — address filled in');
        } else if (code) {
            toast.info(`Scanned: ${code.data}`);
        } else {
            toast.error('No valid QR code found in the image');
        }
    } catch {
        toast.error('Could not read that image');
    }
}

async function sendOtp() {
    otpState.value = 'sending';
    const res = await postJson(props.urls.sendOtp, { type: 'withdraw' });
    if (res.status === 'success') {
        otpState.value = 'sent';
        toast.success(messageText(res.message) || 'OTP sent to your email');
    } else {
        otpState.value = 'idle';
        toast.error('Something went wrong, try again later');
    }
}

function submit() {
    if (!amount.value) return toast.error('Please enter withdraw amount');
    if (!otp.value) return toast.error('Please enter a valid OTP');
    submitting.value = true;
    nextTick(() => formEl.value.submit());
}

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content;
</script>

<template>
    <Modal :show="show" title="Withdraw" size="max-w-lg" @close="$emit('close')">
        <form ref="formEl" :action="urls.withdrawSubmit" method="post" enctype="multipart/form-data" class="space-y-4" @submit.prevent="submit">
            <input type="hidden" name="_token" :value="csrf()" />
            <input type="hidden" name="wallet_type" value="spot" />

            <div>
                <label class="mb-2 block text-sm font-medium text-zinc-300">Currency</label>
                <div class="relative">
                    <select v-model="currency" name="currency" class="field appearance-none pr-9" required>
                        <option value="" disabled>Select currency</option>
                        <option v-for="c in withdrawable" :key="c.symbol" :value="c.symbol">{{ c.symbol }}{{ c.name !== c.symbol ? ` — ${c.name}` : '' }}</option>
                    </select>
                    <i class="ri-arrow-down-s-line pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-zinc-500"></i>
                </div>
            </div>

            <div>
                <div class="mb-2 flex items-center justify-between">
                    <label class="text-sm font-medium text-zinc-300">Amount</label>
                    <span class="text-xs text-zinc-500">Available <span class="font-mono text-zinc-300">{{ balance == null ? '—' : formatAmount(balance) }} {{ currency }}</span></span>
                </div>
                <div class="flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
                    <input v-model="amount" type="number" step="any" min="0" name="amount" placeholder="0.00" class="w-full bg-transparent px-4 py-3 font-mono text-sm text-zinc-100 focus:outline-none" required />
                    <button type="button" class="mr-2 rounded-lg bg-brand-500/15 px-2.5 py-1 text-xs font-bold text-brand-300 hover:bg-brand-500/25" :disabled="!currency" @click="setMax">MAX</button>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-zinc-300">Network</label>
                <div v-if="currency && !currencyMethods.length" class="rounded-xl border border-white/10 bg-white/[0.02] p-3 text-sm text-zinc-500">No withdraw network available for {{ currency }}.</div>
                <div v-else class="grid max-h-60 gap-2 overflow-y-auto pr-1" :class="currencyMethods.length > 2 ? 'grid-cols-2' : 'grid-cols-1'">
                    <label v-for="m in currencyMethods" :key="m.id" class="cursor-pointer">
                        <input v-model="methodId" type="radio" name="method_code" :value="String(m.id)" class="peer sr-only" required />
                        <span class="block rounded-xl border border-white/10 p-3 transition peer-checked:border-brand-500 peer-checked:bg-brand-500/10">
                            <span class="block text-sm font-semibold text-white">{{ m.name }}</span>
                            <span class="block text-[11px] text-zinc-500">Fee {{ formatAmount(m.fixed) }}{{ m.percent ? ` + ${m.percent}%` : '' }}</span>
                        </span>
                    </label>
                    <p v-if="!currency" class="rounded-xl border border-dashed border-white/10 p-3 text-center text-sm text-zinc-500">Choose a currency to see networks</p>
                </div>
            </div>

            <!-- Method-specific fields rendered by the server (x-viser-form) -->
            <div v-if="loadingForm" class="h-16 animate-pulse rounded-xl bg-white/[0.03]"></div>
            <div v-show="dynamicHtml && !loadingForm" class="viser-fields space-y-3" v-html="dynamicHtml"></div>

            <label v-if="dynamicHtml" class="flex cursor-pointer items-center gap-3 rounded-xl border border-dashed border-white/10 px-4 py-3 text-sm text-zinc-400 hover:border-white/20">
                <i class="ri-qr-scan-2-line text-xl text-brand-300"></i>
                <span class="flex-1">Upload a QR code image to fill the address</span>
                <input type="file" accept="image/*" class="hidden" @change="scanQr" />
            </label>

            <div v-if="method" class="space-y-2 rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 text-sm">
                <div class="flex justify-between"><span class="text-zinc-500">Limit</span><span class="font-mono text-zinc-200">{{ formatAmount(method.min) }} – {{ formatAmount(method.max) }} {{ currency }}</span></div>
                <div class="flex justify-between"><span class="text-zinc-500">Fee</span><span class="font-mono text-down">{{ formatAmount(charge) }} {{ currency }}</span></div>
                <div class="flex justify-between border-t border-white/[0.06] pt-2"><span class="text-zinc-300">You receive</span><span class="font-mono font-semibold text-white">{{ formatAmount(Math.max(receivable, 0)) }} {{ currency }}</span></div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-300">Security PIN</label>
                    <input v-model="pin" type="password" inputmode="numeric" name="security_pin" autocomplete="off" placeholder="Security PIN" class="field" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-300">Email OTP</label>
                    <div class="flex items-center rounded-xl border border-white/10 bg-ink-850 focus-within:border-brand-500/60">
                        <input v-model="otp" type="text" inputmode="numeric" name="withdraw_otp" autocomplete="one-time-code" placeholder="OTP" class="w-full min-w-0 bg-transparent px-4 py-3 font-mono text-sm text-zinc-100 focus:outline-none" />
                        <button type="button" class="mr-2 shrink-0 rounded-lg bg-white/[0.06] px-2.5 py-1 text-xs font-semibold text-zinc-200 hover:bg-white/[0.1]" :disabled="otpState === 'sending'" @click="sendOtp">
                            {{ otpState === 'sending' ? 'Sending…' : otpState === 'sent' ? 'Resend' : 'Send OTP' }}
                        </button>
                    </div>
                </div>
            </div>

            <ul class="space-y-1 text-[11px] text-zinc-500">
                <li><i class="ri-error-warning-line text-amber-400"></i> Double-check the address and network — transfers can't be reversed.</li>
                <li><i class="ri-shield-check-line text-brand-400"></i> Never share your PIN or OTP with anyone.</li>
            </ul>

            <button type="submit" class="btn-primary w-full py-3" :disabled="submitting || !currency || !methodId">
                <i :class="submitting ? 'ri-loader-4-line animate-spin' : 'ri-upload-2-line'"></i> Withdraw
            </button>
        </form>
    </Modal>
</template>

