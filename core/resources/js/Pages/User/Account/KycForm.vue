<script setup>
import { computed, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import { postJson } from '@/utils/http';
import { useToast } from '@/composables/useToast';

const props = defineProps({ user: Object, rejected: String, urls: Object });
const { push } = useToast();

const form = useForm({
    first_name: props.user.firstname || '',
    last_name: props.user.lastname || '',
    maiden_name: '',
    phone: '',
    home_owner: '',
    marital_status: 'Single',
    occupation: '',
    income_source: '',
    annual_income: '',
    account_purpose: '',
    document_type: '',
    id_number: '',
    passport_number: '',
    dl_number: '',
    front_page: null,
    back_page: null,
    selfie: null,
    net_worth_estimate: '',
    experienced_in_investment: 'No',
    bankrupt_by_court: 'No',
    relationship: '',
    relation_full_name: '',
    emergency_phone: '',
    address: '',
});

const opts = {
    home_owner: ['Own Home', 'Family Home', 'Renting Home', 'Others'],
    occupation: ['Student', 'Employee', 'Government Employee', 'Freelancer', 'Others'],
    income_source: ['Salary', 'Savings', 'Others'],
    annual_income: [['<50 million', '< 50 million'], ['50-200 million', '50–200 million'], ['250-500 million', '250–500 million'], ['500-1.5 billion', '500 million – 1.5 billion'], ['250-500 billion', '> 1.5 billion']],
    account_purpose: ['Hedging', 'Investment', 'Speculation', 'Others'],
    net_worth_estimate: [['<500 million', '< 500 million'], ['500-1.0 billion', '500 million – 1 billion'], ['1-5 billion', '1–5 billion'], ['5-10 billion', '5–10 billion'], ['10 billion', '> 10 billion']],
    relationship: [['father', 'Father'], ['brother', 'Brother'], ['wife', 'Wife'], ['others', 'Other']],
};
const pairs = (list) => list.map((o) => (Array.isArray(o) ? { value: o[0], label: o[1] } : { value: o, label: o }));

const docs = [
    { value: 'ID Card', field: 'id_number', icon: 'ri-bank-card-2-line', placeholder: 'ID card number' },
    { value: 'Passport', field: 'passport_number', icon: 'ri-passport-line', placeholder: 'Passport number' },
    { value: 'Driving Licence', field: 'dl_number', icon: 'ri-steering-2-line', placeholder: 'Driving licence number' },
];
const doc = computed(() => docs.find((d) => d.value === form.document_type));

const steps = [
    { title: 'Personal', icon: 'ri-user-3-line', required: ['first_name', 'last_name', 'maiden_name', 'phone', 'home_owner'] },
    { title: 'Financial', icon: 'ri-money-dollar-circle-line', required: ['occupation', 'income_source', 'annual_income', 'account_purpose'] },
    { title: 'Identity', icon: 'ri-passport-line', required: ['document_type'] },
    { title: 'Investor', icon: 'ri-line-chart-line', required: ['net_worth_estimate'] },
    { title: 'Emergency', icon: 'ri-contacts-line', required: ['relation_full_name', 'emergency_phone', 'address'] },
];
const step = ref(0);
const missing = ref([]);

function validStep(i) {
    const need = [...steps[i].required];
    if (i === 2 && doc.value) need.push(doc.value.field);
    missing.value = need.filter((k) => !String(form[k] ?? '').trim());
    return missing.value.length === 0;
}
function next() {
    if (validStep(step.value)) step.value++;
}
function go(i) {
    if (i < step.value) step.value = i;
}
const bad = (k) => missing.value.includes(k);

const MAX = 2 * 1024 * 1024;
function pick(key, e) {
    const f = e.target.files?.[0] || null;
    if (f && f.size > MAX) {
        push('error', 'Please upload a file smaller than 2 MB.');
        e.target.value = '';
        return;
    }
    form[key] = f;
}

const checking = ref(false);
async function submit() {
    if (!validStep(step.value)) return;
    checking.value = true;
    const res = await postJson(props.urls.check, { document_type: form.document_type, doc_number: form[doc.value.field] }).catch(() => null);
    checking.value = false;
    if (res?.status !== 'success') {
        push('error', res?.message || 'Could not verify the document number. Try again.');
        step.value = 2;
        return;
    }
    form.transform((d) => {
        const data = { ...d };
        ['front_page', 'back_page', 'selfie'].forEach((k) => !data[k] && delete data[k]);
        return data;
    }).post(props.urls.submit, { forceFormData: true });
}

const uploads = [
    { key: 'front_page', label: 'Front side', icon: 'ri-file-user-line' },
    { key: 'back_page', label: 'Back side', icon: 'ri-file-list-3-line' },
    { key: 'selfie', label: 'Selfie', icon: 'ri-camera-3-line' },
];
</script>

<template>
    <Head title="KYC verification" />
    <PageHeader title="Verify your identity" subtitle="Complete 5 quick steps to unlock all features" icon="ri-passport-line" />

    <div class="mx-auto max-w-3xl">
        <div v-if="rejected" class="mb-5 flex items-start gap-3 rounded-2xl border border-down/25 bg-down/[0.06] p-4 text-sm text-down">
            <i class="ri-close-circle-line text-xl"></i>
            <div><p class="font-semibold">Your previous submission was rejected</p><p class="opacity-80">{{ rejected }}</p></div>
        </div>

        <!-- stepper -->
        <ol class="mb-5 grid grid-cols-5 gap-1.5 sm:gap-3">
            <li v-for="(s, i) in steps" :key="s.title">
                <button type="button" class="group w-full text-left" :disabled="i > step" @click="go(i)">
                    <span class="block h-1.5 rounded-full transition" :class="i <= step ? 'bg-brand-500' : 'bg-white/10'"></span>
                    <span class="mt-2 flex items-center gap-1.5 text-xs font-medium" :class="i === step ? 'text-white' : i < step ? 'text-brand-300' : 'text-zinc-600'">
                        <i :class="i < step ? 'ri-checkbox-circle-fill' : s.icon"></i><span class="hidden sm:inline">{{ s.title }}</span>
                    </span>
                </button>
            </li>
        </ol>

        <form class="card p-5 sm:p-7" @submit.prevent="step === steps.length - 1 ? submit() : next()">
            <p class="text-xs font-semibold tracking-wider text-brand-300 uppercase">Step {{ step + 1 }} of {{ steps.length }}</p>

            <!-- 1 personal -->
            <div v-show="step === 0" class="mt-2 space-y-4">
                <h2 class="text-lg font-semibold text-white">Personal information</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <label class="block"><span class="kyc-label">First name *</span><input v-model="form.first_name" class="field" :class="bad('first_name') && 'border-down/60'" /></label>
                    <label class="block"><span class="kyc-label">Last name *</span><input v-model="form.last_name" class="field" :class="bad('last_name') && 'border-down/60'" /></label>
                    <label class="block"><span class="kyc-label">Mother's maiden name *</span><input v-model="form.maiden_name" class="field" :class="bad('maiden_name') && 'border-down/60'" /></label>
                    <label class="block"><span class="kyc-label">Phone (with country code) *</span><input v-model="form.phone" inputmode="tel" class="field" placeholder="+1 555 000 0000" :class="bad('phone') && 'border-down/60'" /></label>
                    <label class="block sm:col-span-2">
                        <span class="kyc-label">Home ownership *</span>
                        <select v-model="form.home_owner" class="field" :class="bad('home_owner') && 'border-down/60'">
                            <option value="" disabled>Select</option>
                            <option v-for="o in pairs(opts.home_owner)" :key="o.value" :value="o.value">{{ o.label }}</option>
                        </select>
                    </label>
                </div>
                <fieldset>
                    <legend class="kyc-label">Marital status</legend>
                    <div class="grid grid-cols-3 gap-2">
                        <label v-for="m in ['Married', 'Single', 'Divorced']" :key="m" class="cursor-pointer">
                            <input v-model="form.marital_status" type="radio" :value="m" class="peer sr-only" />
                            <span class="block rounded-xl border border-white/10 py-2.5 text-center text-sm text-zinc-300 transition peer-checked:border-brand-500/60 peer-checked:bg-brand-500/10 peer-checked:text-white">{{ m }}</span>
                        </label>
                    </div>
                </fieldset>
            </div>

            <!-- 2 financial -->
            <div v-show="step === 1" class="mt-2 space-y-4">
                <h2 class="text-lg font-semibold text-white">Financial information</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <label v-for="k in ['occupation', 'income_source', 'annual_income', 'account_purpose']" :key="k" class="block">
                        <span class="kyc-label">{{ { occupation: 'Occupation', income_source: 'Source of income', annual_income: 'Annual income', account_purpose: 'Purpose of account' }[k] }} *</span>
                        <select v-model="form[k]" class="field" :class="bad(k) && 'border-down/60'">
                            <option value="" disabled>Select</option>
                            <option v-for="o in pairs(opts[k])" :key="o.value" :value="o.value">{{ o.label }}</option>
                        </select>
                    </label>
                </div>
            </div>

            <!-- 3 identity -->
            <div v-show="step === 2" class="mt-2 space-y-4">
                <h2 class="text-lg font-semibold text-white">Identity document</h2>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                    <label v-for="d in docs" :key="d.value" class="cursor-pointer">
                        <input v-model="form.document_type" type="radio" :value="d.value" class="peer sr-only" />
                        <span class="flex items-center gap-3 rounded-xl border p-3 text-sm transition peer-checked:border-brand-500/60 peer-checked:bg-brand-500/10 peer-checked:text-white" :class="bad('document_type') ? 'border-down/60 text-zinc-300' : 'border-white/10 text-zinc-300'">
                            <i :class="d.icon" class="text-xl text-brand-300"></i>{{ d.value }}
                        </span>
                    </label>
                </div>
                <label v-if="doc" class="block">
                    <span class="kyc-label">{{ doc.placeholder }} *</span>
                    <input v-model="form[doc.field]" class="field font-mono" :placeholder="doc.placeholder" :class="bad(doc.field) && 'border-down/60'" />
                </label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <label v-for="u in uploads" :key="u.key" class="flex cursor-pointer flex-col items-center gap-1 rounded-2xl border border-dashed px-3 py-5 text-center transition hover:border-brand-500/50" :class="form[u.key] ? 'border-brand-500/50 bg-brand-500/[0.05]' : 'border-white/15'">
                        <i :class="form[u.key] ? 'ri-checkbox-circle-fill text-up' : u.icon + ' text-zinc-500'" class="text-2xl"></i>
                        <span class="text-sm font-medium text-zinc-200">{{ u.label }}</span>
                        <span class="max-w-full truncate text-xs text-zinc-500">{{ form[u.key]?.name || 'JPG / PNG, max 2 MB' }}</span>
                        <input type="file" accept=".jpg,.jpeg,.png" class="hidden" @change="pick(u.key, $event)" />
                    </label>
                </div>
            </div>

            <!-- 4 investor -->
            <div v-show="step === 3" class="mt-2 space-y-4">
                <h2 class="text-lg font-semibold text-white">US stock investor profile</h2>
                <label class="block">
                    <span class="kyc-label">Net worth estimate *</span>
                    <select v-model="form.net_worth_estimate" class="field" :class="bad('net_worth_estimate') && 'border-down/60'">
                        <option value="" disabled>Select</option>
                        <option v-for="o in pairs(opts.net_worth_estimate)" :key="o.value" :value="o.value">{{ o.label }}</option>
                    </select>
                </label>
                <fieldset v-for="q in [{ k: 'experienced_in_investment', t: 'Do you have investment experience?' }, { k: 'bankrupt_by_court', t: 'Have you been declared bankrupt by a court?' }]" :key="q.k">
                    <legend class="kyc-label">{{ q.t }}</legend>
                    <div class="grid grid-cols-2 gap-2">
                        <label v-for="v in ['Yes', 'No']" :key="v" class="cursor-pointer">
                            <input v-model="form[q.k]" type="radio" :value="v" class="peer sr-only" />
                            <span class="block rounded-xl border border-white/10 py-2.5 text-center text-sm text-zinc-300 transition peer-checked:border-brand-500/60 peer-checked:bg-brand-500/10 peer-checked:text-white">{{ v }}</span>
                        </label>
                    </div>
                </fieldset>
            </div>

            <!-- 5 emergency -->
            <div v-show="step === 4" class="mt-2 space-y-4">
                <h2 class="text-lg font-semibold text-white">Emergency contact</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <label class="block">
                        <span class="kyc-label">Relationship</span>
                        <select v-model="form.relationship" class="field">
                            <option value="">Select</option>
                            <option v-for="o in pairs(opts.relationship)" :key="o.value" :value="o.value">{{ o.label }}</option>
                        </select>
                    </label>
                    <label class="block"><span class="kyc-label">Full name *</span><input v-model="form.relation_full_name" class="field" :class="bad('relation_full_name') && 'border-down/60'" /></label>
                    <label class="block"><span class="kyc-label">Phone *</span><input v-model="form.emergency_phone" inputmode="tel" class="field" :class="bad('emergency_phone') && 'border-down/60'" /></label>
                    <label class="block"><span class="kyc-label">Address *</span><input v-model="form.address" class="field" :class="bad('address') && 'border-down/60'" /></label>
                </div>
            </div>

            <p v-if="missing.length" class="mt-4 flex items-center gap-1.5 text-xs text-down"><i class="ri-error-warning-line"></i>Please fill in the highlighted fields.</p>

            <div class="mt-6 flex items-center justify-between gap-3 border-t border-white/[0.06] pt-5">
                <button v-if="step > 0" type="button" class="btn-ghost" @click="step--"><i class="ri-arrow-left-line"></i> Back</button>
                <span v-else></span>
                <button v-if="step < steps.length - 1" type="submit" class="btn-primary px-6">Continue <i class="ri-arrow-right-line"></i></button>
                <button v-else type="submit" class="btn-primary px-6" :disabled="checking || form.processing">
                    <i :class="checking || form.processing ? 'ri-loader-4-line animate-spin' : 'ri-send-plane-line'"></i>
                    {{ checking || form.processing ? 'Submitting…' : 'Submit for review' }}
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>
.kyc-label {
    display: block;
    margin-bottom: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: rgb(212 212 216);
}
</style>
