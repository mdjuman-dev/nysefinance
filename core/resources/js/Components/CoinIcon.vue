<script setup>
import { ref } from 'vue';

defineProps({
    src: String,
    symbol: String,
    size: { type: String, default: 'h-9 w-9' },
});

const failed = ref(false);
</script>

<template>
    <img
        v-if="src && !failed"
        :src="src"
        :alt="symbol"
        loading="lazy"
        :class="size"
        class="shrink-0 rounded-full bg-ink-700 object-cover ring-1 ring-white/10"
        @error="failed = true"
    />
    <span
        v-else
        :class="size"
        class="grid shrink-0 place-items-center rounded-full bg-gradient-to-br from-brand-500/30 to-sky-500/20 text-xs font-bold text-brand-200 ring-1 ring-white/10"
    >
        {{ (symbol || '?').slice(0, 3) }}
    </span>
</template>
