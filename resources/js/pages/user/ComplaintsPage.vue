<template>
    <AppShell section="user" title="My Complaints" subtitle="Search, filter, and review every complaint you've submitted.">
        <template #header>
            <div class="flex flex-col gap-1">
                <h1 class="text-2xl font-bold text-slate-900">My Complaints</h1>
                <p class="text-sm text-slate-500">Track your complaints and their status.</p>
            </div>
            <div class="flex items-center gap-3">
                <RouterLink to="/complaints/new" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Complaint
                    </span>
                </RouterLink>
            </div>
        </template>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-3 md:grid-cols-7">
                <input v-model="filters.search" placeholder="Search..."
                    class="md:col-span-2 rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <select v-model="filters.status"
                    class="rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">All statuses</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="rejected">Rejected</option>
                </select>
                <select v-model="filters.category_id"
                    class="rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">All categories</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}
                    </option>
                </select>
                <input v-model="filters.date_from" type="date"
                    class="rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <input v-model="filters.date_to" type="date"
                    class="rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <div class="flex gap-3">
                    <button
                        class="flex-1 rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100"
                        @click="loadComplaints">Apply</button>
                    <button class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors duration-200"
                        @click="resetFilters">Reset</button>
                </div>
            </div>

            <div v-if="complaints.length" class="mt-6 grid gap-3">
                <RouterLink v-for="complaint in complaints" :key="complaint.id" :to="`/complaints/${complaint.id}`"
                    class="rounded-lg border border-slate-200 px-4 py-4 hover:border-indigo-300 hover:bg-indigo-50 transition-all duration-200">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-semibold text-slate-900">{{ complaint.title }}</p>
                            <p class="text-sm text-slate-500">{{ complaint.reference }} • {{ complaint.category?.name }}
                            </p>
                        </div>
                        <StatusBadge :status="complaint.status" />
                    </div>
                </RouterLink>
            </div>
            <p v-else class="mt-6 rounded-lg bg-slate-50 px-4 py-6 text-sm text-slate-500 text-center">No complaints match the current filters.</p>
        </div>
    </AppShell>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import axios from 'axios';
import { RouterLink } from 'vue-router';
import AppShell from '../../layouts/AppShell.vue';
import StatusBadge from '../../components/StatusBadge.vue';
import { useToastStore } from '../../stores/toasts';
import { extractMessage, normalizeItems } from '../../utils/http';

const toast = useToastStore();
const complaints = ref([]);
const categories = ref([]);
const initialFilters = { search: '', status: '', category_id: '', date_from: '', date_to: '' };
const filters = reactive({ ...initialFilters });

async function loadCategories() {
    const { data } = await axios.get('/api/categories');
    categories.value = normalizeItems(data);
}

async function loadComplaints() {
    try {
        const { data } = await axios.get('/api/complaints', { params: filters });
        complaints.value = normalizeItems(data);
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load complaints.'));
    }
}

function resetFilters() {
    Object.assign(filters, initialFilters);
    loadComplaints();
}

onMounted(async () => {
    try {
        await Promise.all([loadCategories(), loadComplaints()]);
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load complaint filters.'));
    }
});
</script>
