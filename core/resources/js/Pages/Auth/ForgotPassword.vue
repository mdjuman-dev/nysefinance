<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import AuthCard from '@/Components/Auth/AuthCard.vue';
import AuthForm from '@/Components/Auth/AuthForm.vue';
import Field from '@/Components/Auth/Field.vue';
import Captcha from '@/Components/Auth/Captcha.vue';

defineOptions({ layout: AuthLayout });
const props = defineProps({ action: String, old: Object, captcha: String, authUrls: Object });
const value = ref(props.old?.value || '');
</script>

<template>
    <Head title="Forgot password" />
    <AuthCard title="Reset your password" subtitle="Enter your email or username and we'll send you a 6-digit verification code." icon="ri-key-2-line">
        <AuthForm :action="action" v-slot="{ busy }">
            <Field v-model="value" name="value" label="Email or username" icon="ri-user-3-line" autocomplete="username" required autofocus />
            <Captcha :html="captcha" />
            <button type="submit" class="btn-primary w-full py-3.5" :disabled="busy">
                <i v-if="busy" class="ri-loader-4-line animate-spin"></i>
                {{ busy ? 'Sending…' : 'Send code' }}
            </button>
        </AuthForm>
        <template #footer>
            Remembered it? <a :href="authUrls.login" class="font-semibold text-brand-300 hover:underline">Back to login</a>
        </template>
    </AuthCard>
</template>
