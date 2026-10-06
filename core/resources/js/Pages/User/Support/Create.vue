<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import FileDrop from '@/Components/UI/FileDrop.vue';

const props = defineProps({ urls: Object });

const form = useForm({ subject: '', priority: '2', message: '', attachments: [] });
const priorities = [
    { value: '1', label: 'Low', hint: 'General question', cls: 'peer-checked:border-zinc-400 peer-checked:bg-white/[0.05]' },
    { value: '2', label: 'Medium', hint: 'Something is not working', cls: 'peer-checked:border-amber-400 peer-checked:bg-amber-400/[0.07]' },
    { value: '3', label: 'High', hint: 'Funds or account at risk', cls: 'peer-checked:border-down peer-checked:bg-down/[0.07]' },
];

function submit() {
    form.transform((d) => {
        const data = { ...d };
        if (!data.attachments.length) delete data.attachments;
        return data;
    }).post(props.urls.store, { forceFormData: true });
}
</script>

<template>
    <Head title="New ticket" />
    <PageHeader title="Open a ticket" subtitle="Describe the issue and we will reply as soon as possible" icon="ri-chat-new-line">
        <template #actions>
            <Link :href="urls.index" class="btn-ghost"><i class="ri-arrow-left-line"></i> All tickets</Link>
        </template>
    </PageHeader>

    <form class="card mx-auto max-w-3xl space-y-5 p-5 sm:p-7" @submit.prevent="submit">
        <div>
            <label for="subject" class="mb-2 block text-sm font-medium text-zinc-300">Subject</label>
            <input id="subject" v-model="form.subject" type="text" maxlength="255" class="field" placeholder="e.g. Deposit not credited" required />
            <p v-if="form.errors.subject" class="mt-1.5 text-xs text-down">{{ form.errors.subject }}</p>
        </div>

        <fieldset>
            <legend class="mb-2 text-sm font-medium text-zinc-300">Priority</legend>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                <label v-for="p in priorities" :key="p.value" class="cursor-pointer">
                    <input v-model="form.priority" type="radio" :value="p.value" class="peer sr-only" />
                    <span class="block rounded-xl border border-white/10 p-3 transition" :class="p.cls">
                        <span class="block text-sm font-semibold text-white">{{ p.label }}</span>
                        <span class="block text-xs text-zinc-500">{{ p.hint }}</span>
                    </span>
                </label>
            </div>
        </fieldset>

        <div>
            <label for="message" class="mb-2 block text-sm font-medium text-zinc-300">Message</label>
            <textarea id="message" v-model="form.message" rows="6" class="field resize-y" placeholder="Include transaction IDs, amounts and what you expected to happen." required></textarea>
            <p v-if="form.errors.message" class="mt-1.5 text-xs text-down">{{ form.errors.message }}</p>
        </div>

        <div>
            <p class="mb-2 text-sm font-medium text-zinc-300">Attachments <span class="font-normal text-zinc-500">(optional)</span></p>
            <FileDrop v-model="form.attachments" />
            <p v-if="form.errors.attachments" class="mt-1.5 text-xs text-down">{{ form.errors.attachments }}</p>
        </div>

        <button type="submit" class="btn-primary w-full py-3" :disabled="form.processing">
            <i :class="form.processing ? 'ri-loader-4-line animate-spin' : 'ri-send-plane-2-line'"></i> Submit ticket
        </button>
    </form>
</template>
