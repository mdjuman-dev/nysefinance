<script setup>
import { onBeforeUnmount, watch } from 'vue';

// Bottom sheet on phones, centred dialog from sm up.
const props = defineProps({
    show: Boolean,
    title: String,
    size: { type: String, default: 'max-w-md' },
});
const emit = defineEmits(['close']);

function onKey(e) {
    if (e.key === 'Escape') emit('close');
}
watch(
    () => props.show,
    (open) => {
        document.body.style.overflow = open ? 'hidden' : '';
        open ? window.addEventListener('keydown', onKey) : window.removeEventListener('keydown', onKey);
    },
);
onBeforeUnmount(() => {
    document.body.style.overflow = '';
    window.removeEventListener('keydown', onKey);
});
</script>

<template>
    <Teleport to="body">
        <transition enter-active-class="transition duration-200" enter-from-class="opacity-0" leave-active-class="transition duration-150" leave-to-class="opacity-0">
            <div v-if="show" class="fixed inset-0 z-[65] bg-black/70 backdrop-blur-sm" @click="emit('close')"></div>
        </transition>
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="translate-y-full opacity-0 sm:translate-y-4"
            leave-active-class="transition duration-200 ease-in"
            leave-to-class="translate-y-full opacity-0 sm:translate-y-4"
        >
            <div v-if="show" class="pointer-events-none fixed inset-0 z-[66] flex items-end justify-center sm:items-center sm:p-4">
                <div
                    class="pointer-events-auto flex max-h-[92vh] w-full flex-col rounded-t-3xl border border-white/[0.08] bg-ink-850 shadow-2xl sm:rounded-3xl"
                    :class="size"
                    role="dialog"
                    aria-modal="true"
                    :aria-label="title"
                >
                    <div class="mx-auto mt-2.5 h-1 w-10 rounded-full bg-white/15 sm:hidden"></div>
                    <div class="flex items-center justify-between gap-4 px-5 pt-4 pb-3 sm:px-6 sm:pt-5">
                        <h3 class="text-base font-semibold text-white">{{ title }}</h3>
                        <button class="grid h-9 w-9 place-items-center rounded-lg text-xl text-zinc-400 hover:bg-white/[0.06] hover:text-white" aria-label="Close" @click="emit('close')">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                    <div class="overflow-y-auto px-5 pb-[max(1.25rem,env(safe-area-inset-bottom))] sm:px-6 sm:pb-6">
                        <slot />
                    </div>
                </div>
            </div>
        </transition>
    </Teleport>
</template>
