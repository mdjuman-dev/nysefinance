<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import { useToast } from '@/composables/useToast';
import { copyText } from '@/utils/clipboard';

const props = defineProps({ enabled: Boolean, secret: String, qr: String, urls: Object });
const toast = useToast();

const form = useForm({ key: props.secret, code: '' });

function submit() {
    form.key = props.secret;
    form.post(props.enabled ? props.urls.disable : props.urls.enable, {
        preserveScroll: true,
        onFinish: () => form.reset('code'),
    });
}

async function copySecret() {
    (await copyText(props.secret)) ? toast.success('Setup key copied') : toast.error('Could not copy');
}

const steps = [
    { icon: 'ri-download-cloud-2-line', text: 'Install Google Authenticator (or any TOTP app) on your phone.' },
    { icon: 'ri-qr-scan-2-line', text: 'Scan the QR code or enter the setup key manually.' },
    { icon: 'ri-shield-check-line', text: 'Enter the 6-digit code from the app to confirm.' },
];
</script>

<template>
    <Head title="Security" />
    <PageHeader title="Security" subtitle="Protect your account with two-factor authentication" icon="ri-shield-keyhole-line">
        <template #actions>
            <a :href="urls.password" class="btn-ghost"><i class="ri-lock-password-line"></i> Change password</a>
        </template>
    </PageHeader>

    <!-- Status banner -->
    <div class="mb-5 flex items-center gap-4 rounded-3xl border p-5" :class="enabled ? 'border-up/20 bg-up/[0.06]' : 'border-amber-400/20 bg-amber-400/[0.06]'">
        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl text-2xl" :class="enabled ? 'bg-up/15 text-up' : 'bg-amber-400/15 text-amber-300'">
            <i :class="enabled ? 'ri-shield-check-fill' : 'ri-shield-flash-line'"></i>
        </span>
        <div>
            <p class="font-semibold text-white">Two-factor authentication is {{ enabled ? 'on' : 'off' }}</p>
            <p class="text-sm text-zinc-400">{{ enabled ? 'A code from your authenticator app is required when you sign in.' : 'Add a second step to sign-in so a stolen password is not enough.' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <!-- Setup -->
        <section v-if="!enabled" class="card p-5 sm:p-6">
            <h2 class="font-semibold text-white">1. Add your account</h2>
            <ol class="mt-4 space-y-3">
                <li v-for="(s, i) in steps" :key="i" class="flex items-start gap-3 text-sm text-zinc-400">
                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-lg bg-white/[0.05] text-brand-300"><i :class="s.icon"></i></span>
                    {{ s.text }}
                </li>
            </ol>
            <div class="mt-5 flex justify-center rounded-2xl bg-white p-4">
                <img :src="qr" alt="2FA QR code" class="h-44 w-44" />
            </div>
            <label class="mt-5 mb-2 block text-sm font-medium text-zinc-300">Setup key</label>
            <div class="flex items-center gap-2 rounded-xl border border-white/10 bg-ink-850 p-1.5 pl-3">
                <span class="min-w-0 flex-1 truncate font-mono text-sm tracking-wider text-zinc-200">{{ secret }}</span>
                <button type="button" class="btn-ghost shrink-0 px-3 py-1.5 text-xs" @click="copySecret"><i class="ri-file-copy-line"></i> Copy</button>
            </div>
        </section>

        <!-- Verify -->
        <section class="card p-5 sm:p-6" :class="enabled ? 'lg:col-span-1' : ''">
            <h2 class="font-semibold text-white">{{ enabled ? 'Turn off 2FA' : '2. Confirm and enable' }}</h2>
            <p class="mt-1 text-sm text-zinc-500">Enter the current 6-digit code from your authenticator app.</p>
            <form class="mt-5 space-y-4" @submit.prevent="submit">
                <input
                    v-model="form.code"
                    type="text"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    maxlength="6"
                    placeholder="000000"
                    class="field text-center font-mono text-2xl tracking-[0.5em]"
                    required
                />
                <p v-if="form.errors.code" class="text-xs text-down">{{ form.errors.code }}</p>
                <button type="submit" class="w-full py-3" :class="enabled ? 'btn bg-down/15 text-down hover:bg-down/25' : 'btn-primary'" :disabled="form.processing || form.code.length < 6">
                    <i :class="form.processing ? 'ri-loader-4-line animate-spin' : enabled ? 'ri-shield-cross-line' : 'ri-shield-check-line'"></i>
                    {{ enabled ? 'Disable 2FA' : 'Enable 2FA' }}
                </button>
            </form>
        </section>
    </div>
</template>
