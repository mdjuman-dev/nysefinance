<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Avatar from '@/Components/Avatar.vue';
import NoticeModal from '@/Components/NoticeModal.vue';
import Toasts from '@/Components/Toasts.vue';
import { useToast } from '@/composables/useToast';
import { copyText } from '@/utils/clipboard';

const page = usePage();
const site = computed(() => page.props.site);
const shell = computed(() => page.props.shell);
const user = computed(() => shell.value.user);
const links = computed(() => shell.value.links);

const drawerOpen = ref(false);
const profileOpen = ref(false);
const navFilter = ref('');
const toast = useToast();

// Trading terminals use the full width.
const wide = computed(() => String(page.component).startsWith('Trade/'));
const currentPath = computed(() => new URL(page.url, window.location.origin).pathname);
const isActive = (href) => href && new URL(href).pathname === currentPath.value;

const filteredNav = computed(() => {
    const q = navFilter.value.trim().toLowerCase();
    if (!q) return shell.value.nav;
    return shell.value.nav
        .map((g) => ({ ...g, items: g.items.filter((i) => i.label.toLowerCase().includes(q)) }))
        .filter((g) => g.items.length);
});

const tabs = computed(() => [
    { label: 'Home', icon: 'ri-home-5-line', activeIcon: 'ri-home-5-fill', href: links.value.home },
    { label: 'Stocks', icon: 'ri-stock-line', activeIcon: 'ri-stock-fill', href: links.value.stocks },
    { label: 'Trade', icon: 'ri-arrow-left-right-line', href: links.value.trade, primary: true },
    { label: 'Bonds', icon: 'ri-bank-line', activeIcon: 'ri-bank-fill', href: links.value.bonds },
    { label: 'Assets', icon: 'ri-wallet-3-line', activeIcon: 'ri-wallet-3-fill', href: links.value.wallet },
]);

const kycBadge = computed(() => ({
    verified: { label: 'Verified', cls: 'bg-up/10 text-up', icon: 'ri-verified-badge-fill' },
    pending: { label: 'KYC pending', cls: 'bg-amber-400/10 text-amber-300', icon: 'ri-time-line' },
    unverified: { label: 'Unverified', cls: 'bg-down/10 text-down', icon: 'ri-error-warning-line' },
})[user.value.kyc]);

async function copy(text, label) {
    if (await copyText(text)) toast.success(`${label} copied`);
    else toast.error('Could not copy to clipboard');
}

function onKey(e) {
    if (e.key === 'Escape') {
        drawerOpen.value = false;
        profileOpen.value = false;
    }
}
function onDocClick(e) {
    if (!e.target.closest('[data-profile-menu]')) profileOpen.value = false;
}
onMounted(() => {
    window.addEventListener('keydown', onKey);
    document.addEventListener('click', onDocClick);
});
onBeforeUnmount(() => {
    window.removeEventListener('keydown', onKey);
    document.removeEventListener('click', onDocClick);
});
watch(drawerOpen, (open) => (document.body.style.overflow = open ? 'hidden' : ''));
</script>

<template>
    <div class="min-h-screen bg-ink-950">
        <!-- Desktop sidebar -->
        <aside class="fixed inset-y-0 left-0 z-40 hidden w-72 flex-col border-r border-white/[0.06] bg-ink-900/80 backdrop-blur-xl lg:flex">
            <div class="flex h-16 shrink-0 items-center gap-3 border-b border-white/[0.06] px-6">
                <a :href="links.home"><img :src="site.logo" :alt="site.name" class="h-10 w-auto" /></a>
            </div>
            <div class="px-4 pt-4">
                <label class="relative block">
                    <i class="ri-search-line pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-sm text-zinc-500"></i>
                    <input v-model="navFilter" type="search" placeholder="Find a page" class="w-full rounded-lg border border-white/[0.06] bg-white/[0.03] py-2 pr-3 pl-9 text-sm text-zinc-200 placeholder:text-zinc-500 focus:border-brand-500/50 focus:outline-none" />
                </label>
            </div>
            <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-4 [scrollbar-width:thin]">
                <div v-for="group in filteredNav" :key="group.label">
                    <p class="px-3 pb-1.5 text-[11px] font-semibold tracking-wider text-zinc-500 uppercase">{{ group.label }}</p>
                    <a
                        v-for="item in group.items"
                        :key="item.href"
                        :href="item.href"
                        class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition"
                        :class="item.active ? 'bg-brand-500/10 font-semibold text-brand-300' : 'text-zinc-400 hover:bg-white/[0.04] hover:text-zinc-100'"
                    >
                        <i :class="item.icon" class="text-lg" />
                        {{ item.label }}
                    </a>
                </div>
            </nav>
            <div class="border-t border-white/[0.06] p-4">
                <a :href="links.logout" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-zinc-400 transition hover:bg-down/10 hover:text-down">
                    <i class="ri-logout-box-r-line text-lg"></i> Log out
                </a>
            </div>
        </aside>

        <div class="lg:pl-72">
            <!-- Top bar -->
            <header class="sticky top-0 z-30 border-b border-white/[0.06] bg-ink-950/85 backdrop-blur-xl">
                <div class="flex h-14 items-center gap-3 px-4 sm:px-6 lg:h-16">
                    <button class="grid h-10 w-10 place-items-center rounded-xl text-xl text-zinc-300 hover:bg-white/[0.05] lg:hidden" aria-label="Open menu" @click="drawerOpen = true">
                        <i class="ri-menu-2-line"></i>
                    </button>
                    <a :href="links.home" class="lg:hidden"><img :src="site.mark" :alt="site.name" class="h-9 w-9 rounded-xl" /></a>

                    <div class="flex-1"></div>

                    <button class="hidden items-center gap-2 rounded-lg border border-white/[0.06] bg-white/[0.03] px-3 py-2 text-xs font-medium text-zinc-300 transition hover:border-brand-500/40 hover:text-white sm:flex" @click="copy(shell.referralLink, 'Referral link')">
                        <i class="ri-share-forward-line text-brand-400"></i> Invite friends
                    </button>
                    <a v-if="links.help" :href="links.help" class="grid h-10 w-10 place-items-center rounded-xl text-xl text-zinc-400 transition hover:bg-white/[0.05] hover:text-white" aria-label="Help center">
                        <i class="ri-question-line"></i>
                    </a>

                    <div class="relative" data-profile-menu>
                        <button class="flex items-center gap-2.5 rounded-xl p-1 pr-2 transition hover:bg-white/[0.05]" :aria-expanded="profileOpen" @click="profileOpen = !profileOpen">
                            <Avatar :src="user.avatar" :name="user.fullname || user.username" size="h-8 w-8 text-xs" />
                            <span class="hidden text-left leading-tight sm:block">
                                <span class="block max-w-[10rem] truncate text-sm font-semibold text-zinc-100">{{ user.fullname || user.username }}</span>
                                <span class="block text-[11px] text-zinc-500">UID {{ user.uid || '—' }}</span>
                            </span>
                            <i class="ri-arrow-down-s-line hidden text-zinc-500 sm:block"></i>
                        </button>
                        <transition enter-active-class="transition duration-150 ease-out" enter-from-class="opacity-0 scale-95" leave-active-class="transition duration-100 ease-in" leave-to-class="opacity-0 scale-95">
                            <div v-if="profileOpen" class="absolute right-0 mt-2 w-72 origin-top-right rounded-2xl border border-white/[0.08] bg-ink-850 p-2 shadow-2xl shadow-black/60">
                                <div class="flex items-center gap-3 rounded-xl p-3">
                                    <Avatar :src="user.avatar" :name="user.fullname || user.username" size="h-11 w-11 text-sm" />
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-white">{{ user.fullname || user.username }}</p>
                                        <p class="truncate text-xs text-zinc-500">{{ user.email }}</p>
                                    </div>
                                </div>
                                <div class="mx-3 mb-2 flex items-center justify-between gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px] font-semibold" :class="kycBadge.cls"><i :class="kycBadge.icon"></i>{{ kycBadge.label }}</span>
                                    <button v-if="user.uid" class="inline-flex items-center gap-1 text-[11px] text-zinc-400 hover:text-white" @click="copy(user.uid, 'UID')">UID {{ user.uid }} <i class="ri-file-copy-line"></i></button>
                                </div>
                                <div class="h-px bg-white/[0.06]"></div>
                                <a :href="links.profile" class="mt-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-zinc-300 hover:bg-white/[0.05]"><i class="ri-user-3-line text-lg"></i> Profile</a>
                                <a :href="links.security" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-zinc-300 hover:bg-white/[0.05]"><i class="ri-shield-keyhole-line text-lg"></i> Security</a>
                                <a :href="links.password" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-zinc-300 hover:bg-white/[0.05]"><i class="ri-lock-password-line text-lg"></i> Change password</a>
                                <div class="my-1 h-px bg-white/[0.06]"></div>
                                <a :href="links.logout" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-down hover:bg-down/10"><i class="ri-logout-box-r-line text-lg"></i> Log out</a>
                            </div>
                        </transition>
                    </div>
                </div>
            </header>

            <main class="mx-auto w-full px-4 pt-5 pb-28 sm:px-6 lg:pt-8 lg:pb-12" :class="wide ? 'max-w-[1680px] lg:px-5' : 'max-w-7xl lg:px-8'">
                <slot />
            </main>
        </div>

        <!-- Mobile drawer -->
        <transition enter-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-active-class="transition-opacity duration-200" leave-to-class="opacity-0">
            <div v-if="drawerOpen" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm lg:hidden" @click="drawerOpen = false"></div>
        </transition>
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="-translate-x-full" leave-active-class="transition duration-200 ease-in" leave-to-class="-translate-x-full">
            <aside v-if="drawerOpen" class="fixed inset-y-0 left-0 z-50 flex w-[86%] max-w-sm flex-col bg-ink-900 shadow-2xl lg:hidden" role="dialog" aria-label="Menu">
                <div class="flex items-center gap-3 border-b border-white/[0.06] p-4">
                    <Avatar :src="user.avatar" :name="user.fullname || user.username" size="h-11 w-11 text-sm" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-semibold text-white">{{ user.fullname || user.username }}</p>
                        <span class="mt-0.5 inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold" :class="kycBadge.cls"><i :class="kycBadge.icon"></i>{{ kycBadge.label }}</span>
                    </div>
                    <button class="grid h-9 w-9 place-items-center rounded-lg text-xl text-zinc-400 hover:bg-white/[0.05]" aria-label="Close menu" @click="drawerOpen = false"><i class="ri-close-line"></i></button>
                </div>
                <div class="grid grid-cols-2 gap-2 p-4 pb-2">
                    <button class="flex items-center justify-center gap-2 rounded-xl bg-white/[0.04] py-2.5 text-xs font-medium text-zinc-200" @click="copy(shell.referralLink, 'Referral link')"><i class="ri-share-forward-line text-brand-400"></i> Invite</button>
                    <button class="flex items-center justify-center gap-2 rounded-xl bg-white/[0.04] py-2.5 text-xs font-medium text-zinc-200" :disabled="!user.uid" @click="copy(user.uid, 'UID')"><i class="ri-file-copy-line text-brand-400"></i> Copy UID</button>
                </div>
                <div class="px-4 pt-2">
                    <label class="relative block">
                        <i class="ri-search-line pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-sm text-zinc-500"></i>
                        <input v-model="navFilter" type="search" placeholder="Find a page" class="w-full rounded-lg border border-white/[0.06] bg-white/[0.03] py-2.5 pr-3 pl-9 text-sm text-zinc-200 placeholder:text-zinc-500 focus:outline-none" />
                    </label>
                </div>
                <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-4">
                    <div v-for="group in filteredNav" :key="group.label">
                        <p class="px-3 pb-1.5 text-[11px] font-semibold tracking-wider text-zinc-500 uppercase">{{ group.label }}</p>
                        <a v-for="item in group.items" :key="item.href" :href="item.href" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm" :class="item.active ? 'bg-brand-500/10 font-semibold text-brand-300' : 'text-zinc-300'">
                            <i :class="item.icon" class="text-lg text-zinc-500" /> {{ item.label }}
                        </a>
                    </div>
                </nav>
                <div class="border-t border-white/[0.06] p-4 pb-[max(1rem,env(safe-area-inset-bottom))]">
                    <a :href="links.logout" class="flex items-center justify-center gap-2 rounded-xl bg-down/10 py-3 text-sm font-semibold text-down"><i class="ri-logout-box-r-line"></i> Log out</a>
                </div>
            </aside>
        </transition>

        <!-- Mobile bottom tab bar -->
        <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-white/[0.06] bg-ink-900/95 pb-[env(safe-area-inset-bottom)] backdrop-blur-xl lg:hidden" aria-label="Primary">
            <div class="mx-auto grid max-w-lg grid-cols-5">
                <a v-for="t in tabs" :key="t.label" :href="t.href" class="relative flex flex-col items-center justify-center gap-0.5 py-2 text-[11px] font-medium" :class="isActive(t.href) ? 'text-brand-300' : 'text-zinc-500'">
                    <template v-if="t.primary">
                        <span class="-mt-6 grid h-12 w-12 place-items-center rounded-2xl bg-brand-500 text-2xl text-ink-950 shadow-lg shadow-brand-500/40 ring-4 ring-ink-950"><i :class="t.icon"></i></span>
                        <span class="text-zinc-300">{{ t.label }}</span>
                    </template>
                    <template v-else>
                        <i :class="isActive(t.href) ? t.activeIcon : t.icon" class="text-[22px] leading-none"></i>
                        {{ t.label }}
                        <span v-if="isActive(t.href)" class="absolute top-0 h-0.5 w-8 rounded-full bg-brand-400"></span>
                    </template>
                </a>
            </div>
        </nav>

        <NoticeModal :image="shell.notice" />
        <Toasts />
    </div>
</template>
