<script setup>
import { ref } from 'vue';

// v-model: File[]; accepts the extensions the ticket validator allows.
const props = defineProps({
    modelValue: { type: Array, default: () => [] },
    max: { type: Number, default: 5 },
    accept: { type: String, default: '.jpg,.jpeg,.png,.pdf,.doc,.docx' },
    compact: Boolean,
});
const emit = defineEmits(['update:modelValue']);
const input = ref(null);
const dragging = ref(false);

function add(list) {
    const files = [...props.modelValue, ...Array.from(list)].slice(0, props.max);
    emit('update:modelValue', files);
}
function remove(i) {
    emit('update:modelValue', props.modelValue.filter((_, idx) => idx !== i));
}
const size = (b) => (b > 1048576 ? `${(b / 1048576).toFixed(1)} MB` : `${Math.ceil(b / 1024)} KB`);
</script>

<template>
    <div>
        <button
            v-if="!compact"
            type="button"
            class="flex w-full flex-col items-center gap-1 rounded-2xl border border-dashed px-4 py-6 text-center transition"
            :class="dragging ? 'border-brand-400 bg-brand-500/5' : 'border-white/10 hover:border-white/20'"
            :disabled="modelValue.length >= max"
            @click="input.click()"
            @dragover.prevent="dragging = true"
            @dragleave="dragging = false"
            @drop.prevent="dragging = false; add($event.dataTransfer.files)"
        >
            <i class="ri-upload-cloud-2-line text-2xl text-zinc-500"></i>
            <span class="text-sm text-zinc-300">Drop files or <span class="text-brand-300">browse</span></span>
            <span class="text-[11px] text-zinc-500">JPG, PNG, PDF, DOC · up to {{ max }} files</span>
        </button>
        <button v-else type="button" class="grid h-10 w-10 place-items-center rounded-xl text-xl text-zinc-400 hover:bg-white/[0.06] hover:text-white" aria-label="Attach files" :disabled="modelValue.length >= max" @click="input.click()">
            <i class="ri-attachment-2"></i>
        </button>
        <input ref="input" type="file" multiple :accept="accept" class="hidden" @change="add($event.target.files); $event.target.value = ''" />
        <ul v-if="modelValue.length && !compact" class="mt-3 space-y-2">
            <li v-for="(f, i) in modelValue" :key="i" class="flex items-center gap-3 rounded-xl bg-white/[0.03] px-3 py-2">
                <i class="ri-file-3-line text-zinc-500"></i>
                <span class="min-w-0 flex-1 truncate text-sm text-zinc-200">{{ f.name }}</span>
                <span class="text-[11px] text-zinc-500">{{ size(f.size) }}</span>
                <button type="button" class="text-zinc-500 hover:text-down" aria-label="Remove file" @click="remove(i)"><i class="ri-close-line"></i></button>
            </li>
        </ul>
    </div>
</template>
