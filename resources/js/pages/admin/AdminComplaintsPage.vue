<template>
    <AppShell section="admin" title="Complaint management" subtitle="Search, filter and review all complaints across the system.">
    <template #header>
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-bold text-slate-900">Complaint Management</h1>
            <p class="text-sm text-slate-500">Search, filter and review all complaints across the system.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative w-64">
                <input
                    type="text"
                    placeholder="Search complaints..."
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                />
                <AppIcon name="search" class="absolute right-3 top-2.5 h-4 w-4 text-slate-400" />
            </div>
            <button class="rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                <AppIcon name="filter" class="h-5 w-5" />
            </button>
            <button class="rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                <AppIcon name="download" class="h-5 w-5" />
            </button>
        </div>
    </template>
        <ComplaintsTable heading="All complaints" description="Open a complaint to update its status, assignment and notes."
            :initial-filters="initialFilters" />
    </AppShell>
</template>

<script setup>
import { useRoute } from 'vue-router';
import AppShell from '../../layouts/AppShell.vue';
import ComplaintsTable from '../../components/ComplaintsTable.vue';
import { STATUS_META } from '../../utils/status';

// Dashboard stat cards link here with ?status=…; only accept known statuses.
const route = useRoute();
const status = typeof route.query.status === 'string' && STATUS_META[route.query.status] ? route.query.status : '';
const initialFilters = status ? { status } : {};
</script>
