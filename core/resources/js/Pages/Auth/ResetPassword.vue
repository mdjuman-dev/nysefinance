<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import AuthCard from '@/Components/Auth/AuthCard.vue';
import AuthForm from '@/Components/Auth/AuthForm.vue';
import Field from '@/Components/Auth/Field.vue';

defineOptions({ layout: AuthLayout });
const props = defineProps({ token: String, email: String, action: String, securePassword: Boolean, authUrls: Object });

const password = ref('');
const confirm = ref('');
const mismatch = computed(() => confirm.value && password.value !== confirm.value);
</script>

<template>
    <Head title="Reset password" />
    <AuthCard title="Choose a new password" :subtitle="`For ${email}`" icon="ri-lock-unlock-line">
        <AuthForm :action="action" :confirm="() => !mismatch" v-slot="{ busy }">
            <input type="hidden" name="email" :value="email" />
            <input type="hidden" name="token" :value="token" />
            <Field
                v-model="password"
                name="password"
                type="password"
                label="New password"
                icon="ri-lock-2-line"
                autocomplete="new-password"
                :hint="securePassword ? 'Use upper & lower case letters, a number and a symbol.' : ''"
                required
                autofocus
            />
            <Field
                v-model="confirm"
                name="password_confirmation"
                type="password"
                label="Confirm password"
                icon="ri-lock-password-line"
                autocomplete="new-password"
                :error="mismatch ? 'Passwords do not match.' : ''"
                required
            />
            <button type="submit" class="btn-primary w-full py-3.5" :disabled="busy || mismatch">
                <i v-if="busy" class="ri-loader-4-line animate-spin"></i>
                {{ busy ? 'Saving…' : 'Update password' }}
            </button>
        </AuthForm>
        <template #footer>
            <a :href="authUrls.login" class="font-semibold text-brand-300 hover:underline">Back to login</a>
        </template>
    </AuthCard>
</template>
