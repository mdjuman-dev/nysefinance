<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({ title: String, slug: String, content: String });

const routes = computed(() => usePage().props.routes);
const legal = computed(() => [
    { label: 'Privacy policy', href: routes.value.privacy, slug: 'privacy-policy' },
    { label: 'Terms & conditions', href: routes.value.terms, slug: 'terms-condition' },
    { label: 'Trade policy', href: routes.value.trade, slug: 'trade-policy' },
]);
const isLegal = computed(() => legal.value.some((l) => l.slug === props.slug));
</script>

<template>
    <Head :title="title" />

    <section class="container-x pt-32 lg:pt-40">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <aside v-if="isLegal" class="lg:col-span-3">
                <div class="card sticky top-28 p-2">
                    <p class="px-3 pt-2 pb-1 text-[11px] font-semibold tracking-wider text-zinc-500 uppercase">Legal</p>
                    <Link
                        v-for="l in legal"
                        :key="l.slug"
                        :href="l.href"
                        class="flex items-center justify-between rounded-xl px-3 py-2.5 text-sm transition"
                        :class="slug === l.slug ? 'bg-brand-500/10 font-semibold text-brand-300' : 'text-zinc-400 hover:bg-white/[0.03] hover:text-white'"
                    >
                        {{ l.label }} <i class="ri-arrow-right-s-line"></i>
                    </Link>
                </div>
            </aside>

            <article class="card p-6 sm:p-10" :class="isLegal ? 'lg:col-span-9' : 'lg:col-span-10 lg:col-start-2'">
                <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">{{ title }}</h1>
                <div class="mt-6 h-px bg-white/[0.06]"></div>
                <div v-if="content" class="cms-content mt-4" v-html="content"></div>
                <p v-else class="py-16 text-center text-zinc-500">This page has no content yet.</p>
            </article>
        </div>
    </section>
</template>
