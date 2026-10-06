<script setup>
defineProps({
    icon: { type: String, default: 'ri-inbox-line' },
    title: { type: String, default: 'Nothing here yet' },
    text: String,
});
</script>

<template>
    <div class="flex flex-col items-center px-6 py-14 text-center">
        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-white/[0.04] text-2xl text-zinc-500 ring-1 ring-white/[0.06]">
            <i :class="icon"></i>
        </span>
        <p class="mt-4 text-sm font-semibold text-zinc-200">{{ title }}</p>
        <p v-if="text" class="mt-1 max-w-sm text-sm text-zinc-500">{{ text }}</p>
        <div v-if="$slots.default" class="mt-5"><slot /></div>
    </div>
</template>
