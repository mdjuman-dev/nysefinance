<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import AuthCard from '@/Components/Auth/AuthCard.vue';
import AuthForm from '@/Components/Auth/AuthForm.vue';
import Field from '@/Components/Auth/Field.vue';
import Captcha from '@/Components/Auth/Captcha.vue';
import PolicyLinks from '@/Components/Auth/PolicyLinks.vue';

defineOptions({ layout: AuthLayout });
const props = defineProps({ heading: String, subheading: String, old: Object, captcha: String, policies: Array, authUrls: Object });

const username = ref(props.old?.username || props.old?.email || '');
const password = ref('');
</script>

<template>
    <Head title="Log in" />
    <AuthCard :title="heading || 'Welcome back'" :subtitle="subheading || 'Log in to continue trading.'">
        <AuthForm :action="authUrls.login" v-slot="{ busy }">
            <Field v-model="username" name="username" label="Email or username" icon="ri-user-3-line" autocomplete="username" placeholder="you@example.com" required autofocus />
            <Field v-model="password" name="password" type="password" label="Password" icon="ri-lock-2-line" autocomplete="current-password" placeholder="••••••••" required>
                <template #aside>
                    <a :href="authUrls.forgot" class="text-xs font-normal text-brand-300 hover:underline">Forgot password?</a>
                </template>
            </Field>

            <Captcha :html="captcha" />

            <label class="flex cursor-pointer items-center gap-2.5 text-sm text-zinc-400 select-none">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded accent-brand-500" :checked="!!old?.remember" />
                Keep me signed in
            </label>

            <button type="submit" class="btn-primary w-full py-3.5" :disabled="busy">
                <i v-if="busy" class="ri-loader-4-line animate-spin"></i>
                {{ busy ? 'Signing in…' : 'Log in' }}
            </button>

            <p v-if="policies?.length" class="text-center text-xs leading-relaxed text-zinc-500">
                By continuing you agree to our <PolicyLinks :policies="policies" />.
            </p>
        </AuthForm>

        <template #footer>
            New here? <a :href="authUrls.register" class="font-semibold text-brand-300 hover:underline">Create an account</a>
        </template>
    </AuthCard>
</template>
