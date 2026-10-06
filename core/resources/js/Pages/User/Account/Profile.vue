<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import Badge from '@/Components/UI/Badge.vue';
import { formatDate } from '@/utils/display';

const props = defineProps({ profile: Object, pin: String, urls: Object });

const form = useForm({
    firstname: props.profile.firstname || '',
    lastname: props.profile.lastname || '',
    address: props.profile.address || '',
    city: props.profile.city || '',
    state: props.profile.state || '',
    zip: props.profile.zip || '',
    security_pin: '',
    image: null,
});

const preview = ref(props.profile.image);
const fileInput = ref(null);
function pickImage(e) {
    const file = e.target.files?.[0];
    if (!file) return;
    form.image = file;
    preview.value = URL.createObjectURL(file);
}

function submit() {
    form.transform((d) => {
        const data = { ...d };
        if (!data.image) delete data.image;
        if (props.pin || !data.security_pin) delete data.security_pin;
        return data;
    }).post(props.urls.save, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => form.reset('security_pin', 'image'),
    });
}

const resetting = ref(false);
function resetPin() {
    if (!confirm('Request a new security PIN? You can do this once per day.')) return;
    resetting.value = true;
    router.get(props.urls.pinReset, {}, { preserveScroll: true, onFinish: () => (resetting.value = false) });
}

const kyc = computed(() => ({ 0: ['Unverified', 'warning'], 1: ['Verified', 'success'], 2: ['Pending review', 'info'] })[props.profile.kyc] || ['Unverified', 'warning']);

const security = computed(() => [
    { icon: 'ri-mail-check-line', label: 'Email', ok: props.profile.ev },
    { icon: 'ri-smartphone-line', label: 'Mobile', ok: props.profile.sv },
    { icon: 'ri-shield-keyhole-line', label: '2FA', ok: props.profile.twofa, href: props.urls.twofa },
    { icon: 'ri-passport-line', label: 'KYC', ok: props.profile.kyc === 1, href: props.profile.kyc === 0 ? props.urls.kyc : props.urls.kycData },
]);

const info = computed(() => [
    { icon: 'ri-at-line', label: 'Username', value: props.profile.username },
    { icon: 'ri-mail-line', label: 'Email', value: props.profile.email },
    { icon: 'ri-phone-line', label: 'Mobile', value: props.profile.mobile },
    { icon: 'ri-earth-line', label: 'Country', value: props.profile.country },
]);
</script>

<template>
    <Head title="Profile" />
    <PageHeader title="Profile" subtitle="Your personal details and account security" icon="ri-user-settings-line">
        <template #actions>
            <Link :href="urls.password" class="btn-ghost"><i class="ri-lock-password-line"></i> Change password</Link>
        </template>
    </PageHeader>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-[340px_1fr]">
        <!-- identity -->
        <aside class="space-y-5">
            <div class="card overflow-hidden">
                <div class="h-20 bg-gradient-to-r from-brand-500/25 via-brand-400/10 to-sky-500/20"></div>
                <div class="-mt-12 px-5 pb-5 text-center">
                    <button type="button" class="group relative mx-auto block h-24 w-24 rounded-full ring-4 ring-ink-900" aria-label="Change photo" @click="fileInput.click()">
                        <img v-if="preview" :src="preview" alt="" class="h-full w-full rounded-full object-cover" />
                        <span v-else class="grid h-full w-full place-items-center rounded-full bg-gradient-to-br from-brand-400 to-brand-600 text-3xl font-bold text-ink-950">{{ (profile.firstname || profile.username || '?').charAt(0).toUpperCase() }}</span>
                        <span class="absolute inset-0 grid place-items-center rounded-full bg-black/55 text-xl text-white opacity-0 transition group-hover:opacity-100"><i class="ri-camera-line"></i></span>
                        <span class="absolute right-0 bottom-0 grid h-8 w-8 place-items-center rounded-full bg-brand-500 text-ink-950 ring-4 ring-ink-900"><i class="ri-pencil-line"></i></span>
                    </button>
                    <input ref="fileInput" type="file" accept=".jpg,.jpeg,.png" class="hidden" @change="pickImage" />
                    <p v-if="form.errors.image" class="mt-2 text-xs text-down">{{ form.errors.image }}</p>
                    <h2 class="mt-3 text-lg font-bold text-white">{{ profile.firstname }} {{ profile.lastname }}</h2>
                    <p class="text-sm text-zinc-500">@{{ profile.username }}</p>
                    <div class="mt-3 flex justify-center"><Badge :tone="kyc[1]">KYC · {{ kyc[0] }}</Badge></div>
                </div>
                <ul class="divide-y divide-white/[0.05] border-t border-white/[0.06]">
                    <li v-for="i in info" :key="i.label" class="flex items-center gap-3 px-5 py-3">
                        <i :class="i.icon" class="text-lg text-zinc-500"></i>
                        <div class="min-w-0">
                            <p class="text-[11px] tracking-wide text-zinc-500 uppercase">{{ i.label }}</p>
                            <p class="truncate text-sm text-zinc-200">{{ i.value || '—' }}</p>
                        </div>
                    </li>
                </ul>
                <p v-if="profile.joined" class="border-t border-white/[0.06] px-5 py-3 text-xs text-zinc-500">Member since {{ formatDate(profile.joined) }}</p>
            </div>

            <div class="card p-5">
                <h3 class="mb-3 text-sm font-semibold text-white">Security status</h3>
                <div class="grid grid-cols-2 gap-2">
                    <component
                        :is="s.href ? Link : 'div'"
                        v-for="s in security"
                        :key="s.label"
                        :href="s.href"
                        class="flex items-center gap-2 rounded-xl border px-3 py-2.5 text-sm transition"
                        :class="s.ok ? 'border-up/20 bg-up/[0.06] text-up' : 'border-amber-400/20 bg-amber-400/[0.06] text-amber-300 hover:border-amber-400/40'"
                    >
                        <i :class="s.icon"></i>
                        <span class="flex-1">{{ s.label }}</span>
                        <i :class="s.ok ? 'ri-check-line' : 'ri-arrow-right-s-line'"></i>
                    </component>
                </div>
            </div>
        </aside>

        <!-- edit -->
        <form class="space-y-5" @submit.prevent="submit">
            <section class="card p-5 sm:p-7">
                <h3 class="font-semibold text-white">Personal information</h3>
                <p class="mb-5 text-sm text-zinc-500">Keep your name and address up to date.</p>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-medium text-zinc-300">First name</span>
                        <input v-model="form.firstname" class="field" required autocomplete="given-name" />
                        <span v-if="form.errors.firstname" class="mt-1 block text-xs text-down">{{ form.errors.firstname }}</span>
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-medium text-zinc-300">Last name</span>
                        <input v-model="form.lastname" class="field" required autocomplete="family-name" />
                        <span v-if="form.errors.lastname" class="mt-1 block text-xs text-down">{{ form.errors.lastname }}</span>
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="mb-1.5 block text-sm font-medium text-zinc-300">Address</span>
                        <input v-model="form.address" class="field" autocomplete="street-address" />
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-medium text-zinc-300">City</span>
                        <input v-model="form.city" class="field" autocomplete="address-level2" />
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="block">
                            <span class="mb-1.5 block text-sm font-medium text-zinc-300">State</span>
                            <input v-model="form.state" class="field" autocomplete="address-level1" />
                        </label>
                        <label class="block">
                            <span class="mb-1.5 block text-sm font-medium text-zinc-300">Zip</span>
                            <input v-model="form.zip" class="field" autocomplete="postal-code" />
                        </label>
                    </div>
                </div>
            </section>

            <section class="card p-5 sm:p-7">
                <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-white">Security PIN</h3>
                        <p class="text-sm text-zinc-500">Used to confirm sensitive actions. Once set it stays hidden; you can request a reset once per day.</p>
                    </div>
                    <Badge :tone="pin ? 'success' : 'warning'">{{ pin ? 'Set' : 'Not set' }}</Badge>
                </div>
                <div v-if="pin" class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3">
                    <span class="font-mono text-lg tracking-[0.3em] text-white">{{ pin }}</span>
                    <button type="button" class="btn-ghost py-2 text-xs" :disabled="resetting" @click="resetPin">
                        <i class="ri-refresh-line" :class="resetting && 'animate-spin'"></i> Request reset
                    </button>
                </div>
                <label v-else class="block">
                    <span class="mb-1.5 block text-sm font-medium text-zinc-300">Choose a PIN</span>
                    <input v-model="form.security_pin" inputmode="numeric" class="field font-mono tracking-widest" placeholder="Enter your security PIN" required />
                    <span v-if="form.errors.security_pin" class="mt-1 block text-xs text-down">{{ form.errors.security_pin }}</span>
                    <span class="mt-1.5 flex items-center gap-1 text-xs text-amber-300"><i class="ri-information-line"></i>Write it down — you won't be able to view it again.</span>
                </label>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary w-full sm:w-auto sm:px-8" :disabled="form.processing">
                    <i :class="form.processing ? 'ri-loader-4-line animate-spin' : 'ri-save-3-line'"></i>
                    {{ form.processing ? 'Saving…' : 'Save changes' }}
                </button>
            </div>
        </form>
    </div>
</template>
