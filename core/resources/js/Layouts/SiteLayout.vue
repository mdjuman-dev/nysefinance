<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Toasts from '@/Components/Toasts.vue';

const page = usePage();
const site = computed(() => page.props.site);
const routes = computed(() => page.props.routes);
const user = computed(() => page.props.auth?.user);

const scrolled = ref(false);
const menuOpen = ref(false);

const nav = computed(() => [
    { label: 'Home', href: routes.value.home },
    { label: 'Markets', href: routes.value.crypto },
    { label: 'About', href: routes.value.about },
    { label: 'Contact', href: routes.value.contact },
]);

const isActive = (href) => {
    const path = new URL(href, window.location.origin).pathname;
    return path === '/' ? page.url === '/' : page.url.startsWith(path);
};

const onScroll = () => (scrolled.value = window.scrollY > 12);
onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
});
onBeforeUnmount(() => window.removeEventListener('scroll', onScroll));
watch(() => page.url, () => (menuOpen.value = false));

const year = new Date().getFullYear();
</script>

<template>
    <div class="relative min-h-screen overflow-x-clip">
        <!-- ambient glow -->
        <div class="pointer-events-none absolute inset-x-0 top-0 -z-0 h-[720px]">
            <div class="grid-bg absolute inset-0"></div>
            <div class="absolute -top-40 left-1/2 h-[520px] w-[900px] -translate-x-1/2 rounded-full bg-brand-500/15 blur-[120px]"></div>
            <div class="absolute top-40 -right-40 h-[360px] w-[360px] rounded-full bg-sky-500/10 blur-[100px]"></div>
        </div>

        <header
            class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
            :class="scrolled || menuOpen ? 'border-b border-white/[0.06] bg-ink-950/80 backdrop-blur-xl' : 'bg-transparent'"
        >
            <div class="container-x flex h-16 items-center justify-between gap-6 lg:h-20">
                <Link :href="routes.home" class="flex shrink-0 items-center gap-2.5">
                    <img :src="site.logo" :alt="site.name" class="h-9 w-auto lg:h-11" />
                </Link>

                <nav class="hidden items-center gap-1 rounded-full border border-white/[0.06] bg-white/[0.02] p-1 md:flex">
                    <Link
                        v-for="item in nav"
                        :key="item.label"
                        :href="item.href"
                        class="rounded-full px-4 py-1.5 text-sm font-medium transition"
                        :class="isActive(item.href) ? 'bg-white/[0.08] text-white' : 'text-zinc-400 hover:text-white'"
                    >
                        {{ item.label }}
                    </Link>
                </nav>

                <div class="hidden items-center gap-2 md:flex">
                    <template v-if="user">
                        <a :href="routes.dashboard" class="btn-primary">
                            <i class="ri-dashboard-3-line text-base"></i> Dashboard
                        </a>
                    </template>
                    <template v-else>
                        <a :href="routes.login" class="btn px-4 text-zinc-300 hover:text-white">Log in</a>
                        <a :href="routes.register" class="btn-primary">Get started <i class="ri-arrow-right-line"></i></a>
                    </template>
                </div>

                <button
                    type="button"
                    class="grid h-10 w-10 place-items-center rounded-xl border border-white/10 text-xl text-zinc-200 md:hidden"
                    :aria-expanded="menuOpen"
                    aria-label="Toggle menu"
                    @click="menuOpen = !menuOpen"
                >
                    <i :class="menuOpen ? 'ri-close-line' : 'ri-menu-3-line'"></i>
                </button>
            </div>

            <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                leave-active-class="transition duration-150 ease-in"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div v-if="menuOpen" class="container-x pb-5 md:hidden">
                    <div class="space-y-1 rounded-2xl border border-white/[0.08] bg-ink-900 p-2 shadow-2xl shadow-black/60">
                        <Link
                            v-for="item in nav"
                            :key="item.label"
                            :href="item.href"
                            class="block rounded-xl px-4 py-3 text-sm font-medium"
                            :class="isActive(item.href) ? 'bg-white/[0.06] text-white' : 'text-zinc-300'"
                        >
                            {{ item.label }}
                        </Link>
                        <div class="grid grid-cols-2 gap-2 p-2 pt-3">
                            <template v-if="user">
                                <a :href="routes.dashboard" class="btn-primary col-span-2">Dashboard</a>
                            </template>
                            <template v-else>
                                <a :href="routes.login" class="btn-ghost">Log in</a>
                                <a :href="routes.register" class="btn-primary">Sign up</a>
                            </template>
                        </div>
                    </div>
                </div>
            </transition>
        </header>

        <main class="relative z-10">
            <slot />
        </main>

        <footer class="relative z-10 mt-24 border-t border-white/[0.06] bg-ink-900/60">
            <div class="container-x grid grid-cols-1 gap-12 py-16 md:grid-cols-12">
                <div class="md:col-span-5">
                    <img :src="site.logo" :alt="site.name" class="h-12 w-auto" />
                    <p class="mt-5 max-w-sm text-sm leading-6 text-zinc-400">
                        {{ site.name }} is a digital asset exchange where you can trade Bitcoin, Ethereum and other cryptocurrencies
                        with professional tools, strong security and round-the-clock support.
                    </p>
                    <a :href="site.apk" class="mt-6 inline-flex items-center gap-3 rounded-xl border border-white/10 bg-white/[0.03] px-4 py-2.5 transition hover:border-white/20">
                        <i class="ri-android-fill text-2xl text-brand-400"></i>
                        <span class="leading-tight">
                            <span class="block text-[10px] tracking-wider text-zinc-500 uppercase">Download for</span>
                            <span class="text-sm font-semibold">Android</span>
                        </span>
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 md:col-span-7">
                    <div>
                        <h4 class="text-xs font-semibold tracking-wider text-zinc-500 uppercase">Platform</h4>
                        <ul class="mt-4 space-y-3 text-sm">
                            <li><Link :href="routes.crypto" class="text-zinc-300 hover:text-white">Markets</Link></li>
                            <li><a :href="routes.register" class="text-zinc-300 hover:text-white">Create account</a></li>
                            <li><a :href="routes.login" class="text-zinc-300 hover:text-white">Log in</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold tracking-wider text-zinc-500 uppercase">Company</h4>
                        <ul class="mt-4 space-y-3 text-sm">
                            <li><Link :href="routes.about" class="text-zinc-300 hover:text-white">About us</Link></li>
                            <li><Link :href="routes.contact" class="text-zinc-300 hover:text-white">Contact</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold tracking-wider text-zinc-500 uppercase">Legal</h4>
                        <ul class="mt-4 space-y-3 text-sm">
                            <li><Link :href="routes.privacy" class="text-zinc-300 hover:text-white">Privacy policy</Link></li>
                            <li><Link :href="routes.terms" class="text-zinc-300 hover:text-white">Terms &amp; conditions</Link></li>
                            <li><Link :href="routes.trade" class="text-zinc-300 hover:text-white">Trade policy</Link></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/[0.06]">
                <div class="container-x flex flex-col items-center justify-between gap-3 py-6 text-xs text-zinc-500 sm:flex-row">
                    <p>&copy; {{ year }} {{ site.name }}. All rights reserved.</p>
                    <p class="flex items-center gap-2">
                        <span class="h-1.5 w-1.5 animate-pulse-dot rounded-full bg-up"></span> All systems operational
                    </p>
                </div>
            </div>
        </footer>

        <Toasts />
    </div>
</template>
