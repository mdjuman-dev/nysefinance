<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Toasts from '@/Components/Toasts.vue';

const page = usePage();
const site = computed(() => page.props.site);
const home = computed(() => page.props.authUrls?.home || '/');
const year = new Date().getFullYear();

const perks = [
    { icon: 'ri-flashlight-line', title: 'Spot & futures in one place', text: 'Live order books, leverage trading and TP/SL built in.' },
    { icon: 'ri-shield-check-line', title: 'Security first', text: '2FA, email & SMS verification and KYC-protected withdrawals.' },
    { icon: 'ri-line-chart-line', title: 'Stocks, bonds & copy trading', text: 'Put idle balance to work with daily interest products.' },
];
</script>

<template>
    <div class="relative min-h-screen overflow-x-clip bg-ink-950">
        <div class="pointer-events-none absolute inset-0">
            <div class="grid-bg absolute inset-0 opacity-60"></div>
            <div class="absolute -top-48 left-1/4 h-[520px] w-[720px] max-w-full rounded-full bg-brand-500/12 blur-[130px]"></div>
            <div class="absolute right-0 bottom-0 h-[380px] w-[380px] max-w-full rounded-full bg-sky-500/10 blur-[110px]"></div>
        </div>

        <div class="relative grid min-h-screen grid-cols-1 lg:grid-cols-[1.05fr_1fr]">
            <!-- brand panel (desktop) -->
            <aside class="relative hidden flex-col justify-between overflow-hidden border-r border-white/[0.06] p-12 lg:flex xl:p-16">
                <a :href="home" class="inline-flex"><img :src="site.logo" :alt="site.name" class="h-11 w-auto" /></a>

                <div class="max-w-lg">
                    <p class="eyebrow mb-4">Trade with confidence</p>
                    <h2 class="text-4xl leading-tight font-bold text-white xl:text-5xl">
                        Your gateway to <span class="text-gradient">global markets</span>
                    </h2>
                    <p class="mt-4 text-zinc-400">One account for crypto spot, futures, P2P, stocks and bonds — built for speed on every device.</p>

                    <ul class="mt-10 space-y-5">
                        <li v-for="p in perks" :key="p.title" class="flex gap-4">
                            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl border border-brand-500/20 bg-brand-500/10 text-xl text-brand-300"><i :class="p.icon"></i></span>
                            <div>
                                <p class="font-semibold text-white">{{ p.title }}</p>
                                <p class="text-sm text-zinc-500">{{ p.text }}</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <p class="text-xs text-zinc-600">© {{ year }} {{ site.name }}. All rights reserved.</p>
            </aside>

            <!-- form side -->
            <main class="flex flex-col px-4 py-6 sm:px-8 lg:px-12">
                <div class="flex items-center justify-between lg:justify-end">
                    <a :href="home" class="lg:hidden"><img :src="site.logo" :alt="site.name" class="h-9 w-auto" /></a>
                    <a :href="home" class="inline-flex items-center gap-1.5 text-sm text-zinc-400 transition hover:text-white">
                        <i class="ri-arrow-left-line"></i> Home
                    </a>
                </div>

                <div class="flex flex-1 items-center justify-center py-8">
                    <div class="w-full max-w-md">
                        <slot />
                    </div>
                </div>

                <p class="text-center text-xs text-zinc-600 lg:hidden">© {{ year }} {{ site.name }}</p>
            </main>
        </div>

        <Toasts />
    </div>
</template>
