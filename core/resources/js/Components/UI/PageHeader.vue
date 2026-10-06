<script setup>
defineProps({ title: String, subtitle: String, icon: String });
</script>

<template>
    <div class="mb-5 flex flex-wrap items-end justify-between gap-4 lg:mb-6">
        <div class="flex min-w-0 items-center gap-3">
            <span v-if="icon" class="hidden h-11 w-11 shrink-0 place-items-center rounded-2xl bg-brand-500/10 text-xl text-brand-400 ring-1 ring-brand-500/20 sm:grid">
                <i :class="icon"></i>
            </span>
            <div class="min-w-0">
                <h1 class="truncate text-xl font-bold tracking-tight text-white sm:text-2xl">{{ title }}</h1>
                <p v-if="subtitle" class="mt-0.5 text-sm text-zinc-500">{{ subtitle }}</p>
            </div>
        </div>
        <div v-if="$slots.actions" class="flex flex-wrap items-center gap-2">
            <slot name="actions" />
        </div>
    </div>
</template>
