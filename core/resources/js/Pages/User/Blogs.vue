<script setup>
import { reactive } from 'vue';
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/UI/PageHeader.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import { postJson } from '@/utils/http';
import { timeAgo } from '@/utils/format';

const props = defineProps({ blogs: Object, urls: Object });

// Likes are anonymous counters server-side; remember what this browser liked.
const KEY = 'likedBlogs';
let saved = [];
try { saved = JSON.parse(localStorage.getItem(KEY) || '[]'); } catch {}
const liked = reactive(new Set(saved));
const counts = reactive(Object.fromEntries(props.blogs.data.map((b) => [b.id, b.likes])));
const busy = reactive({});

async function toggle(blog) {
    if (busy[blog.id]) return;
    busy[blog.id] = true;
    const isLiked = liked.has(blog.id);
    const res = await postJson((isLiked ? props.urls.unlike : props.urls.like).replace('__ID__', blog.id));
    busy[blog.id] = false;
    if (!res.success) return;
    counts[blog.id] = res.likes;
    isLiked ? liked.delete(blog.id) : liked.add(blog.id);
    try { localStorage.setItem(KEY, JSON.stringify([...liked])); } catch {}
}
</script>

<template>
    <Head title="Community" />
    <PageHeader title="Community posts" subtitle="Ideas and updates from the community" icon="ri-article-line" />

    <EmptyState v-if="!blogs.data.length" icon="ri-article-line" title="No posts yet" class="card" />

    <template v-else>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <article v-for="b in blogs.data" :key="b.id" class="card card-hover flex flex-col overflow-hidden">
                <img v-if="b.image" :src="b.image" :alt="b.title" loading="lazy" class="h-40 w-full object-cover" />
                <div class="flex flex-1 flex-col p-5">
                    <h2 class="line-clamp-2 font-semibold text-white">{{ b.title }}</h2>
                    <p class="mt-2 flex-1 text-sm leading-6 whitespace-pre-line text-zinc-400">{{ b.content }}</p>
                    <div class="mt-4 flex items-center gap-3 border-t border-white/[0.05] pt-4">
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-gradient-to-br from-brand-400 to-sky-500 text-[11px] font-bold text-ink-950">
                            {{ (b.author || '?').split(' ').map((w) => w[0]).slice(0, 2).join('').toUpperCase() }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-zinc-200">{{ b.author }}</p>
                            <p class="text-[11px] text-zinc-500">{{ timeAgo(b.date) }}</p>
                        </div>
                        <button
                            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm transition"
                            :class="liked.has(b.id) ? 'bg-down/10 text-down' : 'text-zinc-400 hover:bg-white/[0.05] hover:text-white'"
                            :aria-pressed="liked.has(b.id)"
                            aria-label="Like"
                            @click="toggle(b)"
                        >
                            <i :class="liked.has(b.id) ? 'ri-heart-3-fill' : 'ri-heart-3-line'"></i> {{ counts[b.id] }}
                        </button>
                    </div>
                </div>
            </article>
        </div>
        <div v-if="blogs.meta.last > 1" class="card mt-5 overflow-hidden"><Pagination :meta="blogs.meta" /></div>
    </template>
</template>
