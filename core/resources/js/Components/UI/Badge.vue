<script setup>
import { computed } from 'vue';

const props = defineProps({ tone: { type: String, default: 'neutral' }, dot: Boolean });

const tones = {
    success: 'bg-up/10 text-up ring-up/20',
    danger: 'bg-down/10 text-down ring-down/20',
    warning: 'bg-amber-400/10 text-amber-300 ring-amber-400/20',
    info: 'bg-sky-400/10 text-sky-300 ring-sky-400/20',
    brand: 'bg-brand-500/10 text-brand-300 ring-brand-500/20',
    violet: 'bg-violet-400/10 text-violet-300 ring-violet-400/20',
    neutral: 'bg-white/[0.05] text-zinc-400 ring-white/10',
};
const cls = computed(() => tones[props.tone] || tones.neutral);
</script>

<template>
    <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-0.5 text-[11px] font-semibold whitespace-nowrap ring-1 ring-inset" :class="cls">
        <span v-if="dot" class="h-1.5 w-1.5 rounded-full bg-current"></span>
        <slot />
    </span>
</template>
