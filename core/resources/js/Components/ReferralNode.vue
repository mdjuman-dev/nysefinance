<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { formatMoney } from '@/utils/format';

const props = defineProps({ node: Object, level: { type: Number, default: 1 }, filter: String });
const open = ref(props.level < 2);

const matches = (n, q) => !q || n.name.toLowerCase().includes(q) || n.username.toLowerCase().includes(q) || (n.children || []).some((c) => matches(c, q));
const q = computed(() => (props.filter || '').trim().toLowerCase());
const visible = computed(() => matches(props.node, q.value));
const kids = computed(() => (props.node.children || []).filter((c) => matches(c, q.value)));
const expanded = computed(() => open.value || (q.value && kids.value.length));
const levelColors = ['bg-brand-500', 'bg-sky-400', 'bg-violet-400', 'bg-amber-400', 'bg-pink-400', 'bg-teal-400'];
</script>

<template>
    <li v-if="visible" class="relative">
        <div class="group flex items-center gap-2.5 rounded-xl py-2 pr-2 pl-1 transition hover:bg-white/[0.03]">
            <button
                class="grid h-7 w-7 shrink-0 place-items-center rounded-lg text-zinc-500 transition hover:bg-white/[0.06] hover:text-white"
                :class="{ invisible: !node.children?.length }"
                :aria-label="expanded ? 'Collapse' : 'Expand'"
                @click="open = !open"
            >
                <i :class="expanded ? 'ri-arrow-down-s-line' : 'ri-arrow-right-s-line'" class="text-lg"></i>
            </button>
            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg text-[11px] font-bold text-ink-950" :class="levelColors[(level - 1) % levelColors.length]">
                {{ node.name.split(' ').map((w) => w[0]).slice(0, 2).join('').toUpperCase() }}
            </span>
            <div class="min-w-0 flex-1">
                <p class="flex items-center gap-1.5 truncate text-sm font-medium text-zinc-100">
                    {{ node.name }}
                    <i v-if="node.member" class="ri-verified-badge-fill text-brand-400" title="Stock member"></i>
                </p>
                <p class="truncate text-xs text-zinc-500">
                    <component :is="node.url ? Link : 'span'" :href="node.url" class="hover:text-brand-300">@{{ node.username }}</component>
                    · {{ node.count }} referrals
                </p>
            </div>
            <span class="shrink-0 rounded-lg bg-white/[0.04] px-2 py-1 font-mono text-xs text-zinc-300">${{ formatMoney(node.invest) }}</span>
        </div>
        <ul v-if="expanded && kids.length" class="ml-4 space-y-0.5 border-l border-white/[0.06] pl-2 sm:ml-5 sm:pl-3">
            <ReferralNode v-for="c in kids" :key="c.id" :node="c" :level="level + 1" :filter="filter" />
        </ul>
    </li>
</template>
