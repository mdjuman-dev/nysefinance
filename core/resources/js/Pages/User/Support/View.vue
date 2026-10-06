<script setup>
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Badge from '@/Components/UI/Badge.vue';
import FileDrop from '@/Components/UI/FileDrop.vue';
import Modal from '@/Components/UI/Modal.vue';
import { useToast } from '@/composables/useToast';
import { postJson, messageText } from '@/utils/http';
import { formatDateTime, priorityTone, ticketStatusTone } from '@/utils/display';

const props = defineProps({ ticket: Object, messages: Array, urls: Object });
const toast = useToast();

const message = ref('');
const files = ref([]);
const sending = ref(false);
const confirmClose = ref(false);
const thread = ref(null);

const scrollDown = () => nextTick(() => thread.value && (thread.value.scrollTop = thread.value.scrollHeight));

async function send() {
    if (!message.value.trim()) return;
    sending.value = true;
    const data = { message: message.value };
    files.value.forEach((f, i) => (data[`attachments[${i}]`] = f));
    // replyTicket answers XHR requests with JSON, so post with fetch and refresh the thread.
    const res = await postJson(props.urls.reply, data, { asForm: true });
    sending.value = false;
    if (res.status === 'success') {
        message.value = '';
        files.value = [];
        router.reload({ only: ['messages', 'ticket'], onSuccess: scrollDown });
    } else {
        toast.error(messageText(res.message) || 'Could not send your reply');
    }
}

function closeTicket() {
    router.post(props.urls.close, {}, { preserveScroll: true, onFinish: () => (confirmClose.value = false) });
}

// Poll for staff replies (the Blade page polled every few seconds too).
let timer;
onMounted(() => {
    scrollDown();
    timer = setInterval(() => !document.hidden && router.reload({ only: ['messages', 'ticket'] }), 15000);
});
onBeforeUnmount(() => clearInterval(timer));
watch(() => props.messages.length, scrollDown);

const onKey = (e) => {
    if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) send();
};
</script>

<template>
    <Head :title="`Ticket #${ticket.ticket}`" />

    <div class="mx-auto flex max-w-4xl flex-col">
        <!-- Header -->
        <div class="mb-4 flex items-start gap-3">
            <Link :href="urls.index" class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-white/[0.07] text-lg text-zinc-400 hover:text-white" aria-label="Back to tickets"><i class="ri-arrow-left-line"></i></Link>
            <div class="min-w-0 flex-1">
                <h1 class="truncate text-lg font-bold text-white sm:text-xl">{{ ticket.subject }}</h1>
                <div class="mt-1 flex flex-wrap items-center gap-1.5 text-xs text-zinc-500">
                    <span>#{{ ticket.ticket }}</span>
                    <Badge :tone="ticketStatusTone(ticket.status)" dot>{{ ticket.status }}</Badge>
                    <Badge :tone="priorityTone(ticket.priority)">{{ ticket.priority }} priority</Badge>
                </div>
            </div>
            <button v-if="!ticket.closed" class="btn-ghost shrink-0 px-3 py-2 text-xs" @click="confirmClose = true"><i class="ri-close-circle-line"></i> <span class="hidden sm:inline">Close ticket</span></button>
        </div>

        <!-- Thread -->
        <div ref="thread" class="card flex-1 space-y-4 overflow-y-auto p-4 sm:p-6" style="max-height: calc(100vh - 22rem); min-height: 18rem">
            <div v-for="m in messages" :key="m.id" class="flex gap-3" :class="m.mine ? 'flex-row-reverse' : ''">
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg text-xs font-bold" :class="m.mine ? 'bg-brand-500 text-ink-950' : 'bg-sky-500/20 text-sky-300'">
                    <i v-if="!m.mine" class="ri-customer-service-2-line text-base"></i>
                    <template v-else>{{ (m.author || '?').slice(0, 1).toUpperCase() }}</template>
                </span>
                <div class="max-w-[82%] sm:max-w-[70%]" :class="m.mine ? 'text-right' : ''">
                    <div class="inline-block rounded-2xl px-4 py-2.5 text-left text-sm leading-6 whitespace-pre-line" :class="m.mine ? 'rounded-tr-md bg-brand-500/15 text-zinc-100' : 'rounded-tl-md bg-white/[0.05] text-zinc-200'">{{ m.message }}</div>
                    <div v-if="m.files.length" class="mt-1.5 flex flex-wrap gap-1.5" :class="m.mine ? 'justify-end' : ''">
                        <a v-for="f in m.files" :key="f.url" :href="f.url" target="_blank" class="inline-flex items-center gap-1 rounded-lg bg-white/[0.04] px-2 py-1 text-[11px] text-zinc-300 hover:text-white"><i class="ri-attachment-2"></i>{{ f.name }}</a>
                    </div>
                    <p class="mt-1 text-[11px] text-zinc-500">{{ m.author }} · {{ formatDateTime(m.date) }}</p>
                </div>
            </div>
        </div>

        <!-- Composer -->
        <div v-if="!ticket.closed" class="card mt-3 p-2 sm:p-3">
            <div v-if="files.length" class="flex flex-wrap gap-1.5 px-2 pt-1 pb-2">
                <span v-for="(f, i) in files" :key="i" class="inline-flex items-center gap-1 rounded-lg bg-white/[0.05] px-2 py-1 text-[11px] text-zinc-300">
                    {{ f.name }} <button class="hover:text-down" aria-label="Remove file" @click="files.splice(i, 1)"><i class="ri-close-line"></i></button>
                </span>
            </div>
            <div class="flex items-end gap-1">
                <FileDrop v-model="files" compact />
                <textarea v-model="message" rows="1" placeholder="Write a reply…" class="max-h-40 min-h-10 flex-1 resize-none bg-transparent px-2 py-2.5 text-sm text-zinc-100 placeholder:text-zinc-500 focus:outline-none" @keydown="onKey" @input="$event.target.style.height = 'auto'; $event.target.style.height = $event.target.scrollHeight + 'px'"></textarea>
                <button class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-500 text-lg text-ink-950 transition hover:bg-brand-400 disabled:opacity-40" :disabled="sending || !message.trim()" aria-label="Send reply" @click="send">
                    <i :class="sending ? 'ri-loader-4-line animate-spin' : 'ri-send-plane-2-fill'"></i>
                </button>
            </div>
        </div>
        <p v-else class="mt-3 rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 text-center text-sm text-zinc-500">This ticket is closed. Open a new ticket if you need more help.</p>
    </div>

    <Modal :show="confirmClose" title="Close this ticket?" @close="confirmClose = false">
        <p class="text-sm text-zinc-400">You will not be able to reply after closing. You can always open a new ticket.</p>
        <div class="mt-5 grid grid-cols-2 gap-2">
            <button class="btn-ghost" @click="confirmClose = false">Keep open</button>
            <button class="btn bg-down/15 text-down hover:bg-down/25" @click="closeTicket">Close ticket</button>
        </div>
    </Modal>
</template>
