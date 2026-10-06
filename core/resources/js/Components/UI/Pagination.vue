<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({ meta: { type: Object, required: true } });
</script>

<template>
    <div v-if="meta && meta.last > 1" class="flex flex-col items-center justify-between gap-3 border-t border-white/[0.05] px-4 py-3.5 sm:flex-row sm:px-5">
        <p class="text-xs text-zinc-500">
            Showing <span class="font-semibold text-zinc-300">{{ meta.from }}–{{ meta.to }}</span> of
            <span class="font-semibold text-zinc-300">{{ meta.total.toLocaleString() }}</span>
        </p>
        <nav class="flex items-center gap-1" aria-label="Pagination">
            <component :is="meta.prev ? Link : 'span'" :href="meta.prev" preserve-scroll class="grid h-9 w-9 place-items-center rounded-lg text-lg" :class="meta.prev ? 'text-zinc-300 hover:bg-white/[0.06]' : 'text-zinc-700'" aria-label="Previous page">
                <i class="ri-arrow-left-s-line"></i>
            </component>
            <!-- page numbers: hidden on phones, prev/next + counter instead -->
            <span class="px-2 text-xs text-zinc-400 sm:hidden">{{ meta.current }} / {{ meta.last }}</span>
            <template v-for="(l, i) in meta.links" :key="i">
                <component
                    :is="l.url && !l.active ? Link : 'span'"
                    :href="l.url"
                    preserve-scroll
                    class="hidden h-9 min-w-9 place-items-center rounded-lg px-2 text-sm sm:grid"
                    :class="l.active ? 'bg-brand-500 font-semibold text-ink-950' : l.url ? 'text-zinc-400 hover:bg-white/[0.06] hover:text-white' : 'text-zinc-600'"
                    v-html="l.label"
                />
            </template>
            <component :is="meta.next ? Link : 'span'" :href="meta.next" preserve-scroll class="grid h-9 w-9 place-items-center rounded-lg text-lg" :class="meta.next ? 'text-zinc-300 hover:bg-white/[0.06]' : 'text-zinc-700'" aria-label="Next page">
                <i class="ri-arrow-right-s-line"></i>
            </component>
        </nav>
    </div>
</template>
