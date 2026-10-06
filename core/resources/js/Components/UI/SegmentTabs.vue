<script setup>
import { Link } from '@inertiajs/vue3';

// tabs: [{ label, href?, value?, active, count? }] — links when href is set, buttons otherwise.
defineProps({ tabs: { type: Array, required: true } });
defineEmits(['select']);
</script>

<template>
    <div class="-mx-4 overflow-x-auto px-4 [scrollbar-width:none] sm:mx-0 sm:px-0">
        <div class="inline-flex min-w-max gap-1 rounded-xl border border-white/[0.06] bg-ink-900/70 p-1">
            <template v-for="t in tabs" :key="t.label">
                <component
                    :is="t.href ? (t.external ? 'a' : Link) : 'button'"
                    :href="t.href"
                    :type="t.href ? undefined : 'button'"
                    class="flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-sm font-medium whitespace-nowrap transition"
                    :class="t.active ? 'bg-ink-700 text-white shadow-sm' : 'text-zinc-400 hover:text-zinc-100'"
                    @click="!t.href && $emit('select', t.value)"
                >
                    <i v-if="t.icon" :class="t.icon"></i>
                    {{ t.label }}
                    <span v-if="t.count != null" class="rounded-md bg-white/[0.06] px-1.5 text-[11px] text-zinc-400">{{ t.count }}</span>
                </component>
            </template>
        </div>
    </div>
</template>
