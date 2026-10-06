<script setup>
/**
 * Toolbar row for DataList: search + selects.
 * selects: [{ key, placeholder, options: [{ value, label }] }]
 */
defineProps({
    filters: { type: Object, required: true },
    search: { type: String, default: null }, // placeholder; null hides search
    selects: { type: Array, default: () => [] },
});
</script>

<template>
    <div class="flex flex-col gap-2 border-b border-white/[0.05] p-3 sm:flex-row sm:items-center sm:p-4">
        <label v-if="search !== null" class="relative block flex-1">
            <i class="ri-search-line pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-sm text-zinc-500"></i>
            <input v-model="filters.search" type="search" :placeholder="search" class="w-full rounded-lg border border-white/[0.07] bg-white/[0.03] py-2.5 pr-3 pl-9 text-sm text-zinc-200 placeholder:text-zinc-500 focus:border-brand-500/50 focus:outline-none" />
        </label>
        <div v-if="selects.length" class="grid grid-cols-2 gap-2 sm:flex">
            <div v-for="s in selects" :key="s.key" class="relative">
                <select v-model="filters[s.key]" class="w-full appearance-none rounded-lg border border-white/[0.07] bg-ink-850 py-2.5 pr-8 pl-3 text-sm text-zinc-200 focus:border-brand-500/50 focus:outline-none sm:w-40">
                    <option value="">{{ s.placeholder }}</option>
                    <option v-for="o in s.options" :key="o.value" :value="String(o.value)">{{ o.label }}</option>
                </select>
                <i class="ri-arrow-down-s-line pointer-events-none absolute top-1/2 right-2.5 -translate-y-1/2 text-zinc-500"></i>
            </div>
        </div>
        <slot />
    </div>
</template>
