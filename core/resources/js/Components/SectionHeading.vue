<script setup>
defineProps({
    eyebrow: String,
    title: String,
    subtitle: String,
    align: { type: String, default: 'center' },
});
</script>

<template>
    <div :class="align === 'center' ? 'mx-auto max-w-2xl text-center' : 'max-w-xl'">
        <span v-if="eyebrow" class="eyebrow">{{ eyebrow }}</span>
        <h2 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">
            <slot name="title">{{ title }}</slot>
        </h2>
        <p v-if="subtitle" class="mt-4 text-base leading-7 text-zinc-400">{{ subtitle }}</p>
    </div>
</template>
