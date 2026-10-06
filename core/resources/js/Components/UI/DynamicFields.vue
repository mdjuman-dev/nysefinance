<script setup>
// Renders admin-built form fields (FormProcessor form_data) into a v-model object
// keyed by each field's `key`. Checkbox values are arrays, file values are File objects.
defineProps({ fields: { type: Array, default: () => [] }, errors: { type: Object, default: () => ({}) } });
const model = defineModel({ type: Object, required: true });

function toggle(key, option) {
    const list = Array.isArray(model.value[key]) ? [...model.value[key]] : [];
    const i = list.indexOf(option);
    i >= 0 ? list.splice(i, 1) : list.push(option);
    model.value[key] = list;
}
</script>

<template>
    <div class="space-y-4">
        <div v-for="f in fields" :key="f.key">
            <span class="mb-1.5 block text-sm font-medium text-zinc-300">{{ f.label }} <span v-if="f.required" class="text-down">*</span></span>

            <textarea v-if="f.type === 'textarea'" v-model="model[f.key]" rows="3" class="field" :required="f.required"></textarea>

            <select v-else-if="f.type === 'select'" v-model="model[f.key]" class="field" :required="f.required">
                <option value="" disabled>Select one</option>
                <option v-for="o in f.options" :key="o" :value="o">{{ o }}</option>
            </select>

            <div v-else-if="f.type === 'checkbox'" class="flex flex-wrap gap-2">
                <button v-for="o in f.options" :key="o" type="button" class="rounded-full border px-3 py-1.5 text-sm transition" :class="(model[f.key] || []).includes(o) ? 'border-brand-500 bg-brand-500/10 text-white' : 'border-white/10 text-zinc-400'" @click="toggle(f.key, o)">{{ o }}</button>
            </div>

            <div v-else-if="f.type === 'radio'" class="flex flex-wrap gap-2">
                <label v-for="o in f.options" :key="o" class="cursor-pointer">
                    <input v-model="model[f.key]" type="radio" :value="o" class="peer sr-only" :required="f.required" />
                    <span class="block rounded-full border border-white/10 px-3 py-1.5 text-sm text-zinc-400 transition peer-checked:border-brand-500 peer-checked:bg-brand-500/10 peer-checked:text-white">{{ o }}</span>
                </label>
            </div>

            <input v-else-if="f.type === 'file'" type="file" class="field file:mr-3 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-1 file:text-zinc-200" :required="f.required && !model[f.key]" @change="model[f.key] = $event.target.files[0] || null" />

            <input v-else v-model="model[f.key]" :type="['number', 'email', 'url', 'date', 'datetime-local', 'time'].includes(f.type) ? f.type : 'text'" :step="f.type === 'number' ? 'any' : undefined" class="field" :required="f.required" />

            <span v-if="errors[f.key]" class="mt-1 block text-xs text-down">{{ errors[f.key] }}</span>
            <span v-else-if="f.instruction" class="mt-1 block text-xs text-zinc-500">{{ f.instruction }}</span>
        </div>
    </div>
</template>
