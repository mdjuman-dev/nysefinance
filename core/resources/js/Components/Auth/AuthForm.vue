<script setup>
// Native POST form: auth routes redirect to Blade or Vue pages alike, so a full
// page load after submit is the reliable path. Validation errors come back via session.
import { ref } from 'vue';

const props = defineProps({ action: { type: String, required: true }, confirm: Function });
const busy = ref(false);
const token = document.querySelector('meta[name="csrf-token"]')?.content;

function onSubmit(e) {
    if (busy.value || (props.confirm && props.confirm() === false)) {
        e.preventDefault();
        return;
    }
    busy.value = true;
}
</script>

<template>
    <form :action="action" method="POST" class="space-y-4" @submit="onSubmit">
        <input type="hidden" name="_token" :value="token" />
        <slot :busy="busy" />
    </form>
</template>
