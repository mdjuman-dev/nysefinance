<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import AuthCard from '@/Components/Auth/AuthCard.vue';
import AuthForm from '@/Components/Auth/AuthForm.vue';
import Field from '@/Components/Auth/Field.vue';
import { postJson } from '@/utils/http';

defineOptions({ layout: AuthLayout });
const props = defineProps({ needsEmail: Boolean, countries: Array, detected: String, action: String, logout: String, old: Object, authUrls: Object });

const firstDetected = (props.detected || '').split(',')[0];
const form = ref({
    email: props.old?.email || '',
    username: props.old?.username || '',
    country_code: props.old?.country_code || props.countries.find((c) => c.code === firstDetected)?.code || '',
    mobile: props.old?.mobile || '',
    address: props.old?.address || '',
    state: props.old?.state || '',
    zip: props.old?.zip || '',
    city: props.old?.city || '',
});
const country = computed(() => props.countries.find((c) => c.code === form.value.country_code));

const taken = ref({});
async function check(field) {
    taken.value[field] = '';
    const value = form.value[field];
    if (!value) return;
    if (field === 'username' && /[^a-z0-9_]/.test(value)) {
        taken.value.username = 'Only small letters, numbers and underscore.';
        return;
    }
    const payload = field === 'mobile' ? { mobile: value, mobile_code: country.value?.dial } : { [field]: value };
    const res = await postJson(props.authUrls.checkUser, payload).catch(() => null);
    if (res?.data) taken.value[field] = `This ${res.field?.toLowerCase() || field} is already taken.`;
}
const blocked = computed(() => Object.values(taken.value).some(Boolean));
</script>

<template>
    <Head title="Complete your profile" />
    <AuthCard title="Complete your profile" subtitle="Just a few details and your account is ready." icon="ri-user-settings-line">
        <AuthForm :action="action" :confirm="() => !blocked" v-slot="{ busy }">
            <Field v-if="needsEmail" v-model="form.email" name="email" type="email" label="Email" icon="ri-mail-line" required @blur="check('email')" :error="taken.email" />
            <Field
                v-model="form.username"
                name="username"
                label="Username"
                icon="ri-at-line"
                autocomplete="username"
                hint="At least 6 characters: small letters, numbers, underscore."
                minlength="6"
                required
                :error="taken.username"
                @blur="check('username')"
            />

            <label class="block">
                <span class="mb-1.5 block text-sm font-medium text-zinc-300">Country</span>
                <select v-model="form.country_code" name="country_code" class="field" required>
                    <option value="" disabled>Select country</option>
                    <option v-for="c in countries" :key="c.code" :value="c.code">{{ c.name }}</option>
                </select>
            </label>
            <input type="hidden" name="country" :value="country?.name || ''" />
            <input type="hidden" name="mobile_code" :value="country?.dial || ''" />

            <label class="block">
                <span class="mb-1.5 block text-sm font-medium text-zinc-300">Mobile</span>
                <span class="flex">
                    <span class="grid min-w-16 place-items-center rounded-l-xl border border-r-0 border-white/10 bg-white/[0.04] px-3 font-mono text-sm text-zinc-300">+{{ country?.dial || '—' }}</span>
                    <input v-model="form.mobile" name="mobile" inputmode="numeric" class="field rounded-l-none" :class="taken.mobile && 'border-down/60'" required @blur="check('mobile')" @input="form.mobile = form.mobile.replace(/\D/g, '')" />
                </span>
                <span v-if="taken.mobile || $page.props.errors?.mobile" class="mt-1.5 block text-xs text-down">{{ taken.mobile || $page.props.errors.mobile }}</span>
            </label>

            <Field v-model="form.address" name="address" label="Address" icon="ri-map-pin-line" autocomplete="street-address" />
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <Field v-model="form.city" name="city" label="City" autocomplete="address-level2" />
                <Field v-model="form.state" name="state" label="State" autocomplete="address-level1" />
                <Field v-model="form.zip" name="zip" label="Zip" autocomplete="postal-code" />
            </div>

            <button type="submit" class="btn-primary w-full py-3.5" :disabled="busy || blocked">
                <i v-if="busy" class="ri-loader-4-line animate-spin"></i>
                {{ busy ? 'Saving…' : 'Finish setup' }}
            </button>
        </AuthForm>
        <template #footer>
            <a :href="logout" class="inline-flex items-center gap-1.5 text-zinc-400 hover:text-white"><i class="ri-logout-box-r-line"></i>Log out</a>
        </template>
    </AuthCard>
</template>
