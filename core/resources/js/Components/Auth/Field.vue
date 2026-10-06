<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

defineOptions({ inheritAttrs: false });

const props = defineProps({
    name: String,
    label: String,
    type: { type: String, default: 'text' },
    icon: String,
    error: String,
    hint: String,
});
const model = defineModel({ default: '' });
const page = usePage();
const shown = ref(false);
const isPassword = computed(() => props.type === 'password');
const message = computed(() => props.error || page.props.errors?.[props.name]);
</script>

<template>
    <label class="block">
        <span v-if="label" class="mb-1.5 flex items-center justify-between text-sm font-medium text-zinc-300">
            {{ label }}<slot name="aside" />
        </span>
        <span class="relative block">
            <i v-if="icon" :class="icon" class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2 text-lg text-zinc-500"></i>
            <input
                v-model="model"
                v-bind="$attrs"
                :name="name"
                :type="isPassword && shown ? 'text' : type"
                class="field"
                :class="[icon && 'pl-11', isPassword && 'pr-12', message && 'border-down/60']"
            />
            <button
                v-if="isPassword"
                type="button"
                class="absolute top-1/2 right-3 grid h-8 w-8 -translate-y-1/2 place-items-center rounded-lg text-lg text-zinc-500 hover:text-zinc-200"
                :aria-label="shown ? 'Hide password' : 'Show password'"
                @click="shown = !shown"
            >
                <i :class="shown ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
            </button>
        </span>
        <span v-if="message" class="mt-1.5 flex items-center gap-1 text-xs text-down"><i class="ri-error-warning-line"></i>{{ message }}</span>
        <span v-else-if="hint" class="mt-1.5 block text-xs text-zinc-500">{{ hint }}</span>
    </label>
</template>
