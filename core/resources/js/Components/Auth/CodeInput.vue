<script setup>
// 6-box one-time code input that submits as a single `code` field.
import { computed, onMounted, ref } from 'vue';

const props = defineProps({ length: { type: Number, default: 6 }, name: { type: String, default: 'code' } });
const digits = ref(Array(props.length).fill(''));
const inputs = ref([]);
const value = computed(() => digits.value.join(''));
const complete = computed(() => value.value.length === props.length);

const focus = (i) => inputs.value[Math.max(0, Math.min(props.length - 1, i))]?.focus();

function fill(text, from = 0) {
    const chars = text.replace(/\D/g, '').slice(0, props.length - from).split('');
    chars.forEach((c, k) => (digits.value[from + k] = c));
    focus(from + chars.length);
}
function onInput(i, e) {
    const chars = e.target.value.replace(/\D/g, '');
    if (chars.length > 1) return fill(chars, i);
    digits.value[i] = chars;
    e.target.value = chars;
    if (chars) focus(i + 1);
}
function onKey(i, e) {
    if (e.key === 'Backspace' && !digits.value[i]) focus(i - 1);
    if (e.key === 'ArrowLeft') focus(i - 1);
    if (e.key === 'ArrowRight') focus(i + 1);
}
function onPaste(e) {
    e.preventDefault();
    fill(e.clipboardData.getData('text'));
}

onMounted(() => focus(0));
defineExpose({ complete });
</script>

<template>
    <div>
        <input type="hidden" :name="name" :value="value" />
        <div class="flex justify-between gap-2 sm:gap-3">
            <input
                v-for="(d, i) in digits"
                :key="i"
                :ref="(el) => (inputs[i] = el)"
                :value="d"
                inputmode="numeric"
                autocomplete="one-time-code"
                class="h-14 w-full min-w-0 rounded-xl border border-white/10 bg-ink-850 text-center font-mono text-xl font-bold text-white transition focus:border-brand-500/60 focus:ring-4 focus:ring-brand-500/10 focus:outline-none sm:h-16 sm:text-2xl"
                :aria-label="`Digit ${i + 1}`"
                @input="onInput(i, $event)"
                @keydown="onKey(i, $event)"
                @paste="onPaste"
            />
        </div>
    </div>
</template>
