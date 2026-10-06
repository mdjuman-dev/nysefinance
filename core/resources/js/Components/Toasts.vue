<script setup>
import { watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useToast } from '@/composables/useToast';

const page = usePage();
const { toasts, dismiss, push } = useToast();

const styles = {
    success: { icon: 'ri-checkbox-circle-fill', color: 'text-up' },
    error: { icon: 'ri-error-warning-fill', color: 'text-down' },
    warning: { icon: 'ri-alert-fill', color: 'text-amber-400' },
    info: { icon: 'ri-information-fill', color: 'text-sky-400' },
};
const styleOf = (type) => styles[type] || styles.info;

watch(
    () => page.props.notify,
    (items) => (items || []).forEach((n) => push(n.type, n.message)),
    { immediate: true },
);
</script>

<template>
    <div class="pointer-events-none fixed right-4 bottom-24 z-[60] flex w-[calc(100%-2rem)] max-w-sm flex-col gap-3 lg:bottom-4">
        <transition-group
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-3"
            leave-active-class="transition duration-200 ease-in"
            leave-to-class="opacity-0 translate-x-6"
        >
            <div
                v-for="t in toasts"
                :key="t.id"
                class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-white/[0.08] bg-ink-850 p-4 shadow-2xl shadow-black/40"
                role="status"
            >
                <i :class="[styleOf(t.type).icon, styleOf(t.type).color]" class="text-xl leading-none"></i>
                <p class="flex-1 text-sm text-zinc-200">{{ t.message }}</p>
                <button class="text-zinc-500 hover:text-zinc-200" aria-label="Dismiss" @click="dismiss(t.id)">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        </transition-group>
    </div>
</template>
