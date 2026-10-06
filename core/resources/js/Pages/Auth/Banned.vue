<script setup>
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import AuthCard from '@/Components/Auth/AuthCard.vue';

defineOptions({ layout: AuthLayout });
defineProps({ reason: String, authUrls: Object });
</script>

<template>
    <Head title="Account suspended" />
    <AuthCard title="Account suspended" subtitle="Your account has been banned by the administrator." icon="ri-forbid-2-line">
        <p class="text-xs font-medium tracking-wider text-zinc-500 uppercase">Reason</p>
        <p class="mt-2 text-sm leading-relaxed text-zinc-200">{{ reason || 'No reason was provided.' }}</p>
        <template #footer>
            <a :href="authUrls.home" class="font-semibold text-brand-300 hover:underline">Go to homepage</a>
        </template>
    </AuthCard>
</template>
