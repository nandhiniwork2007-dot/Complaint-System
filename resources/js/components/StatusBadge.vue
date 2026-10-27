<template>
<<<<<<< HEAD
    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset"
        :class="meta.badge + ' transition-colors duration-200 hover:ring-opacity-80'">
        <span class="h-1.5 w-1.5 rounded-full" :class="meta.dot" aria-hidden="true"></span>
=======
    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em]" :class="classes">
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
        {{ label }}
    </span>
</template>

<script setup>
import { computed } from 'vue';
<<<<<<< HEAD
import { STATUS_META, statusLabel } from '../utils/status';
=======
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9

const props = defineProps({
    status: { type: String, required: true },
});

<<<<<<< HEAD
const meta = computed(() => STATUS_META[props.status] || { badge: 'bg-slate-100 text-slate-700 ring-slate-500/20', dot: 'bg-slate-400' });
const label = computed(() => statusLabel(props.status));
=======
const classes = computed(() => ({
    pending: 'bg-amber-100 text-amber-800',
    in_progress: 'bg-sky-100 text-sky-800',
    resolved: 'bg-emerald-100 text-emerald-800',
    rejected: 'bg-rose-100 text-rose-800',
}[props.status] || 'bg-slate-100 text-slate-800'));

const label = computed(() => props.status.replace('_', ' '));
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
</script>
