<script setup>
defineProps({ policies: { type: Array, default: () => [] } });
</script>

<template>
    <template v-for="(p, i) in policies" :key="p.url">
        <a :href="p.url" target="_blank" class="text-brand-300 hover:underline">{{ p.title }}</a><span v-if="i < policies.length - 1">, </span>
    </template>
</template>
