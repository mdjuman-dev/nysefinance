<script setup>
import { computed, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';

const props = defineProps({ securePassword: Boolean, action: String });

const form = useForm({ current_password: '', password: '', password_confirmation: '' });
const show = ref(false);

const checks = computed(() => {
    const p = form.password;
    return [
        { label: 'At least 6 characters', ok: p.length >= 6 },
        { label: 'Upper & lower case', ok: /[a-z]/.test(p) && /[A-Z]/.test(p), secure: true },
        { label: 'A number', ok: /\d/.test(p), secure: true },
        { label: 'A symbol', ok: /[^A-Za-z0-9]/.test(p), secure: true },
    ].filter((c) => !c.secure || props.securePassword);
});
const mismatch = computed(() => form.password_confirmation && form.password !== form.password_confirmation);

function submit() {
    form.post(props.action, { preserveScroll: true, onSuccess: () => form.reset() });
}
const fields = [
    { key: 'current_password', label: 'Current password', auto: 'current-password' },
    { key: 'password', label: 'New password', auto: 'new-password' },
    { key: 'password_confirmation', label: 'Confirm new password', auto: 'new-password' },
];
</script>

<template>
    <Head title="Change password" />
    <PageHeader title="Change password" subtitle="Use a strong password you don't use anywhere else" icon="ri-lock-password-line" />

    <div class="mx-auto grid max-w-4xl grid-cols-1 gap-5 lg:grid-cols-[1fr_280px]">
        <form class="card space-y-4 p-5 sm:p-7" @submit.prevent="submit">
            <label v-for="f in fields" :key="f.key" class="block">
                <span class="mb-1.5 block text-sm font-medium text-zinc-300">{{ f.label }}</span>
                <span class="relative block">
                    <input v-model="form[f.key]" :type="show ? 'text' : 'password'" :autocomplete="f.auto" class="field pr-12" required />
                    <button type="button" class="absolute top-1/2 right-3 -translate-y-1/2 text-lg text-zinc-500 hover:text-zinc-200" :aria-label="show ? 'Hide passwords' : 'Show passwords'" @click="show = !show">
                        <i :class="show ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                    </button>
                </span>
                <span v-if="form.errors[f.key]" class="mt-1 block text-xs text-down">{{ form.errors[f.key] }}</span>
                <span v-else-if="f.key === 'password_confirmation' && mismatch" class="mt-1 block text-xs text-down">Passwords do not match.</span>
            </label>
            <button type="submit" class="btn-primary w-full py-3" :disabled="form.processing || mismatch">
                <i :class="form.processing ? 'ri-loader-4-line animate-spin' : 'ri-shield-check-line'"></i>
                {{ form.processing ? 'Updating…' : 'Update password' }}
            </button>
        </form>

        <aside class="card h-fit p-5">
            <h3 class="mb-3 text-sm font-semibold text-white">Password requirements</h3>
            <ul class="space-y-2 text-sm">
                <li v-for="c in checks" :key="c.label" class="flex items-center gap-2" :class="c.ok ? 'text-up' : 'text-zinc-500'">
                    <i :class="c.ok ? 'ri-checkbox-circle-fill' : 'ri-checkbox-blank-circle-line'"></i>{{ c.label }}
                </li>
            </ul>
            <p class="mt-4 border-t border-white/[0.06] pt-4 text-xs text-zinc-500">Tip: turn on two-factor authentication for extra protection.</p>
        </aside>
    </div>
</template>
