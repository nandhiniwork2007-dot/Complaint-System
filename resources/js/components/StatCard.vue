<template>
    <component :is="to ? RouterLink : 'div'" :to="to || undefined"
        class="group flex items-start justify-between gap-3 rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100"
        :class="to ? 'transition hover:border-slate-300 hover:shadow-md' : ''">
        <div class="min-w-0">
            <p class="text-sm font-medium text-slate-500">{{ label }}</p>
            <div v-if="loading" class="mt-3 h-8 w-16 rounded bg-slate-100 motion-safe:animate-pulse" aria-hidden="true"></div>
            <p v-else class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 tabular-nums">{{ display }}</p>
            <p v-if="hint" class="mt-1 text-xs text-slate-500">{{ hint }}</p>
        </div>
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg" :class="tones.box">
            <AppIcon :name="icon" class="h-5 w-5" />
        </span>
    </component>
</template>

<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import AppIcon from './AppIcon.vue';

const props = defineProps({
    label: { type: String, required: true },
    value: { type: [String, Number], default: null },
    icon: { type: String, default: 'layers' },
    tone: { type: String, default: 'blue' },
    loading: { type: Boolean, default: false },
    hint: { type: String, default: '' },
    to: { type: [String, Object], default: null },
});

const TONES = {
    blue: { box: 'bg-blue-50 text-blue-600' },
    amber: { box: 'bg-amber-50 text-amber-600' },
    green: { box: 'bg-green-50 text-green-600' },
    red: { box: 'bg-red-50 text-red-600' },
    slate: { box: 'bg-slate-100 text-slate-600' },
};

const tones = computed(() => TONES[props.tone] || TONES.blue);
const display = computed(() => (props.value === null || props.value === undefined ? '—' : Number(props.value).toLocaleString()));
</script>
