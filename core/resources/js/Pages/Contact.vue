<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PageHero from '@/Components/PageHero.vue';

const props = defineProps({
    content: { type: Object, default: () => ({}) },
    user: { type: Object, default: null },
});

const form = useForm({
    name: props.user?.name ?? '',
    email: props.user?.email ?? '',
    subject: '',
    message: '',
});

function submit() {
    form.post(window.location.pathname, {
        preserveScroll: true,
        onSuccess: () => form.reset('subject', 'message'),
    });
}
</script>

<template>
    <Head title="Contact us" />

    <PageHero
        eyebrow="Contact"
        :title="content.heading || 'We would love to hear from you'"
        :subtitle="content.subheading"
    />

    <section class="container-x">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
            <div class="space-y-4 lg:col-span-2">
                <a v-if="content.email" :href="`mailto:${content.email}`" class="card card-hover flex items-center gap-4 p-5">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-brand-500/10 text-2xl text-brand-400 ring-1 ring-brand-500/20">
                        <i class="ri-mail-line"></i>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-xs text-zinc-500">Email us</span>
                        <span class="block truncate font-semibold text-white">{{ content.email }}</span>
                    </span>
                </a>
                <a v-if="content.mobile" :href="`tel:${content.mobile}`" class="card card-hover flex items-center gap-4 p-5">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-brand-500/10 text-2xl text-brand-400 ring-1 ring-brand-500/20">
                        <i class="ri-phone-line"></i>
                    </span>
                    <span>
                        <span class="block text-xs text-zinc-500">Call us</span>
                        <span class="block font-semibold text-white">{{ content.mobile }}</span>
                    </span>
                </a>
                <div class="card p-6">
                    <h3 class="flex items-center gap-2 font-semibold text-white"><i class="ri-time-line text-brand-400"></i> Support hours</h3>
                    <p class="mt-2 text-sm leading-6 text-zinc-400">
                        Our team is available seven days a week. Every message becomes a support ticket, so you can follow the conversation
                        from your account.
                    </p>
                </div>
            </div>

            <form class="card space-y-5 p-6 sm:p-8 lg:col-span-3" @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-zinc-300">Name</label>
                        <input id="name" v-model="form.name" type="text" class="field" placeholder="Your name" :readonly="!!user" required />
                        <p v-if="form.errors.name" class="mt-1.5 text-xs text-down">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-zinc-300">Email</label>
                        <input id="email" v-model="form.email" type="email" class="field" placeholder="you@example.com" :readonly="!!user" required />
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-down">{{ form.errors.email }}</p>
                    </div>
                </div>
                <div>
                    <label for="subject" class="mb-2 block text-sm font-medium text-zinc-300">Subject</label>
                    <input id="subject" v-model="form.subject" type="text" class="field" placeholder="How can we help?" required />
                    <p v-if="form.errors.subject" class="mt-1.5 text-xs text-down">{{ form.errors.subject }}</p>
                </div>
                <div>
                    <label for="message" class="mb-2 block text-sm font-medium text-zinc-300">Message</label>
                    <textarea id="message" v-model="form.message" rows="6" class="field resize-y" placeholder="Write your message" required></textarea>
                    <p v-if="form.errors.message" class="mt-1.5 text-xs text-down">{{ form.errors.message }}</p>
                </div>
                <button type="submit" class="btn-primary w-full py-3" :disabled="form.processing">
                    <i :class="form.processing ? 'ri-loader-4-line animate-spin' : 'ri-send-plane-2-line'"></i>
                    {{ form.processing ? 'Sending…' : 'Send message' }}
                </button>
            </form>
        </div>
    </section>
</template>
