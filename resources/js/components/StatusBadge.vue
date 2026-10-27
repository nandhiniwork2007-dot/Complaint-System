<template>
    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset"
        :class="meta.badge + ' transition-colors duration-200 hover:ring-opacity-80'">
        <span class="h-1.5 w-1.5 rounded-full" :class="meta.dot" aria-hidden="true"></span>
        {{ label }}
    </span>
</template>

<script setup>
import { computed } from 'vue';
import { STATUS_META, statusLabel } from '../utils/status';

const props = defineProps({
    status: { type: String, required: true },
});

const meta = computed(() => STATUS_META[props.status] || { badge: 'bg-slate-100 text-slate-700 ring-slate-500/20', dot: 'bg-slate-400' });
const label = computed(() => statusLabel(props.status));
</script>
