<script setup>
defineProps({ eyebrow: String, title: String, subtitle: String });
</script>

<template>
    <section class="container-x pt-32 pb-12 lg:pt-40">
        <span v-if="eyebrow" class="eyebrow">{{ eyebrow }}</span>
        <h1 class="mt-5 max-w-3xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl">{{ title }}</h1>
        <p v-if="subtitle" class="mt-5 max-w-2xl text-lg leading-8 text-zinc-400">{{ subtitle }}</p>
        <slot />
    </section>
</template>
