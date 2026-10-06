<script setup>
defineProps({ title: String, subtitle: String, icon: String });
</script>

<template>
    <div>
        <div class="mb-7">
            <span v-if="icon" class="mb-5 grid h-14 w-14 place-items-center rounded-2xl border border-brand-500/20 bg-brand-500/10 text-2xl text-brand-300"><i :class="icon"></i></span>
            <h1 class="text-2xl font-bold text-white sm:text-3xl">{{ title }}</h1>
            <p v-if="subtitle" class="mt-2 text-sm text-zinc-400">{{ subtitle }}</p>
            <slot name="sub" />
        </div>
        <div class="card p-5 sm:p-7">
            <slot />
        </div>
        <div v-if="$slots.footer" class="mt-6 text-center text-sm text-zinc-400"><slot name="footer" /></div>
    </div>
</template>
