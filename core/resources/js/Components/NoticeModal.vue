<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({ image: String });

// Same key and 6-hour snooze as the Blade layout, so dismissals carry over.
const KEY = 'chmsModalClosed';
const SNOOZE = 6 * 60 * 60 * 1000;
const open = ref(false);

onMounted(() => {
    if (!props.image) return;
    let last = null;
    try { last = Number(localStorage.getItem(KEY)); } catch {}
    open.value = !last || Date.now() - last > SNOOZE;
});

function close() {
    open.value = false;
    try { localStorage.setItem(KEY, String(Date.now())); } catch {}
}
</script>

<template>
    <transition enter-active-class="transition duration-200" enter-from-class="opacity-0" leave-active-class="transition duration-150" leave-to-class="opacity-0">
        <div v-if="open" class="fixed inset-0 z-[70] grid place-items-center bg-black/70 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="Announcement" @click.self="close" @keydown.esc="close">
            <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-white/10 bg-ink-850 shadow-2xl">
                <button class="absolute top-3 right-3 z-10 grid h-9 w-9 place-items-center rounded-full bg-black/50 text-xl text-white backdrop-blur hover:bg-black/70" aria-label="Close announcement" @click="close">
                    <i class="ri-close-line"></i>
                </button>
                <img :src="image" alt="Announcement" class="block max-h-[80vh] w-full object-contain" />
            </div>
        </div>
    </transition>
</template>
