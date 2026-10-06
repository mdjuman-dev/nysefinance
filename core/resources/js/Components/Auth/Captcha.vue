<script setup>
// Server-rendered captcha (custom image or Google reCAPTCHA). v-html does not run
// <script> tags, so re-insert them so reCAPTCHA can initialise.
import { onMounted, ref } from 'vue';

defineProps({ html: String });
const box = ref(null);

onMounted(() => {
    box.value?.querySelectorAll('script').forEach((old) => {
        const s = document.createElement('script');
        [...old.attributes].forEach((a) => s.setAttribute(a.name, a.value));
        s.textContent = old.textContent;
        old.replaceWith(s);
    });
});
</script>

<template>
    <div v-if="html" ref="box" class="viser-fields" v-html="html"></div>
</template>
