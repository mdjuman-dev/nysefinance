<script setup>
import { computed, ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import Modal from '@/Components/UI/Modal.vue';
import DynamicFields from '@/Components/UI/DynamicFields.vue';

const props = defineProps({ saved: Object, methods: Array, editing: Object, urls: Object });

/* ---------- add / edit sheet ---------- */
const open = ref(false);
const current = ref(null); // saved row being edited
const form = useForm({ payment_method: '', remark: '' });
const values = ref({});

const method = computed(() => props.methods.find((m) => m.id === Number(form.payment_method)));
const usedIds = computed(() => props.saved.data.map((s) => s.methodId));

function blankValues(m, row = null) {
    const out = {};
    (m?.fields || []).forEach((f) => {
        const prev = row?.data.find((d) => d.raw === f.label || d.raw === f.key);
        out[f.key] = f.type === 'checkbox' ? prev?.values || [] : f.type === 'file' ? null : prev?.value ?? '';
    });
    return out;
}
watch(() => form.payment_method, () => (values.value = blankValues(method.value, current.value)));

function startAdd() {
    current.value = null;
    form.reset();
    form.clearErrors();
    form.payment_method = props.methods.find((m) => !usedIds.value.includes(m.id))?.id || '';
    values.value = blankValues(method.value);
    open.value = true;
}
function startEdit(row) {
    current.value = row;
    form.clearErrors();
    form.payment_method = row.methodId;
    form.remark = row.remark || '';
    values.value = blankValues(method.value, row);
    open.value = true;
}
// /payment-method/create and /edit/{id} open the sheet directly
if (props.editing?.mode === 'create') startAdd();
if (props.editing?.mode === 'edit') {
    const row = props.saved.data.find((r) => r.id === props.editing.id);
    if (row) startEdit(row);
}

function submit() {
    const hasFile = Object.values(values.value).some((v) => v instanceof File);
    form.transform((d) => ({ ...d, ...values.value })).post(current.value ? current.value.save : props.urls.store, {
        forceFormData: hasFile,
        preserveScroll: true,
        onSuccess: () => (open.value = false),
    });
}

/* ---------- delete ---------- */
const removing = ref(null);
function remove(row) {
    if (!confirm(`Remove ${row.name}?`)) return;
    removing.value = row.id;
    router.post(row.delete, {}, { preserveScroll: true, onFinish: () => (removing.value = null) });
}
</script>

<template>
    <Head title="P2P payment methods" />
    <PageHeader title="Payment methods" subtitle="Accounts buyers pay into when you sell on P2P" icon="ri-bank-card-line">
        <template #actions>
            <button class="btn-primary" @click="startAdd"><i class="ri-add-line"></i> Add method</button>
        </template>
    </PageHeader>

    <div v-if="saved.data.length" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
        <article v-for="row in saved.data" :key="row.id" class="card overflow-hidden">
            <div class="h-1.5" :style="{ background: row.color || 'var(--color-brand-500)' }"></div>
            <div class="p-5">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="flex items-center gap-2 font-semibold text-white">
                        <span class="h-2.5 w-2.5 rounded-full" :style="{ background: row.color || 'var(--color-brand-500)' }"></span>{{ row.name }}
                    </h3>
                    <div class="flex gap-1">
                        <button class="grid h-8 w-8 place-items-center rounded-lg text-zinc-400 hover:bg-white/[0.06] hover:text-white" aria-label="Edit" @click="startEdit(row)"><i class="ri-edit-line"></i></button>
                        <button class="grid h-8 w-8 place-items-center rounded-lg text-zinc-400 hover:bg-down/10 hover:text-down" aria-label="Delete" :disabled="removing === row.id" @click="remove(row)">
                            <i :class="removing === row.id ? 'ri-loader-4-line animate-spin' : 'ri-delete-bin-line'"></i>
                        </button>
                    </div>
                </div>
                <dl class="mt-4 space-y-2 text-sm">
                    <div v-for="d in row.data" :key="d.name" class="flex justify-between gap-3">
                        <dt class="text-zinc-500">{{ d.name }}</dt>
                        <dd class="min-w-0 text-right font-medium break-all text-zinc-100">
                            <a v-if="d.file" :href="d.file" class="text-brand-300 hover:underline"><i class="ri-attachment-2"></i> File</a>
                            <template v-else>{{ d.value || '—' }}</template>
                        </dd>
                    </div>
                </dl>
                <p v-if="row.remark" class="mt-3 rounded-xl bg-white/[0.03] p-3 text-xs text-zinc-400">{{ row.remark }}</p>
            </div>
        </article>
    </div>
    <div v-else class="card">
        <EmptyState icon="ri-bank-card-line" title="No payment methods yet" text="Add the accounts you want to receive P2P payments into." />
        <div class="-mt-6 pb-8 text-center"><button class="btn-primary" @click="startAdd"><i class="ri-add-line"></i> Add method</button></div>
    </div>
    <Pagination v-if="saved.meta.last > 1" :meta="saved.meta" class="mt-5" />

    <Modal :show="open" :title="current ? `Edit ${current.name}` : 'Add payment method'" @close="open = false">
        <form class="space-y-4" @submit.prevent="submit">
            <label class="block">
                <span class="mb-1.5 block text-sm font-medium text-zinc-300">Method</span>
                <select v-model="form.payment_method" class="field" :disabled="!!current" required>
                    <option value="" disabled>Select one</option>
                    <option v-for="m in methods" :key="m.id" :value="m.id" :disabled="!current && usedIds.includes(m.id)">{{ m.name }}{{ !current && usedIds.includes(m.id) ? ' (added)' : '' }}</option>
                </select>
                <span v-if="form.errors.payment_method" class="mt-1 block text-xs text-down">{{ form.errors.payment_method }}</span>
            </label>

            <DynamicFields v-if="method" v-model="values" :fields="method.fields" :errors="form.errors" />

            <label class="block">
                <span class="mb-1.5 block text-sm font-medium text-zinc-300">Remark <span class="text-zinc-500">(optional)</span></span>
                <textarea v-model="form.remark" rows="2" maxlength="255" class="field"></textarea>
            </label>

            <button class="btn-primary w-full py-3" :disabled="form.processing || !form.payment_method">
                <i :class="form.processing ? 'ri-loader-4-line animate-spin' : 'ri-save-3-line'"></i> {{ current ? 'Save changes' : 'Add method' }}
            </button>
        </form>
    </Modal>
</template>
