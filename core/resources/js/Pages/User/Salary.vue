<script setup>
import { computed, reactive } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import { getJson } from '@/utils/http';
import { formatMoney } from '@/utils/format';

const props = defineProps({ members: Array, urls: Object });

const MIN_TOTAL = 6000;
const teams = [
    { key: 'team_one', type: 'one', label: 'Team A', pct: 50, color: 'from-brand-500 to-brand-300' },
    { key: 'team_two', type: 'two', label: 'Team B', pct: 30, color: 'from-sky-500 to-sky-300' },
    { key: 'team_three', type: 'three', label: 'Team C', pct: 15, color: 'from-violet-500 to-violet-300' },
    { key: 'team_four', type: 'four', label: 'Team D', pct: 5, color: 'from-amber-500 to-amber-300' },
];

const form = useForm({ team_one: '', team_two: '', team_three: '', team_four: '' });
const invest = reactive({ team_one: null, team_two: null, team_three: null, team_four: null });
const loading = reactive({});

async function pick(team) {
    const id = form[team.key];
    invest[team.key] = null;
    if (!id) return;
    loading[team.key] = true;
    try {
        const res = await getJson(props.urls.invest, { user_id: id, type: team.type });
        invest[team.key] = Number(res.data) || 0;
    } catch {
        invest[team.key] = 0;
    } finally {
        loading[team.key] = false;
    }
}

const total = computed(() => teams.reduce((s, t) => s + (invest[t.key] || 0), 0));
const progress = computed(() => Math.min((total.value / MIN_TOTAL) * 100, 100));
const picked = computed(() => teams.map((t) => form[t.key]).filter(Boolean));
const duplicate = computed(() => new Set(picked.value).size !== picked.value.length);
const ready = computed(() => picked.value.length === 4 && !duplicate.value && total.value >= MIN_TOTAL);
const takenBy = (teamKey, id) => teams.some((t) => t.key !== teamKey && String(form[t.key]) === String(id));

const submit = () => form.post(props.urls.submit, { preserveScroll: true });
</script>

<template>
    <Head title="Salary" />
    <PageHeader title="Salary request" subtitle="Pick four teams from your direct referrals to qualify" icon="ri-money-dollar-circle-line" />

    <EmptyState v-if="!members.length" icon="ri-team-line" title="No direct referrals" text="You need direct referrals to build salary teams." class="card" />

    <form v-else class="grid grid-cols-1 gap-5 lg:grid-cols-3" @submit.prevent="submit">
        <div class="space-y-3 lg:col-span-2">
            <div v-for="t in teams" :key="t.key" class="card p-4 sm:p-5">
                <div class="flex items-start gap-4">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-gradient-to-br font-mono text-sm font-bold text-ink-950" :class="t.color">{{ t.pct }}%</span>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-white">{{ t.label }}</p>
                        <p class="text-xs text-zinc-500">{{ t.pct }}% of this team's investment counts toward your salary</p>
                        <div class="relative mt-3">
                            <select v-model="form[t.key]" class="field appearance-none pr-9" @change="pick(t)">
                                <option value="">Choose a team leader</option>
                                <option v-for="m in members" :key="m.id" :value="m.id" :disabled="takenBy(t.key, m.id)">
                                    {{ m.username }}{{ m.name?.trim() ? ` — ${m.name}` : '' }}{{ takenBy(t.key, m.id) ? ' (already used)' : '' }}
                                </option>
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-zinc-500"></i>
                        </div>
                    </div>
                    <div class="w-24 shrink-0 text-right">
                        <p class="text-[11px] text-zinc-500">Counts</p>
                        <p class="mt-1 font-mono text-sm font-semibold text-white">
                            <i v-if="loading[t.key]" class="ri-loader-4-line animate-spin text-zinc-500"></i>
                            <template v-else-if="invest[t.key] != null">${{ formatMoney(invest[t.key]) }}</template>
                            <span v-else class="text-zinc-600">—</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <aside class="lg:sticky lg:top-24 lg:self-start">
            <div class="relative overflow-hidden rounded-3xl border border-white/[0.07] bg-gradient-to-br from-ink-800 to-ink-900 p-5 sm:p-6">
                <p class="text-sm text-zinc-400">Qualifying investment</p>
                <p class="mt-1 font-mono text-3xl font-bold text-white">${{ formatMoney(total) }}</p>
                <div class="mt-4 h-2 overflow-hidden rounded-full bg-white/[0.06]">
                    <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-brand-300 transition-all duration-500" :style="{ width: progress + '%' }"></div>
                </div>
                <p class="mt-2 text-xs text-zinc-500">
                    <template v-if="total >= MIN_TOTAL"><span class="text-up">Minimum reached</span> · ${{ formatMoney(MIN_TOTAL) }} required</template>
                    <template v-else>${{ formatMoney(MIN_TOTAL - total) }} more needed to reach ${{ formatMoney(MIN_TOTAL) }}</template>
                </p>
                <ul class="mt-5 space-y-2 text-sm">
                    <li class="flex items-center gap-2" :class="picked.length === 4 ? 'text-up' : 'text-zinc-500'"><i :class="picked.length === 4 ? 'ri-checkbox-circle-fill' : 'ri-checkbox-blank-circle-line'"></i> Four teams selected</li>
                    <li class="flex items-center gap-2" :class="picked.length && !duplicate ? 'text-up' : 'text-zinc-500'"><i :class="picked.length && !duplicate ? 'ri-checkbox-circle-fill' : 'ri-checkbox-blank-circle-line'"></i> All teams different</li>
                    <li class="flex items-center gap-2" :class="total >= MIN_TOTAL ? 'text-up' : 'text-zinc-500'"><i :class="total >= MIN_TOTAL ? 'ri-checkbox-circle-fill' : 'ri-checkbox-blank-circle-line'"></i> At least ${{ formatMoney(MIN_TOTAL) }}</li>
                </ul>
                <button type="submit" class="btn-primary mt-6 w-full py-3" :disabled="!ready || form.processing">
                    <i :class="form.processing ? 'ri-loader-4-line animate-spin' : 'ri-send-plane-2-line'"></i> Submit salary request
                </button>
            </div>
        </aside>
    </form>
</template>
