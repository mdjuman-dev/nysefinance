<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import CoinIcon from '@/Components/CoinIcon.vue';
import PageHero from '@/Components/PageHero.vue';
import SectionHeading from '@/Components/SectionHeading.vue';
import { formatPercent, formatPrice, trendClass } from '@/utils/format';

defineProps({
    content: { type: String, default: null },
    markets: { type: Array, default: () => [] },
});

const page = usePage();
const site = computed(() => page.props.site);
const routes = computed(() => page.props.routes);

const values = [
    { icon: 'ri-shield-star-line', title: 'Security first', text: 'Layered protection for accounts, wallets and every transaction on the platform.' },
    { icon: 'ri-eye-line', title: 'Transparency', text: 'Clear fees, clear policies and real-time market data you can verify.' },
    { icon: 'ri-rocket-2-line', title: 'Performance', text: 'Infrastructure built to keep trading responsive when markets move fast.' },
    { icon: 'ri-heart-3-line', title: 'People-focused', text: 'Round-the-clock support from a team that understands traders.' },
];
</script>

<template>
    <Head title="About us" />

    <PageHero
        eyebrow="About us"
        :title="`Building a better way to trade digital assets`"
        :subtitle="`${site.name} brings professional trading tools, strong security and reliable support together in one platform.`"
    >
        <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div v-for="m in markets" :key="m.symbol" class="card flex items-center gap-3 p-4">
                <CoinIcon :src="m.image" :symbol="m.symbol" />
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white">{{ m.symbol }}</p>
                    <p class="font-mono text-xs text-zinc-400">${{ formatPrice(m.price) }}</p>
                </div>
                <span class="font-mono text-xs font-semibold" :class="trendClass(m.change24h)">{{ formatPercent(m.change24h) }}</span>
            </div>
        </div>
    </PageHero>

    <section class="container-x py-16">
        <SectionHeading eyebrow="What we stand for" title="Our values" />
        <div class="mt-12 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <article v-for="v in values" :key="v.title" class="card card-hover p-6">
                <i :class="v.icon" class="text-3xl text-brand-400"></i>
                <h3 class="mt-5 font-semibold text-white">{{ v.title }}</h3>
                <p class="mt-2 text-sm leading-6 text-zinc-400">{{ v.text }}</p>
            </article>
        </div>
    </section>

    <section v-if="content" class="container-x">
        <article class="card mx-auto max-w-4xl p-6 sm:p-10">
            <div class="cms-content" v-html="content"></div>
        </article>
    </section>

    <section class="container-x pt-16">
        <div class="card flex flex-col items-center justify-between gap-6 p-8 sm:flex-row sm:p-10">
            <div>
                <h2 class="text-2xl font-bold text-white">Have a question for our team?</h2>
                <p class="mt-2 text-sm text-zinc-400">We are here seven days a week.</p>
            </div>
            <div class="flex gap-3">
                <Link :href="routes.contact" class="btn-ghost">Contact us</Link>
                <a :href="routes.register" class="btn-primary">Get started</a>
            </div>
        </div>
    </section>
</template>
