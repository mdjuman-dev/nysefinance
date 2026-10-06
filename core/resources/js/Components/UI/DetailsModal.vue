<script setup>
import Modal from './Modal.vue';

// items: [{ name, value }]; feedback: admin note shown for rejected records.
defineProps({ show: Boolean, title: { type: String, default: 'Details' }, items: Array, feedback: String });
defineEmits(['close']);
</script>

<template>
    <Modal :show="show" :title="title" @close="$emit('close')">
        <dl v-if="items && items.length" class="divide-y divide-white/[0.05] rounded-2xl border border-white/[0.06] bg-white/[0.02]">
            <div v-for="(i, idx) in items" :key="idx" class="flex items-start justify-between gap-4 px-4 py-3">
                <dt class="text-sm text-zinc-400">{{ i.name }}</dt>
                <dd class="text-right text-sm font-medium break-all text-zinc-100">{{ i.value }}</dd>
            </div>
        </dl>
        <p v-else class="py-6 text-center text-sm text-zinc-500">No additional details.</p>
        <div v-if="feedback" class="mt-4 rounded-2xl border border-down/20 bg-down/[0.06] p-4">
            <p class="text-xs font-semibold tracking-wide text-down uppercase">Admin feedback</p>
            <p class="mt-1 text-sm text-zinc-300">{{ feedback }}</p>
        </div>
    </Modal>
</template>
