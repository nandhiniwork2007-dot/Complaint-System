<template>
    <AppShell section="admin" title="Dashboard" subtitle="Admin Panel">
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 mb-8">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100"><h3 class="text-lg font-semibold text-slate-700">Total Complaints</h3><p class="text-3xl font-bold mt-2">{{ stats.total }}</p></div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100"><h3 class="text-lg font-semibold text-slate-700">Pending</h3><p class="text-3xl font-bold mt-2">{{ stats.pending }}</p></div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100"><h3 class="text-lg font-semibold text-slate-700">In Progress</h3><p class="text-3xl font-bold mt-2">{{ stats.in_progress }}</p></div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100"><h3 class="text-lg font-semibold text-slate-700">Resolved</h3><p class="text-3xl font-bold mt-2">{{ stats.resolved }}</p></div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100"><h3 class="text-lg font-semibold text-slate-700">Rejected</h3><p class="text-3xl font-bold mt-2">{{ stats.rejected }}</p></div>
        </div>

        <div class="mt-8 grid gap-6 xl:grid-cols-[1.2fr_1fr]">
            <section class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <div class="flex items-center justify-between gap-4">
                    <h3 class="text-xl font-bold text-slate-800">Complaint Trend</h3>
                    <select v-model="selectedPeriod"
                        class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 outline-none focus:border-teal-500">
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                    </select>
                </div>
                <div class="mt-6 grid max-h-[28rem] gap-4 overflow-y-auto pr-1">
                    <div v-for="item in trend" :key="item.key" class="grid gap-2">
                        <div class="flex items-center justify-between text-sm text-slate-600"><span>{{ item.label
                                }}</span><span>{{ item.total }}</span></div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 rounded-full bg-teal-500"
                                :style="{ width: `${Math.max((item.total / maxTrend) * 100, item.total ? 6 : 0)}%` }">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="text-xl font-bold text-slate-800">Recent Complaints</h3>
                <div class="mt-4 grid gap-3">
                    <RouterLink v-for="complaint in complaints" :key="complaint.id" :to="`/admin/complaints/${complaint.id}`" class="block p-6 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-colors">
                        <div>
                            <p class="text-lg font-semibold text-slate-800">{{ complaint.title }}</p>
                            <p class="text-sm text-slate-600 mt-1">Submitted by: {{ complaint.user?.name }}</p>
                        </div>
                    </RouterLink>
                </div>
            </section>
        </div>
    </AppShell>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import axios from 'axios';
import { RouterLink } from 'vue-router';
import AppShell from '../../layouts/AppShell.vue';
import StatCard from '../../components/StatCard.vue';
import { useToastStore } from '../../stores/toasts';
import { extractMessage } from '../../utils/http';

const toast = useToastStore();
const stats = ref({ total: 0, pending: 0, in_progress: 0, resolved: 0, rejected: 0 });
const trend = ref([]);
const complaints = ref([]);
const selectedPeriod = ref('month');
const maxTrend = computed(() => Math.max(...trend.value.map((item) => Number(item.total)), 1));

async function loadDashboard() {
    try {
        const { data } = await axios.get('/api/admin/dashboard', {
            params: { period: selectedPeriod.value },
        });

        stats.value = data.stats;
        trend.value = data.trend;
        complaints.value = data.recent_complaints;
        selectedPeriod.value = data.selected_period || selectedPeriod.value;
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load dashboard.'));
    }
}

watch(selectedPeriod, loadDashboard);

onMounted(loadDashboard);
</script>
