<template>
    <AppShell section="user" title="Dashboard" subtitle="A quick snapshot of your complaint activity.">
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 mb-8">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-700">Total Complaints</h3>
                <p class="text-3xl font-bold mt-2">{{ stats.total }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-700">Pending</h3>
                <p class="text-3xl font-bold mt-2">{{ stats.pending }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-700">In Progress</h3>
                <p class="text-3xl font-bold mt-2">{{ stats.in_progress }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-700">Resolved</h3>
                <p class="text-3xl font-bold mt-2">{{ stats.resolved }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-700">Rejected</h3>
                <p class="text-3xl font-bold mt-2">{{ stats.rejected }}</p>
            </div>
        </div>

        <section class="mt-8 bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-800">Recent Complaints</h3>
                <RouterLink to="/complaints" class="text-blue-600 font-semibold hover:text-blue-700 transition-colors">View All</RouterLink>
            </div>
            <div v-if="complaints.length" class="grid gap-3">
                <RouterLink v-for="complaint in complaints" :key="complaint.id" :to="`/complaints/${complaint.id}`" class="block p-6 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-colors flex items-center justify-between">
                    <div>
                        <p class="text-lg font-semibold text-slate-800">{{ complaint.title }}</p>
                        <p class="text-sm text-slate-600 mt-1">#{{ complaint.reference }}</p>
                    </div>
                    <div><StatusBadge :status="complaint.status" /></div>
                </RouterLink>
            </div>
            <div v-else class="p-6 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                <p class="text-sm text-slate-500">No complaints found yet.</p>
                <SubmitComplaintButton class="mt-4" />
            </div>
        </section>
    </AppShell>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { RouterLink } from 'vue-router';
import AppShell from '../../layouts/AppShell.vue';
import StatCard from '../../components/StatCard.vue';
import StatusBadge from '../../components/StatusBadge.vue';
import SubmitComplaintButton from '../../components/SubmitComplaintButton.vue';
import { useToastStore } from '../../stores/toasts';
import { extractMessage, normalizeItems } from '../../utils/http';

const toast = useToastStore();
const stats = ref({ total: 0, pending: 0, in_progress: 0, resolved: 0, rejected: 0 });
const complaints = ref([]);

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/dashboard');
        stats.value = data.stats;
        complaints.value = normalizeItems(data.recent_complaints);
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load dashboard.'));
    }
});
</script>