<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    src: String,
    name: String,
    size: { type: String, default: 'h-8 w-8 text-xs' },
});

const failed = ref(false);
const initials = computed(() =>
    (props.name || '?')
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2)
        .map((w) => w[0].toUpperCase())
        .join(''),
);
</script>

<template>
    <img v-if="src && !failed" :src="src" alt="" :class="size" class="shrink-0 rounded-xl object-cover ring-1 ring-white/10" @error="failed = true" />
    <span v-else :class="size" class="grid shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-400 to-sky-500 font-bold text-ink-950 ring-1 ring-white/10">
        {{ initials }}
    </span>
</template>
