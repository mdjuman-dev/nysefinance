<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import CoinIcon from '@/Components/CoinIcon.vue';
import DataList from '@/Components/UI/DataList.vue';
import Modal from '@/Components/UI/Modal.vue';
import DepositSheet from '@/Components/Wallet/DepositSheet.vue';
import WithdrawSheet from '@/Components/Wallet/WithdrawSheet.vue';
import { formatAmount, formatMoney } from '@/utils/format';
import { formatDateTime } from '@/utils/display';
import { getJson } from '@/utils/http';

const props = defineProps({
    type: String,
    typeTitle: String,
    wallet: Object,
    stats: Array,
    transactions: Object,
    totalTransactions: Number,
    can: Object,
    transfer: Object,
    open: String,
    gateways: Array,
    withdrawMethods: Array,
    urls: Object,
});

const sheet = ref(props.open === 'transfer' && (props.can.transferUser || props.can.transferWallet) ? 'transfer' : null);
const available = computed(() => props.wallet.balance);
const total = computed(() => props.wallet.balance + props.wallet.inOrder);
const usd = computed(() => total.value * (props.wallet.rate || 0));

const actions = computed(() =>
    [
        props.can.deposit && { key: 'deposit', label: 'Deposit', icon: 'ri-download-2-line', primary: true },
        props.can.withdraw && { key: 'withdraw', label: 'Withdraw', icon: 'ri-upload-2-line' },
        (props.can.transferUser || props.can.transferWallet) && { key: 'transfer', label: 'Transfer', icon: 'ri-arrow-left-right-line' },
    ].filter(Boolean),
);

/* ---------- transfer ---------- */
const mode = ref(props.can.transferUser ? 'user' : 'wallet');
const by = ref('username');
const userForm = useForm({ username: '', uid: '', transfer_amount: '', security_pin: '', currency: props.transfer.currencyId, wallet_type: props.type });
const walletForm = useForm({ transfer_amount: '', to_wallet: props.transfer.otherWallets[0]?.value || '', currency: props.transfer.currencyId, from_wallet: props.type });

const receiver = ref(null);
const lookup = ref('');
let timer;
watch(
    () => [by.value, userForm.username, userForm.uid],
    () => {
        clearTimeout(timer);
        receiver.value = null;
        lookup.value = '';
        const value = by.value === 'uid' ? userForm.uid : userForm.username;
        if (!value || value.length < 3) return;
        timer = setTimeout(async () => {
            lookup.value = 'loading';
            const res = await getJson(props.urls.findUser, { type: by.value, username: userForm.username, uid: userForm.uid }).catch(() => null);
            if (res?.status === 'success') {
                receiver.value = res;
                lookup.value = '';
            } else {
                lookup.value = res?.message || 'Receiver not found';
            }
        }, 450);
    },
);

const userFee = computed(() => (Number(userForm.transfer_amount) || 0) * (props.transfer.userCharge / 100));
const walletFee = computed(() => (Number(walletForm.transfer_amount) || 0) * (props.transfer.walletCharge / 100));

function sendToUser() {
    userForm
        .transform((d) => {
            const data = { ...d };
            if (by.value === 'uid') data.username = '';
            else data.uid = '';
            return data;
        })
        .post(props.urls.transferUser, {
            preserveScroll: true,
            onSuccess: () => {
                userForm.reset('transfer_amount', 'security_pin', 'username', 'uid');
                sheet.value = null;
            },
        });
}
function sendToWallet() {
    walletForm.post(props.urls.transferWallet, {
        preserveScroll: true,
        onSuccess: () => {
            walletForm.reset('transfer_amount');
            sheet.value = null;
        },
    });
}

/* ---------- history ---------- */
const columns = [
    { key: 'details', label: 'Transaction', wide: true },
    { key: 'amount', label: 'Amount', align: 'right' },
    { key: 'post', label: 'Balance after', align: 'right' },
    { key: 'date', label: 'Date', align: 'right' },
];
</script>

<template>
    <Head :title="`${wallet.symbol} wallet`" />

    <div class="mb-4 flex items-center gap-2 text-sm">
        <Link :href="urls.back" class="inline-flex items-center gap-1 text-zinc-400 hover:text-white"><i class="ri-arrow-left-line"></i>{{ typeTitle.split(':')[0] }}</Link>
        <span class="text-zinc-600">/</span>
        <span class="text-zinc-200">{{ wallet.symbol }}</span>
    </div>

    <!-- balance hero -->
    <section class="card relative mb-5 overflow-hidden p-5 sm:p-7">
        <div class="pointer-events-none absolute -top-24 -right-24 h-72 w-72 rounded-full bg-brand-500/10 blur-3xl"></div>
        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="flex items-center gap-4">
                <CoinIcon :src="wallet.image" :symbol="wallet.symbol" size="h-14 w-14" />
                <div>
                    <p class="flex items-center gap-2 text-sm text-zinc-400">
                        {{ wallet.name }}
                        <span class="rounded-md bg-white/[0.06] px-1.5 py-0.5 text-[10px] font-semibold tracking-wider text-zinc-300 uppercase">{{ type }}</span>
                    </p>
                    <p class="font-mono text-3xl font-bold text-white sm:text-4xl">{{ formatAmount(total) }} <span class="text-lg text-zinc-400">{{ wallet.symbol }}</span></p>
                    <p class="text-sm text-zinc-500">≈ ${{ formatMoney(usd) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 sm:flex sm:gap-3">
                <button
                    v-for="a in actions"
                    :key="a.key"
                    type="button"
                    :class="a.primary ? 'btn-primary' : 'btn-ghost'"
                    class="flex-col gap-1 py-3 sm:flex-row sm:px-5"
                    @click="sheet = a.key"
                >
                    <i :class="a.icon" class="text-lg"></i>{{ a.label }}
                </button>
                <Link :href="urls.trade" class="btn-ghost flex-col gap-1 py-3 sm:flex-row sm:px-5"><i class="ri-line-chart-line text-lg"></i>Trade</Link>
            </div>
        </div>
        <div class="relative mt-6 grid grid-cols-1 gap-3 border-t border-white/[0.06] pt-5 sm:grid-cols-3">
            <div><p class="text-xs text-zinc-500">Available</p><p class="font-mono text-lg font-semibold text-white">{{ formatAmount(available) }}</p></div>
            <div><p class="text-xs text-zinc-500">In open orders</p><p class="font-mono text-lg font-semibold text-amber-300">{{ formatAmount(wallet.inOrder) }}</p></div>
            <div><p class="text-xs text-zinc-500">Total</p><p class="font-mono text-lg font-semibold text-white">{{ formatAmount(total) }}</p></div>
        </div>
    </section>

    <!-- stats -->
    <div class="mb-6 grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-6">
        <Link v-for="s in stats" :key="s.label" :href="s.href" class="card card-hover p-4">
            <i :class="s.icon" class="text-xl text-brand-300"></i>
            <p class="mt-2 font-mono text-lg font-bold text-white">{{ s.money ? (wallet.sign || '') + formatAmount(s.value) : s.value.toLocaleString() }}</p>
            <p class="text-xs text-zinc-500">{{ s.label }}</p>
        </Link>
    </div>

    <!-- history -->
    <div class="mb-3 flex items-center justify-between">
        <h2 class="font-semibold text-white">Transaction history <span class="text-sm font-normal text-zinc-500">({{ totalTransactions.toLocaleString() }})</span></h2>
        <Link :href="urls.transactions" class="text-sm text-brand-300 hover:underline">View all</Link>
    </div>
    <DataList :rows="transactions.data" :columns="columns" :meta="transactions.meta" empty-icon="ri-file-list-3-line" empty-title="No transactions yet">
        <template #cell-details="{ row }">
            <div class="flex min-w-0 items-center gap-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl" :class="row.type === '+' ? 'bg-up/10 text-up' : 'bg-down/10 text-down'">
                    <i :class="row.type === '+' ? 'ri-arrow-down-line' : 'ri-arrow-up-line'"></i>
                </span>
                <div class="min-w-0">
                    <p class="truncate text-sm text-white">{{ row.details }}</p>
                    <p class="font-mono text-[11px] text-zinc-500">{{ row.trx }}</p>
                </div>
            </div>
        </template>
        <template #cell-amount="{ row }">
            <span class="font-mono font-semibold" :class="row.type === '+' ? 'text-up' : 'text-down'">{{ row.type }}{{ formatAmount(row.amount) }}</span>
            <span v-if="row.charge" class="block text-[11px] text-zinc-500">fee {{ formatAmount(row.charge) }}</span>
        </template>
        <template #cell-post="{ row }"><span class="font-mono text-zinc-300">{{ formatAmount(row.post) }}</span></template>
        <template #cell-date="{ row }"><span class="text-xs text-zinc-400">{{ formatDateTime(row.date) }}</span></template>
    </DataList>

    <DepositSheet :show="sheet === 'deposit'" :gateways="gateways" :action="urls.depositInsert" @close="sheet = null" />
    <WithdrawSheet :show="sheet === 'withdraw'" :methods="withdrawMethods" :urls="urls" @close="sheet = null" />

    <!-- transfer -->
    <Modal :show="sheet === 'transfer'" :title="`Transfer ${wallet.symbol}`" @close="sheet = null">
        <div v-if="can.transferUser && can.transferWallet" class="mb-4 grid grid-cols-2 gap-1 rounded-xl bg-white/[0.04] p-1">
            <button v-for="m in [{ k: 'user', l: 'To another user' }, { k: 'wallet', l: 'Between my wallets' }]" :key="m.k" type="button" class="rounded-lg py-2 text-sm font-medium transition" :class="mode === m.k ? 'bg-brand-500 text-ink-950' : 'text-zinc-400 hover:text-white'" @click="mode = m.k">{{ m.l }}</button>
        </div>

        <form v-if="mode === 'user'" class="space-y-4" @submit.prevent="sendToUser">
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-zinc-300">Receiver</span>
                    <span class="flex gap-1 rounded-lg bg-white/[0.04] p-0.5 text-xs">
                        <button v-for="o in [{ k: 'username', l: 'Username / Email' }, { k: 'uid', l: 'UID' }]" :key="o.k" type="button" class="rounded-md px-2 py-1 transition" :class="by === o.k ? 'bg-white/10 text-white' : 'text-zinc-500'" @click="by = o.k">{{ o.l }}</button>
                    </span>
                </div>
                <input v-if="by === 'username'" v-model.trim="userForm.username" class="field" placeholder="Username or email" required />
                <input v-else v-model.trim="userForm.uid" inputmode="numeric" class="field font-mono" placeholder="Receiver UID" required />
                <div v-if="receiver" class="mt-2 flex items-center gap-2 rounded-xl border border-up/20 bg-up/[0.06] px-3 py-2 text-sm">
                    <i class="ri-user-follow-line text-up"></i>
                    <span class="min-w-0 truncate text-zinc-200">{{ receiver.fullname }} <span class="text-zinc-500">· {{ receiver.email }}</span></span>
                </div>
                <p v-else-if="lookup === 'loading'" class="mt-2 text-xs text-zinc-500">Looking up receiver…</p>
                <p v-else-if="lookup" class="mt-2 text-xs text-down">{{ lookup }}</p>
            </div>
            <div>
                <div class="mb-2 flex justify-between text-sm"><span class="font-medium text-zinc-300">Amount</span><span class="text-xs text-zinc-500">Available {{ formatAmount(available) }} {{ wallet.symbol }}</span></div>
                <div class="relative">
                    <input v-model="userForm.transfer_amount" type="number" step="any" min="0" class="field pr-20 font-mono" placeholder="0.00" required />
                    <button type="button" class="absolute top-1/2 right-2 -translate-y-1/2 rounded-lg bg-brand-500/15 px-2.5 py-1 text-xs font-bold text-brand-300" @click="userForm.transfer_amount = available">MAX</button>
                </div>
            </div>
            <input v-model="userForm.security_pin" type="password" inputmode="numeric" autocomplete="off" class="field" placeholder="Security PIN" required />
            <div v-if="Number(userForm.transfer_amount) > 0" class="space-y-1.5 rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-sm">
                <div class="flex justify-between"><span class="text-zinc-500">Fee ({{ transfer.userCharge }}%)</span><span class="font-mono text-zinc-200">{{ formatAmount(userFee) }}</span></div>
                <div class="flex justify-between"><span class="text-zinc-300">Total deducted</span><span class="font-mono font-semibold text-white">{{ formatAmount(Number(userForm.transfer_amount) + userFee) }} {{ wallet.symbol }}</span></div>
            </div>
            <button class="btn-primary w-full py-3" :disabled="userForm.processing || lookup === 'loading'">
                <i :class="userForm.processing ? 'ri-loader-4-line animate-spin' : 'ri-send-plane-line'"></i> Send
            </button>
        </form>

        <form v-else class="space-y-4" @submit.prevent="sendToWallet">
            <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/[0.03] p-3 text-sm">
                <span class="flex-1 text-center"><span class="block text-xs text-zinc-500">From</span><span class="font-semibold text-white capitalize">{{ type }}</span></span>
                <i class="ri-arrow-right-line text-brand-300"></i>
                <select v-model="walletForm.to_wallet" class="field flex-1 py-2" required>
                    <option v-for="w in transfer.otherWallets" :key="w.value" :value="w.value">{{ w.label }}</option>
                </select>
            </div>
            <div>
                <div class="mb-2 flex justify-between text-sm"><span class="font-medium text-zinc-300">Amount</span><span class="text-xs text-zinc-500">Available {{ formatAmount(available) }} {{ wallet.symbol }}</span></div>
                <div class="relative">
                    <input v-model="walletForm.transfer_amount" type="number" step="any" min="0" class="field pr-20 font-mono" placeholder="0.00" required />
                    <button type="button" class="absolute top-1/2 right-2 -translate-y-1/2 rounded-lg bg-brand-500/15 px-2.5 py-1 text-xs font-bold text-brand-300" @click="walletForm.transfer_amount = available">MAX</button>
                </div>
                <p v-if="transfer.walletCharge" class="mt-1.5 text-xs text-zinc-500">Fee {{ transfer.walletCharge }}% · {{ formatAmount(walletFee) }} {{ wallet.symbol }}</p>
            </div>
            <button class="btn-primary w-full py-3" :disabled="walletForm.processing">
                <i :class="walletForm.processing ? 'ri-loader-4-line animate-spin' : 'ri-arrow-left-right-line'"></i> Transfer
            </button>
        </form>
    </Modal>
</template>
