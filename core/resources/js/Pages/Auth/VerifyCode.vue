<script setup>
// One page for every 6-digit code step: password-reset code, email/SMS verification, 2FA.
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import AuthCard from '@/Components/Auth/AuthCard.vue';
import AuthForm from '@/Components/Auth/AuthForm.vue';
import CodeInput from '@/Components/Auth/CodeInput.vue';

defineOptions({ layout: AuthLayout });
const props = defineProps({
    mode: String, // password | email | sms | 2fa
    email: String,
    mobile: String,
    action: String,
    resend: String,
    resendIn: { type: Number, default: 0 },
    resendError: String,
    logout: String,
    authUrls: Object,
});

const page = usePage();
const codeError = computed(() => page.props.errors?.code);
const code = ref(null);

const mask = (s) => (s ? s.replace(/^(.{2}).*(@.*)$/, '$1•••$2') : '');
const copy = computed(
    () =>
        ({
            password: { title: 'Check your inbox', icon: 'ri-mail-open-line', text: `We sent a 6-digit code to ${mask(props.email)}.` },
            email: { title: 'Verify your email', icon: 'ri-mail-check-line', text: `Enter the 6-digit code we sent to ${mask(props.email)}.` },
            sms: { title: 'Verify your phone', icon: 'ri-smartphone-line', text: `Enter the 6-digit code we sent to •••${(props.mobile || '').slice(-3)}.` },
            '2fa': { title: 'Two-factor authentication', icon: 'ri-shield-keyhole-line', text: 'Open your authenticator app and enter the current 6-digit code.' },
        })[props.mode],
);

// resend countdown (server enforces the same 2-minute window)
const left = ref(props.resendIn);
let timer;
onMounted(() => {
    timer = setInterval(() => left.value > 0 && left.value--, 1000);
});
onBeforeUnmount(() => clearInterval(timer));
const clock = computed(() => `${Math.floor(left.value / 60)}:${String(left.value % 60).padStart(2, '0')}`);
</script>

<template>
    <Head :title="copy.title" />
    <AuthCard :title="copy.title" :subtitle="copy.text" :icon="copy.icon">
        <AuthForm :action="action" :confirm="() => code?.complete" v-slot="{ busy }">
            <input v-if="mode === 'password'" type="hidden" name="email" :value="email" />
            <CodeInput ref="code" />
            <p v-if="codeError" class="flex items-center gap-1 text-xs text-down"><i class="ri-error-warning-line"></i>{{ codeError }}</p>

            <button type="submit" class="btn-primary w-full py-3.5" :disabled="busy">
                <i v-if="busy" class="ri-loader-4-line animate-spin"></i>
                {{ busy ? 'Verifying…' : 'Verify' }}
            </button>
        </AuthForm>

        <div v-if="resend" class="mt-5 border-t border-white/[0.06] pt-4 text-center text-sm text-zinc-400">
            <template v-if="mode === 'password'">
                Didn't get it? Check spam, or <a :href="resend" class="font-semibold text-brand-300 hover:underline">try again</a>
            </template>
            <template v-else-if="left > 0">
                Resend code in <span class="font-mono text-white">{{ clock }}</span>
            </template>
            <template v-else>
                Didn't get it? <a :href="resend" class="font-semibold text-brand-300 hover:underline">Resend code</a>
            </template>
            <p v-if="resendError" class="mt-2 text-xs text-down">{{ resendError }}</p>
        </div>

        <template #footer>
            <a v-if="logout" :href="logout" class="inline-flex items-center gap-1.5 text-zinc-400 hover:text-white"><i class="ri-logout-box-r-line"></i>Use a different account</a>
            <a v-else :href="authUrls.login" class="font-semibold text-brand-300 hover:underline">Back to login</a>
        </template>
    </AuthCard>
</template>
