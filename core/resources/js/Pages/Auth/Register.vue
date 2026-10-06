<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import AuthCard from '@/Components/Auth/AuthCard.vue';
import AuthForm from '@/Components/Auth/AuthForm.vue';
import Field from '@/Components/Auth/Field.vue';
import Captcha from '@/Components/Auth/Captcha.vue';
import PolicyLinks from '@/Components/Auth/PolicyLinks.vue';
import { postJson } from '@/utils/http';

defineOptions({ layout: AuthLayout });
const props = defineProps({
    heading: String,
    subheading: String,
    reward: String,
    reference: String,
    agree: Boolean,
    securePassword: Boolean,
    old: Object,
    captcha: String,
    policies: Array,
    authUrls: Object,
});

const form = ref({
    firstname: props.old?.firstname || '',
    lastname: props.old?.lastname || '',
    email: props.old?.email || '',
    password: '',
    password_confirmation: '',
});
const agreed = ref(!!props.old?.agree);
const emailTaken = ref('');

async function checkEmail() {
    emailTaken.value = '';
    if (!/^\S+@\S+\.\S+$/.test(form.value.email)) return;
    const res = await postJson(props.authUrls.checkUser, { email: form.value.email }).catch(() => null);
    if (res?.data) emailTaken.value = 'This email is already registered — try logging in.';
}

// Same rules the server enforces when "secure password" is on.
const checks = computed(() => {
    const p = form.value.password;
    return [
        { label: '6+ characters', ok: p.length >= 6 },
        { label: 'Upper & lower case', ok: /[a-z]/.test(p) && /[A-Z]/.test(p) },
        { label: 'A number', ok: /\d/.test(p) },
        { label: 'A symbol', ok: /[^A-Za-z0-9]/.test(p) },
    ];
});
const strength = computed(() => checks.value.filter((c) => c.ok).length);
const strengthColor = computed(() => ['bg-white/10', 'bg-down', 'bg-amber-400', 'bg-brand-400', 'bg-up'][strength.value]);
const mismatch = computed(() => form.value.password_confirmation && form.value.password !== form.value.password_confirmation);

const canSubmit = computed(() => !emailTaken.value && !mismatch.value && (!props.agree || agreed.value));
</script>

<template>
    <Head title="Create account" />
    <AuthCard :title="heading || 'Create your account'" :subtitle="subheading || 'It takes less than a minute.'">
        <template #sub>
            <div v-if="reward" class="mt-4 flex items-center gap-3 rounded-2xl border border-brand-500/20 bg-brand-500/[0.07] px-4 py-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-brand-500/15 text-lg text-brand-300"><i class="ri-gift-2-line"></i></span>
                <p class="text-sm font-medium text-brand-300">{{ reward }}</p>
            </div>
        </template>

        <AuthForm :action="authUrls.register" :confirm="() => canSubmit" v-slot="{ busy }">
            <div v-if="reference" class="flex items-center justify-between gap-3 rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3 text-sm">
                <span class="flex items-center gap-2 text-zinc-400"><i class="ri-user-shared-line text-brand-300"></i>Referred by</span>
                <span class="font-semibold text-white">{{ reference }}</span>
                <input type="hidden" name="referBy" :value="reference" />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <Field v-model="form.firstname" name="firstname" label="First name" autocomplete="given-name" required />
                <Field v-model="form.lastname" name="lastname" label="Last name" autocomplete="family-name" required />
            </div>
            <Field
                v-model="form.email"
                name="email"
                type="email"
                label="Email"
                icon="ri-mail-line"
                autocomplete="email"
                placeholder="you@example.com"
                :error="emailTaken"
                required
                @blur="checkEmail"
                @input="emailTaken = ''"
            />

            <div>
                <Field v-model="form.password" name="password" type="password" label="Password" icon="ri-lock-2-line" autocomplete="new-password" required />
                <div v-if="form.password" class="mt-2">
                    <div class="flex gap-1">
                        <span v-for="i in 4" :key="i" class="h-1 flex-1 rounded-full transition" :class="i <= strength ? strengthColor : 'bg-white/10'"></span>
                    </div>
                    <ul v-if="securePassword" class="mt-2 grid grid-cols-2 gap-x-3 gap-y-1 text-xs">
                        <li v-for="c in checks" :key="c.label" class="flex items-center gap-1" :class="c.ok ? 'text-up' : 'text-zinc-500'">
                            <i :class="c.ok ? 'ri-check-line' : 'ri-close-line'"></i>{{ c.label }}
                        </li>
                    </ul>
                </div>
            </div>
            <Field
                v-model="form.password_confirmation"
                name="password_confirmation"
                type="password"
                label="Confirm password"
                icon="ri-lock-password-line"
                autocomplete="new-password"
                :error="mismatch ? 'Passwords do not match.' : ''"
                required
            />

            <Captcha :html="captcha" />

            <label v-if="agree" class="flex cursor-pointer items-start gap-2.5 text-sm text-zinc-400 select-none">
                <input v-model="agreed" type="checkbox" name="agree" class="mt-0.5 h-4 w-4 shrink-0 rounded accent-brand-500" required />
                <span>I agree with <PolicyLinks :policies="policies" /></span>
            </label>

            <button type="submit" class="btn-primary w-full py-3.5" :disabled="busy || !canSubmit">
                <i v-if="busy" class="ri-loader-4-line animate-spin"></i>
                {{ busy ? 'Creating account…' : 'Create account' }}
            </button>
        </AuthForm>

        <template #footer>
            Already have an account? <a :href="authUrls.login" class="font-semibold text-brand-300 hover:underline">Log in</a>
        </template>
    </AuthCard>
</template>
