<script setup>
/**
 * Responsive data list: a table from md up, stacked cards on phones.
 *
 * columns: [{ key, label, align?: 'right', class?, mobile?: false }]
 * Slots:
 *   #cell-<key>="{ row }"   custom cell (used in both layouts)
 *   #card="{ row }"         fully custom mobile card (optional)
 *   #actions="{ row }"      trailing actions cell / card footer
 */
import EmptyState from './EmptyState.vue';
import Pagination from './Pagination.vue';

defineProps({
    rows: { type: Array, default: () => [] },
    columns: { type: Array, required: true },
    meta: Object,
    rowKey: { type: [String, Function], default: 'id' },
    emptyIcon: String,
    emptyTitle: { type: String, default: 'No records found' },
    emptyText: String,
});

const keyOf = (row, i, rowKey) => (typeof rowKey === 'function' ? rowKey(row) : row[rowKey] ?? i);
</script>

<template>
    <div class="card overflow-hidden">
        <slot name="toolbar" />

        <EmptyState v-if="!rows.length" :icon="emptyIcon" :title="emptyTitle" :text="emptyText">
            <slot name="empty" />
        </EmptyState>

        <template v-else>
            <!-- phones -->
            <ul class="divide-y divide-white/[0.05] md:hidden">
                <li v-for="(row, i) in rows" :key="keyOf(row, i, rowKey)" class="px-4 py-4">
                    <slot name="card" :row="row">
                        <dl class="grid grid-cols-2 gap-x-4 gap-y-3">
                            <div v-for="c in columns.filter((c) => c.mobile !== false)" :key="c.key" :class="c.wide ? 'col-span-2' : ''">
                                <dt class="text-[11px] font-medium tracking-wide text-zinc-500 uppercase">{{ c.label }}</dt>
                                <dd class="mt-1 text-sm text-zinc-200">
                                    <slot :name="`cell-${c.key}`" :row="row">{{ row[c.key] }}</slot>
                                </dd>
                            </div>
                        </dl>
                    </slot>
                    <div v-if="$slots.actions" class="mt-3 flex flex-wrap gap-2 border-t border-white/[0.05] pt-3">
                        <slot name="actions" :row="row" />
                    </div>
                </li>
            </ul>

            <!-- tablet / desktop -->
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/[0.05] text-left text-[11px] tracking-wider text-zinc-500 uppercase">
                            <th v-for="c in columns" :key="c.key" class="px-5 py-3 font-medium whitespace-nowrap" :class="[c.align === 'right' ? 'text-right' : '', c.class]">{{ c.label }}</th>
                            <th v-if="$slots.actions" class="px-5 py-3 text-right font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04]">
                        <tr v-for="(row, i) in rows" :key="keyOf(row, i, rowKey)" class="transition hover:bg-white/[0.02]">
                            <td v-for="c in columns" :key="c.key" class="px-5 py-3.5 align-middle text-zinc-200" :class="[c.align === 'right' ? 'text-right' : '', c.class]">
                                <slot :name="`cell-${c.key}`" :row="row">{{ row[c.key] }}</slot>
                            </td>
                            <td v-if="$slots.actions" class="px-5 py-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex gap-2"><slot name="actions" :row="row" /></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>

        <Pagination v-if="meta" :meta="meta" />
    </div>
</template>
