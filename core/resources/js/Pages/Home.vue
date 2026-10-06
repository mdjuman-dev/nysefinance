<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import CoinIcon from '@/Components/CoinIcon.vue';
import SectionHeading from '@/Components/SectionHeading.vue';
import { useLiveMarkets } from '@/composables/useLiveMarkets';
import { formatCompact, formatPercent, formatPrice, trendClass } from '@/utils/format';

const props = defineProps({ markets: { type: Array, default: () => [] } });

const page = usePage();
const routes = computed(() => page.props.routes);
const site = computed(() => page.props.site);
const user = computed(() => page.props.auth?.user);

const { markets } = useLiveMarkets(props.markets);
const heroMarkets = computed(() => markets.value.slice(0, 5));
const tickerMarkets = computed(() => [...markets.value, ...markets.value]);

const features = [
    { icon: 'ri-flashlight-line', title: 'Fast execution', text: 'A reliable matching engine and a responsive trading interface keep your orders moving.' },
    { icon: 'ri-shield-keyhole-line', title: 'Secure platform', text: 'Multi-layer security with encryption, protected wallets and strong authentication.' },
    { icon: 'ri-line-chart-line', title: 'Real-time charts', text: 'Professional charting with technical indicators and live market data.' },
    { icon: 'ri-team-line', title: 'Trader community', text: 'Connect with other traders, follow strategies and share market insights.' },
    { icon: 'ri-stack-line', title: 'Flexible order types', text: 'Market, limit and stop orders so you can trade the way you plan.' },
    { icon: 'ri-percent-line', title: 'Competitive fees', text: 'A transparent fee structure with competitive maker and taker rates.' },
];

const platformPoints = [
    'Real-time market data and price feeds',
    'Multiple order types for trading flexibility',
    'Responsive interface on every device',
    'Customer support and help resources',
    'Secure wallet and account management',
    'Professional charting and analysis tools',
];

const steps = [
    { icon: 'ri-user-add-line', title: 'Create your account', text: 'Sign up for free with email verification and a secure password.' },
    { icon: 'ri-wallet-3-line', title: 'Secure your wallet', text: 'Enable authentication and fund your wallet with your preferred method.' },
    { icon: 'ri-exchange-funds-line', title: 'Start trading', text: 'Explore markets, analyse charts and place your first trade in minutes.' },
];

const appPerks = [
    { icon: 'ri-shield-check-line', title: 'Secure trading', text: 'Bank-level security' },
    { icon: 'ri-flashlight-line', title: 'Lightning fast', text: 'Instant transactions' },
    { icon: 'ri-hand-heart-line', title: 'User friendly', text: 'Intuitive interface' },
];
</script>

<template>
    <Head title="Trade crypto like a pro" />

    <!-- Hero -->
    <section class="container-x relative pt-32 pb-16 lg:pt-40 lg:pb-24">
        <div class="grid grid-cols-1 items-center gap-14 lg:grid-cols-12">
            <div class="lg:col-span-6">
                <span class="eyebrow"><span class="h-1.5 w-1.5 animate-pulse-dot rounded-full bg-brand-400"></span> Live markets, 24/7</span>
                <h1 class="mt-6 text-4xl leading-[1.05] font-extrabold tracking-tight text-white sm:text-6xl">
                    Trade crypto<br />
                    <span class="text-gradient">like a pro.</span>
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-zinc-400">
                    Advanced trading tools, fast execution and serious security. Everything you need to trade digital assets with
                    confidence on {{ site.name }}.
                </p>
                <div class="mt-9 flex flex-wrap items-center gap-3">
                    <a :href="user ? routes.dashboard : routes.register" class="btn-primary px-6 py-3 text-base">
                        {{ user ? 'Go to dashboard' : 'Start trading free' }} <i class="ri-arrow-right-line"></i>
                    </a>
                    <Link :href="routes.crypto" class="btn-ghost px-6 py-3 text-base">
                        <i class="ri-bar-chart-box-line"></i> Explore markets
                    </Link>
                </div>
                <dl class="mt-12 grid max-w-lg grid-cols-3 gap-6 border-t border-white/[0.06] pt-8">
                    <div>
                        <dt class="text-xs text-zinc-500">Security</dt>
                        <dd class="mt-1 flex items-center gap-1.5 text-sm font-semibold text-zinc-200"><i class="ri-shield-check-fill text-brand-400"></i> Protected</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-zinc-500">Market data</dt>
                        <dd class="mt-1 flex items-center gap-1.5 text-sm font-semibold text-zinc-200"><i class="ri-pulse-line text-brand-400"></i> Real-time</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-zinc-500">Support</dt>
                        <dd class="mt-1 flex items-center gap-1.5 text-sm font-semibold text-zinc-200"><i class="ri-customer-service-2-fill text-brand-400"></i> 24/7</dd>
                    </div>
                </dl>
            </div>

            <!-- Market card -->
            <div class="relative lg:col-span-6">
                <div class="absolute -inset-6 -z-10 rounded-[2rem] bg-gradient-to-tr from-brand-500/20 via-transparent to-sky-500/10 blur-2xl"></div>
                <div class="card overflow-hidden shadow-2xl shadow-black/50">
                    <div class="flex items-center justify-between border-b border-white/[0.06] px-5 py-4">
                        <div class="flex items-center gap-2">
                            <i class="ri-fire-line text-brand-400"></i>
                            <h3 class="text-sm font-semibold text-white">Top markets</h3>
                        </div>
                        <span class="flex items-center gap-1.5 rounded-full bg-up/10 px-2.5 py-1 text-[11px] font-semibold text-up">
                            <span class="h-1.5 w-1.5 animate-pulse-dot rounded-full bg-up"></span> LIVE
                        </span>
                    </div>
                    <div class="grid grid-cols-[1fr_auto_auto] gap-x-4 px-5 pt-3 pb-1 text-[11px] font-medium tracking-wider text-zinc-500 uppercase">
                        <span>Asset</span><span class="text-right">Price</span><span class="w-20 text-right">24h</span>
                    </div>
                    <ul>
                        <li
                            v-for="m in heroMarkets"
                            :key="m.symbol"
                            class="grid grid-cols-[1fr_auto_auto] items-center gap-x-4 px-5 py-3.5 transition hover:bg-white/[0.02]"
                        >
                            <div class="flex min-w-0 items-center gap-3">
                                <CoinIcon :src="m.image" :symbol="m.symbol" />
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-white">{{ m.symbol }}</p>
                                    <p class="truncate text-xs text-zinc-500">{{ m.name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-mono text-sm font-semibold text-zinc-100">${{ formatPrice(m.price) }}</p>
                                <p class="text-xs text-zinc-500">Cap {{ formatCompact(m.marketCap) }}</p>
                            </div>
                            <span
                                class="w-20 rounded-lg py-1 text-center font-mono text-xs font-semibold"
                                :class="[trendClass(m.change24h), m.change24h >= 0 ? 'bg-up/10' : 'bg-down/10']"
                            >
                                {{ formatPercent(m.change24h) }}
                            </span>
                        </li>
                        <li v-if="!heroMarkets.length" class="px-5 py-10 text-center text-sm text-zinc-500">Market data is loading…</li>
                    </ul>
                    <Link :href="routes.crypto" class="flex items-center justify-center gap-1.5 border-t border-white/[0.06] py-3.5 text-sm font-medium text-brand-300 transition hover:bg-white/[0.02]">
                        View all markets <i class="ri-arrow-right-up-line"></i>
                    </Link>
                </div>
            </div>
        </div>
    </section>

    <!-- Ticker -->
    <section v-if="markets.length" class="relative border-y border-white/[0.06] bg-ink-900/60 py-4" aria-label="Price ticker">
        <div class="overflow-hidden [mask-image:linear-gradient(to_right,transparent,#000_8%,#000_92%,transparent)]">
            <div class="flex w-max animate-marquee gap-10 hover:[animation-play-state:paused]">
                <div v-for="(m, i) in tickerMarkets" :key="i" class="flex items-center gap-3 whitespace-nowrap">
                    <CoinIcon :src="m.image" :symbol="m.symbol" size="h-6 w-6" />
                    <span class="text-sm font-semibold text-zinc-200">{{ m.symbol }}<span class="text-zinc-500">/USDT</span></span>
                    <span class="font-mono text-sm text-zinc-300">{{ formatPrice(m.price) }}</span>
                    <span class="font-mono text-xs font-semibold" :class="trendClass(m.change24h)">{{ formatPercent(m.change24h) }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="container-x scroll-mt-24 py-24">
        <SectionHeading
            eyebrow="Why choose us"
            title="Built for serious traders"
            subtitle="Professional-grade tools and strong security for traders at every level, in one platform."
        />
        <div class="mt-14 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <article v-for="f in features" :key="f.title" class="card card-hover group p-7">
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand-500/10 text-2xl text-brand-400 ring-1 ring-brand-500/20 transition group-hover:bg-brand-500 group-hover:text-ink-950">
                    <i :class="f.icon"></i>
                </div>
                <h3 class="mt-6 text-lg font-semibold text-white">{{ f.title }}</h3>
                <p class="mt-2 text-sm leading-6 text-zinc-400">{{ f.text }}</p>
            </article>
        </div>
    </section>

    <!-- Platform -->
    <section class="container-x py-12">
        <div class="card relative overflow-hidden p-8 sm:p-12 lg:p-16">
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-brand-500/15 blur-3xl"></div>
            <div class="relative grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
                <div>
                    <SectionHeading
                        align="left"
                        eyebrow="Global platform"
                        title="A trading platform you can rely on"
                        subtitle="Secure cryptocurrency trading with professional tools, wherever you are."
                    />
                    <div class="mt-10 grid grid-cols-2 gap-4">
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-5">
                            <i class="ri-cursor-line text-xl text-brand-400"></i>
                            <p class="mt-3 text-sm font-semibold text-white">Easy interface</p>
                            <p class="text-xs text-zinc-500">User-friendly</p>
                        </div>
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-5">
                            <i class="ri-global-line text-xl text-brand-400"></i>
                            <p class="mt-3 text-sm font-semibold text-white">Trade anywhere</p>
                            <p class="text-xs text-zinc-500">Global access</p>
                        </div>
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-5">
                            <i class="ri-lock-2-line text-xl text-brand-400"></i>
                            <p class="mt-3 text-sm font-semibold text-white">Protected assets</p>
                            <p class="text-xs text-zinc-500">Secure trading</p>
                        </div>
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-5">
                            <i class="ri-customer-service-line text-xl text-brand-400"></i>
                            <p class="mt-3 text-sm font-semibold text-white">24/7 support</p>
                            <p class="text-xs text-zinc-500">Quality service</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border border-white/[0.06] bg-ink-950/60 p-7 sm:p-8">
                    <h3 class="text-lg font-semibold text-white">Platform features</h3>
                    <ul class="mt-6 space-y-4">
                        <li v-for="p in platformPoints" :key="p" class="flex items-start gap-3 text-sm text-zinc-300">
                            <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-brand-500/15 text-xs text-brand-400">
                                <i class="ri-check-line"></i>
                            </span>
                            {{ p }}
                        </li>
                    </ul>
                    <a :href="routes.register" class="btn-primary mt-8 w-full">Open an account</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Steps -->
    <section id="how" class="container-x scroll-mt-24 py-24">
        <SectionHeading eyebrow="Get started" title="Start trading in three steps" />
        <div class="relative mt-14 grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="absolute top-10 right-[16%] left-[16%] hidden h-px bg-gradient-to-r from-transparent via-brand-500/40 to-transparent md:block"></div>
            <article v-for="(s, i) in steps" :key="s.title" class="relative text-center">
                <div class="relative mx-auto grid h-20 w-20 place-items-center rounded-2xl border border-white/10 bg-ink-850 text-3xl text-brand-400 shadow-lg shadow-black/40">
                    <i :class="s.icon"></i>
                    <span class="absolute -top-2 -right-2 grid h-7 w-7 place-items-center rounded-full bg-brand-500 font-mono text-xs font-bold text-ink-950">
                        {{ String(i + 1).padStart(2, '0') }}
                    </span>
                </div>
                <h3 class="mt-6 text-lg font-semibold text-white">{{ s.title }}</h3>
                <p class="mx-auto mt-2 max-w-xs text-sm leading-6 text-zinc-400">{{ s.text }}</p>
            </article>
        </div>
    </section>

    <!-- Mobile app -->
    <section id="app" class="container-x scroll-mt-24 py-12">
        <div class="grid grid-cols-1 items-center gap-14 lg:grid-cols-2">
            <div>
                <SectionHeading
                    align="left"
                    eyebrow="Mobile app"
                    subtitle="Real-time market data, secure transactions and professional trading tools in your pocket."
                >
                    <template #title>Trade on the go,<br /><span class="text-gradient">anytime, anywhere.</span></template>
                </SectionHeading>
                <div class="mt-8 space-y-4">
                    <div v-for="p in appPerks" :key="p.title" class="flex items-center gap-4">
                        <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-white/[0.04] text-xl text-brand-400 ring-1 ring-white/10">
                            <i :class="p.icon"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ p.title }}</p>
                            <p class="text-xs text-zinc-500">{{ p.text }}</p>
                        </div>
                    </div>
                </div>
                <a :href="site.apk" class="mt-10 inline-flex items-center gap-3 rounded-xl bg-white px-5 py-3 text-ink-950 transition hover:bg-zinc-200">
                    <i class="ri-android-fill text-3xl text-brand-600"></i>
                    <span class="leading-tight">
                        <span class="block text-[11px] text-zinc-600">Download the</span>
                        <span class="text-base font-bold">Android app</span>
                    </span>
                </a>
            </div>

            <!-- Phone mockup -->
            <div class="relative mx-auto w-full max-w-[300px]">
                <div class="absolute inset-0 -z-10 scale-110 rounded-full bg-brand-500/20 blur-3xl"></div>
                <div class="animate-float rounded-[2.75rem] border border-white/10 bg-ink-800 p-3 shadow-2xl shadow-black/60">
                    <div class="overflow-hidden rounded-[2.2rem] bg-ink-950">
                        <div class="flex items-center justify-between px-6 pt-4 text-[11px] text-zinc-400">
                            <span class="font-semibold">9:41</span>
                            <span class="h-5 w-20 rounded-full bg-ink-800"></span>
                            <span class="flex gap-1"><i class="ri-signal-wifi-fill"></i><i class="ri-battery-fill"></i></span>
                        </div>
                        <div class="px-5 pt-6 pb-6">
                            <p class="text-xs text-zinc-500">Portfolio balance</p>
                            <p class="mt-1 font-mono text-2xl font-bold text-white">$24,567.89</p>
                            <p class="mt-1 text-xs font-semibold text-up"><i class="ri-arrow-up-line"></i> +5.24% today</p>
                            <svg viewBox="0 0 240 70" class="mt-4 h-16 w-full" preserveAspectRatio="none" aria-hidden="true">
                                <defs>
                                    <linearGradient id="spark" x1="0" x2="0" y1="0" y2="1">
                                        <stop offset="0%" stop-color="#3fd46e" stop-opacity=".35" />
                                        <stop offset="100%" stop-color="#3fd46e" stop-opacity="0" />
                                    </linearGradient>
                                </defs>
                                <path d="M0 55 L20 50 L40 52 L60 40 L80 44 L100 30 L120 34 L140 22 L160 26 L180 14 L200 18 L220 8 L240 10 L240 70 L0 70 Z" fill="url(#spark)" />
                                <path d="M0 55 L20 50 L40 52 L60 40 L80 44 L100 30 L120 34 L140 22 L160 26 L180 14 L200 18 L220 8 L240 10" fill="none" stroke="#3fd46e" stroke-width="2" />
                            </svg>
                            <div class="mt-4 grid grid-cols-2 gap-2">
                                <span class="rounded-xl bg-up/15 py-2.5 text-center text-xs font-semibold text-up">Buy</span>
                                <span class="rounded-xl bg-down/15 py-2.5 text-center text-xs font-semibold text-down">Sell</span>
                            </div>
                            <ul class="mt-5 space-y-3">
                                <li v-for="m in heroMarkets.slice(0, 3)" :key="m.symbol" class="flex items-center gap-3">
                                    <CoinIcon :src="m.image" :symbol="m.symbol" size="h-7 w-7" />
                                    <span class="flex-1 text-xs font-semibold text-zinc-200">{{ m.symbol }}</span>
                                    <span class="font-mono text-xs" :class="trendClass(m.change24h)">{{ formatPercent(m.change24h) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="container-x pt-24">
        <div class="relative overflow-hidden rounded-3xl border border-brand-500/20 bg-gradient-to-br from-brand-600/30 via-ink-900 to-ink-900 px-6 py-16 text-center sm:px-16">
            <div class="grid-bg absolute inset-0 opacity-60"></div>
            <div class="relative">
                <h2 class="text-3xl font-bold tracking-tight text-white sm:text-5xl">Ready to start trading?</h2>
                <p class="mx-auto mt-5 max-w-xl text-zinc-300">
                    Join {{ site.name }} and trade with professional tools, real-time market data and secure infrastructure.
                </p>
                <div class="mt-9 flex flex-wrap justify-center gap-3">
                    <a :href="user ? routes.dashboard : routes.register" class="btn-primary px-7 py-3 text-base">
                        {{ user ? 'Go to dashboard' : 'Create free account' }}
                    </a>
                    <Link :href="routes.contact" class="btn-ghost px-7 py-3 text-base">Talk to us</Link>
                </div>
                <p class="mt-6 flex flex-wrap justify-center gap-x-6 gap-y-2 text-xs text-zinc-400">
                    <span><i class="ri-check-line text-brand-400"></i> Free registration</span>
                    <span><i class="ri-check-line text-brand-400"></i> No credit card required</span>
                </p>
            </div>
        </div>
    </section>
</template>
