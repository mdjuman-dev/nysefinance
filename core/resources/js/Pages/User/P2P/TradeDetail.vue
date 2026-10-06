<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import CoinIcon from '@/Components/CoinIcon.vue';
import Modal from '@/Components/UI/Modal.vue';
import { formatAmount, timeAgo } from '@/utils/format';
import { formatDateTime } from '@/utils/display';
import { getJson, postJson, messageText } from '@/utils/http';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    trade: Object,
    sellerAccount: Object,
    trader: Object,
    messages: Array,
    feedback: Object,
    canFeedback: Boolean,
    isAgent: Boolean,
    urls: Object,
});
const toast = useToast();

const isBuyer = computed(() => props.trade.role === 'buyer');
const open = computed(() => ['pending', 'paid'].includes(props.trade.status));

/* ---------- status timeline ---------- */
const stages = computed(() => {
    const s = props.trade.status;
    const order = { pending: 1, paid: 2, reported: 2, completed: 3, canceled: 0 };
    return [
        { label: 'Order created', done: order[s] >= 1 || s === 'canceled' },
        { label: isBuyer.value ? 'You paid' : 'Buyer paid', done: order[s] >= 2 },
        { label: s === 'canceled' ? 'Canceled' : 'Released', done: order[s] >= 3 || s === 'canceled', bad: s === 'canceled' },
    ];
});
const statusStyle = computed(() => ({
    pending: { text: 'Waiting for payment', cls: 'text-amber-300 bg-amber-400/10', icon: 'ri-time-line' },
    paid: { text: 'Paid — waiting for release', cls: 'text-sky-300 bg-sky-400/10', icon: 'ri-money-dollar-circle-line' },
    reported: { text: 'Under dispute', cls: 'text-amber-300 bg-amber-400/10', icon: 'ri-alarm-warning-line' },
    completed: { text: 'Completed', cls: 'text-up bg-up/10', icon: 'ri-checkbox-circle-line' },
    canceled: { text: 'Canceled', cls: 'text-down bg-down/10', icon: 'ri-close-circle-line' },
})[props.trade.status]);

/* ---------- payment window countdown ---------- */
const left = ref(props.trade.windowLeft);
let tick;
onMounted(() => (tick = setInterval(() => left.value--, 1000)));
const clock = computed(() => {
    const s = Math.max(0, left.value);
    return `${Math.floor(s / 60)}:${String(s % 60).padStart(2, '0')}`;
});

/* ---------- actions ---------- */
const confirmAction = ref(null); // { key, title, text, url, danger }
const releaseForm = useForm({ security_pin: '', p2p_otp: '' });
const busy = ref(false);
const actions = computed(() => {
    const s = props.trade.status;
    const list = [];
    if (s === 'pending' && isBuyer.value) {
        list.push({ key: 'paid', label: 'I have paid', icon: 'ri-check-line', primary: true, url: props.urls.paid, text: 'Confirm you have sent the payment to the seller.' });
        list.push({ key: 'cancel', label: 'Cancel order', icon: 'ri-close-line', url: props.urls.cancel, danger: true, text: 'Cancel this order? Do not cancel if you already paid.' });
    }
    if (s === 'pending' && !isBuyer.value && left.value <= 0) {
        list.push({ key: 'cancel', label: 'Cancel order', icon: 'ri-close-line', url: props.urls.cancel, danger: true, text: 'The payment window has passed. Cancel this order and get your asset back?' });
    }
    if (s === 'paid' && !isBuyer.value) {
        list.push({ key: 'release', label: 'Release asset', icon: 'ri-shield-check-line', primary: true, url: props.urls.release, text: 'Only release after you have received the payment in your account.' });
    }
    if (s === 'paid') {
        list.push({ key: 'dispute', label: 'Dispute', icon: 'ri-alarm-warning-line', url: props.urls.dispute, danger: true, text: 'Open a dispute? Our team will review the chat and payment proof.' });
    }
    return list;
});
function run() {
    const a = confirmAction.value;
    busy.value = true;
    const data = a.key === 'release' ? releaseForm.data() : {};
    router.post(a.url, data, {
        preserveScroll: true,
        onFinish: () => (busy.value = false),
        onSuccess: () => (confirmAction.value = null),
    });
}
const otpSending = ref(false);
async function sendOtp() {
    otpSending.value = true;
    const res = await postJson(props.urls.sendOtp, { type: 'p2p' }).catch(() => null);
    otpSending.value = false;
    res?.status === 'success' ? toast.success(res.message || 'OTP sent to your email') : toast.error('Could not send OTP, try again later');
}

/* ---------- chat ---------- */
const chat = ref([...props.messages]);
const box = ref(null);
const text = ref('');
const file = ref(null);
const sending = ref(false);
const scrollDown = () => nextTick(() => box.value && (box.value.scrollTop = box.value.scrollHeight));
async function poll() {
    const res = await getJson(props.urls.live, { format: 'json' }).catch(() => null);
    if (res?.status !== 'success') return;
    const grew = res.messages.length !== chat.value.length;
    chat.value = res.messages;
    if (grew) scrollDown();
    const statusKey = { 0: 'pending', 1: 'completed', 2: 'paid', 4: 'reported', 9: 'canceled' }[res.tradeStatus];
    if (statusKey && statusKey !== props.trade.status) router.reload({ preserveScroll: true });
}
let pollTimer;
onMounted(() => {
    scrollDown();
    pollTimer = setInterval(poll, 5000);
});
onBeforeUnmount(() => {
    clearInterval(pollTimer);
    clearInterval(tick);
});
async function send() {
    if (!text.value.trim() && !file.value) return;
    sending.value = true;
    const data = { message: text.value.trim() };
    if (file.value) data.attach_file = file.value;
    const res = await postJson(props.urls.send, data, { asForm: true }).catch(() => null);
    sending.value = false;
    if (res?.status === 'success' || res?.success) {
        text.value = '';
        file.value = null;
        await poll();
    } else {
        toast.error(messageText(res?.message) || 'Message not sent');
    }
}

/* ---------- feedback ---------- */
const editingFeedback = ref(false);
const fbForm = useForm({ type: props.feedback?.type ?? 1, comment: props.feedback?.comment || '', feedback_id: props.feedback?.id || 0 });
function saveFeedback() {
    fbForm.post(props.urls.feedback, { preserveScroll: true, onSuccess: () => (editingFeedback.value = false) });
}
function deleteFeedback() {
    if (confirm('Delete your feedback?')) router.post(props.feedback.delete, {}, { preserveScroll: true });
}
</script>

<template>
    <Head :title="`Trade #${trade.uid}`" />

    <!-- header -->
    <section class="card mb-5 p-5 sm:p-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-4">
                <CoinIcon :src="trade.assetImage" :symbol="trade.asset" size="h-12 w-12" />
                <div>
                    <p class="flex items-center gap-2 text-sm">
                        <span class="rounded-md px-1.5 py-0.5 text-[11px] font-bold uppercase" :class="trade.side === 'buy' ? 'bg-up/10 text-up' : 'bg-down/10 text-down'">{{ trade.side }}</span>
                        <span class="font-mono text-zinc-500">#{{ trade.uid }}</span>
                    </p>
                    <p class="mt-1 font-mono text-2xl font-bold text-white">{{ formatAmount(trade.assetAmount) }} {{ trade.asset }}</p>
                    <p class="text-sm text-zinc-400">for <span class="font-mono text-zinc-200">{{ formatAmount(trade.fiatAmount) }} {{ trade.fiat }}</span></p>
                </div>
            </div>
            <div class="flex flex-col items-start gap-3 lg:items-end">
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-semibold" :class="statusStyle.cls"><i :class="statusStyle.icon"></i>{{ statusStyle.text }}</span>
                <p v-if="trade.status === 'pending'" class="text-sm text-zinc-400">
                    <template v-if="left > 0">Payment window closes in <span class="font-mono font-semibold text-white">{{ clock }}</span></template>
                    <template v-else>Payment window has passed</template>
                </p>
            </div>
        </div>

        <!-- timeline -->
        <ol class="mt-6 grid grid-cols-3 gap-2">
            <li v-for="(s, i) in stages" :key="i">
                <span class="block h-1.5 rounded-full" :class="s.bad ? 'bg-down' : s.done ? 'bg-brand-500' : 'bg-white/10'"></span>
                <span class="mt-2 block text-xs" :class="s.done ? 'text-zinc-200' : 'text-zinc-500'">{{ s.label }}</span>
            </li>
        </ol>

        <div v-if="actions.length" class="mt-5 flex flex-wrap gap-2 border-t border-white/[0.06] pt-5">
            <button v-for="a in actions" :key="a.key" type="button" :class="a.primary ? 'btn-primary' : a.danger ? 'btn border border-down/30 bg-down/10 text-down hover:bg-down/20' : 'btn-ghost'" @click="confirmAction = a">
                <i :class="a.icon"></i>{{ a.label }}
            </button>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1fr_400px]">
        <div class="order-2 space-y-5 lg:order-1">
            <!-- details -->
            <section class="card p-5 sm:p-6">
                <h2 class="mb-3 font-semibold text-white">Order details</h2>
                <dl class="grid grid-cols-1 gap-x-6 text-sm sm:grid-cols-2">
                    <div class="flex justify-between border-b border-white/[0.05] py-2.5"><dt class="text-zinc-500">Price</dt><dd class="font-mono text-zinc-100">{{ formatAmount(trade.price) }} {{ trade.fiat }}</dd></div>
                    <div class="flex justify-between border-b border-white/[0.05] py-2.5"><dt class="text-zinc-500">Payment method</dt><dd class="text-zinc-100">{{ trade.method || '—' }}</dd></div>
                    <div class="flex justify-between border-b border-white/[0.05] py-2.5"><dt class="text-zinc-500">Fiat amount</dt><dd class="font-mono text-zinc-100">{{ formatAmount(trade.fiatAmount) }} {{ trade.fiat }}</dd></div>
                    <div class="flex justify-between border-b border-white/[0.05] py-2.5"><dt class="text-zinc-500">Created</dt><dd class="text-zinc-100">{{ formatDateTime(trade.date) }}</dd></div>
                </dl>
            </section>

            <!-- seller account -->
            <section v-if="sellerAccount" class="card p-5 sm:p-6">
                <h2 class="font-semibold text-white">{{ isBuyer ? 'Pay to this account' : 'Your receiving account' }}</h2>
                <p class="mb-3 text-sm text-zinc-500">{{ trade.method }}</p>
                <dl class="space-y-2 rounded-2xl bg-white/[0.03] p-4 text-sm">
                    <div v-for="d in sellerAccount.data" :key="d.name" class="flex justify-between gap-3">
                        <dt class="text-zinc-500">{{ d.name }}</dt>
                        <dd class="min-w-0 text-right font-mono font-semibold break-all text-white">
                            <a v-if="d.file" :href="d.file" class="text-brand-300 hover:underline">Attachment</a>
                            <template v-else>{{ d.value }}</template>
                        </dd>
                    </div>
                </dl>
                <p v-if="sellerAccount.remark" class="mt-3 text-sm text-zinc-400">{{ sellerAccount.remark }}</p>
            </section>

            <!-- counterparty -->
            <section class="card p-5 sm:p-6">
                <h2 class="mb-3 font-semibold text-white">{{ isBuyer ? 'Seller' : 'Buyer' }}</h2>
                <div class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-full bg-gradient-to-br from-brand-400 to-brand-600 font-bold text-ink-950">{{ (trader.name || '?').charAt(0).toUpperCase() }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-white">{{ trader.name }}</p>
                        <p class="text-xs text-zinc-500">{{ trader.email }}<span v-if="trader.mobile"> · {{ trader.mobile }}</span></p>
                    </div>
                    <div class="flex gap-3 text-sm">
                        <span class="text-up"><i class="ri-thumb-up-line"></i> {{ trader.positive }}</span>
                        <span class="text-down"><i class="ri-thumb-down-line"></i> {{ trader.negative }}</span>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span v-for="v in [{ k: 'ev', l: 'Email' }, { k: 'sv', l: 'Mobile' }, { k: 'kv', l: 'KYC' }]" :key="v.k" class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs" :class="trader[v.k] ? 'bg-up/10 text-up' : 'bg-white/[0.05] text-zinc-500'">
                        <i :class="trader[v.k] ? 'ri-check-line' : 'ri-close-line'"></i>{{ v.l }} verified
                    </span>
                </div>
            </section>

            <!-- terms -->
            <section v-if="trade.paymentDetails || trade.terms" class="card space-y-4 p-5 sm:p-6">
                <div v-if="trade.paymentDetails"><h3 class="mb-2 text-sm font-semibold text-white">Payment details</h3><div class="cms-content text-sm" v-html="trade.paymentDetails"></div></div>
                <div v-if="trade.terms"><h3 class="mb-2 text-sm font-semibold text-white">Terms of trade</h3><div class="cms-content text-sm" v-html="trade.terms"></div></div>
            </section>

            <!-- feedback -->
            <section v-if="(canFeedback && !feedback) || feedback" class="card p-5 sm:p-6">
                <h2 class="mb-3 font-semibold text-white">Feedback</h2>
                <div v-if="feedback && !editingFeedback" class="flex items-start gap-3">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl" :class="feedback.type === 1 ? 'bg-up/10 text-up' : 'bg-down/10 text-down'"><i :class="feedback.type === 1 ? 'ri-thumb-up-line' : 'ri-thumb-down-line'"></i></span>
                    <p class="flex-1 text-sm text-zinc-300">{{ feedback.comment }}</p>
                    <div v-if="feedback.mine" class="flex gap-1">
                        <button class="grid h-8 w-8 place-items-center rounded-lg text-zinc-400 hover:text-white" aria-label="Edit feedback" @click="editingFeedback = true"><i class="ri-edit-line"></i></button>
                        <button class="grid h-8 w-8 place-items-center rounded-lg text-zinc-400 hover:text-down" aria-label="Delete feedback" @click="deleteFeedback"><i class="ri-delete-bin-line"></i></button>
                    </div>
                </div>
                <form v-else class="space-y-3" @submit.prevent="saveFeedback">
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" class="btn py-2.5" :class="fbForm.type === 1 ? 'bg-up/15 text-up ring-1 ring-up/40' : 'btn-ghost'" @click="fbForm.type = 1"><i class="ri-thumb-up-line"></i> Positive</button>
                        <button type="button" class="btn py-2.5" :class="fbForm.type === 0 ? 'bg-down/15 text-down ring-1 ring-down/40' : 'btn-ghost'" @click="fbForm.type = 0"><i class="ri-thumb-down-line"></i> Negative</button>
                    </div>
                    <textarea v-model="fbForm.comment" rows="3" class="field" placeholder="How was this trade?" required></textarea>
                    <div class="flex gap-2">
                        <button class="btn-primary flex-1" :disabled="fbForm.processing">Submit feedback</button>
                        <button v-if="editingFeedback" type="button" class="btn-ghost" @click="editingFeedback = false">Cancel</button>
                    </div>
                </form>
            </section>
        </div>

        <!-- chat -->
        <section class="card order-1 flex h-[560px] flex-col overflow-hidden lg:sticky lg:top-24 lg:order-2">
            <div class="flex items-center gap-3 border-b border-white/[0.06] px-4 py-3">
                <span class="grid h-9 w-9 place-items-center rounded-full bg-white/[0.06] text-sm font-bold text-zinc-200">{{ (trader.name || '?').charAt(0).toUpperCase() }}</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white">{{ trader.name }}</p>
                    <p class="text-[11px] text-zinc-500">Chat is monitored for disputes</p>
                </div>
            </div>
            <div ref="box" class="flex-1 space-y-3 overflow-y-auto p-4">
                <p v-if="!chat.length" class="py-10 text-center text-sm text-zinc-500">No messages yet. Say hello 👋</p>
                <div v-for="m in chat" :key="m.id" class="flex" :class="m.mine ? 'justify-end' : 'justify-start'">
                    <div class="max-w-[80%] rounded-2xl px-3.5 py-2 text-sm" :class="m.admin ? 'border border-amber-400/30 bg-amber-400/10 text-amber-100' : m.mine ? 'rounded-br-md bg-brand-500 text-ink-950' : 'rounded-bl-md bg-white/[0.06] text-zinc-100'">
                        <p v-if="m.admin" class="mb-0.5 text-[10px] font-bold tracking-wider uppercase">Admin</p>
                        <a v-if="m.image" :href="m.file" class="mb-1 block"><img :src="m.image" alt="" class="max-h-48 rounded-lg" /></a>
                        <a v-else-if="m.file" :href="m.file" class="mb-1 flex items-center gap-1 underline"><i class="ri-attachment-2"></i>Attachment</a>
                        <p v-if="m.text && m.text !== 'Attach File'" class="whitespace-pre-wrap break-words">{{ m.text }}</p>
                        <p class="mt-0.5 text-[10px] opacity-60">{{ timeAgo(m.date) }}</p>
                    </div>
                </div>
            </div>
            <form v-if="open" class="flex items-end gap-2 border-t border-white/[0.06] p-3" @submit.prevent="send">
                <label class="grid h-10 w-10 shrink-0 cursor-pointer place-items-center rounded-xl text-lg transition" :class="file ? 'bg-brand-500/15 text-brand-300' : 'text-zinc-400 hover:bg-white/[0.06]'" :title="file ? file.name : 'Attach image'">
                    <i class="ri-image-add-line"></i>
                    <input type="file" accept="image/*" class="hidden" @change="file = $event.target.files[0] || null" />
                </label>
                <textarea v-model="text" rows="1" class="field max-h-28 min-h-10 resize-none py-2.5" placeholder="Type a message" @keydown.enter.exact.prevent="send"></textarea>
                <button class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-500 text-ink-950" :disabled="sending" aria-label="Send">
                    <i :class="sending ? 'ri-loader-4-line animate-spin' : 'ri-send-plane-2-fill'"></i>
                </button>
            </form>
            <p v-else class="border-t border-white/[0.06] p-3 text-center text-xs text-zinc-500">This trade is closed — chat is read-only.</p>
        </section>
    </div>

    <!-- confirm -->
    <Modal :show="!!confirmAction" :title="confirmAction?.label" @close="confirmAction = null">
        <p class="text-sm text-zinc-300">{{ confirmAction?.text }}</p>
        <div v-if="confirmAction?.key === 'release'" class="mt-4 space-y-3">
            <input v-model="releaseForm.security_pin" type="password" inputmode="numeric" class="field" placeholder="Security PIN" autocomplete="off" />
            <div class="flex gap-2">
                <input v-model="releaseForm.p2p_otp" inputmode="numeric" class="field" placeholder="Email OTP" />
                <button type="button" class="btn-ghost shrink-0" :disabled="otpSending" @click="sendOtp"><i :class="otpSending ? 'ri-loader-4-line animate-spin' : 'ri-mail-send-line'"></i> Send OTP</button>
            </div>
        </div>
        <div class="mt-5 flex gap-2">
            <button type="button" class="btn-ghost flex-1" @click="confirmAction = null">Back</button>
            <button type="button" class="flex-1" :class="confirmAction?.danger ? 'btn bg-down text-white' : 'btn-primary'" :disabled="busy || (confirmAction?.key === 'release' && (!releaseForm.security_pin || !releaseForm.p2p_otp))" @click="run">
                <i v-if="busy" class="ri-loader-4-line animate-spin"></i> Confirm
            </button>
        </div>
    </Modal>
</template>
