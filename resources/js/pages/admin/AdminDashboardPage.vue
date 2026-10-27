<template>
    <AppShell section="admin" title="Admin Dashboard" subtitle="Monitor complaint activity and keep the campus support queue moving.">
    <template #header>
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-bold text-slate-900">Welcome back, {{ user?.name || 'Admin' }}</h1>
            <p class="text-sm text-slate-500">Today is {{ currentDate }}</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <input
                    type="text"
                    placeholder="Search complaints..."
                    class="w-64 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                />
                <AppIcon name="search" class="absolute right-3 top-2.5 h-4 w-4 text-slate-400" />
            </div>
            <button class="rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                <AppIcon name="bell" class="h-5 w-5" />
            </button>
            <div class="relative">
                <button class="flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 hover:bg-slate-200">
                    <span class="h-8 w-8 rounded-full bg-indigo-500 text-sm font-medium text-white">{{ user?.name?.charAt(0) || 'A' }}</span>
                    <span class="text-sm font-medium text-slate-700">{{ user?.name || 'Admin' }}</span>
                    <AppIcon name="chevron-down" class="h-4 w-4 text-slate-500" />
                </button>
            </div>
        </div>
    </template>
        <div v-if="loadError" class="mb-6 flex items-center justify-between gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            <span class="flex items-center gap-2"><AppIcon name="alert" class="h-4 w-4" /> {{ loadError }}</span>
            <button type="button" class="rounded-lg border border-red-300 px-3 py-1 font-medium hover:bg-red-100" @click="loadDashboard">Retry</button>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            <StatCard
                v-for="card in cards"
                :key="card.key"
                :label="card.label"
                :icon="card.icon"
                :tone="card.tone"
                :value="stats ? stats[card.key] : null"
                :loading="loading && !stats"
                :to="card.to"
                class="transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100"
            ></StatCard>
        </div>

        <!-- Analytics -->
        <div class="mt-6 grid gap-6 xl:grid-cols-3">
            <ChartPanel class="xl:col-span-2" title="Complaint trend" :subtitle="periodSubtitle" :loading="loading && !stats"
                :error="stats ? '' : loadError" :empty="trendTotal === 0" empty-text="No complaints were submitted in this period."
                @retry="loadDashboard">
                <template #actions>
                    <label class="sr-only" for="trend-period">Trend period</label>
                    <select id="trend-period" v-model="period" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm">
                        <option value="day">Today (hourly)</option>
                        <option value="week">Last 7 days</option>
                        <option value="month">This month</option>
                        <option value="year">This year</option>
                    </select>
                </template>
                <TrendChart :data="trend" :aria-label="`Complaints submitted, ${periodSubtitle}`" />
            </ChartPanel>

            <ChartPanel title="Status breakdown" subtitle="All complaints" :loading="loading && !stats"
                :error="stats ? '' : loadError" :empty="!stats || stats.total === 0" empty-text="No complaints yet." @retry="loadDashboard">
                <DonutChart :segments="statusSegments" center-label="Complaints" aria-label="Complaints by status" />
            </ChartPanel>
        </div>

        <div class="mt-6 grid gap-6 xl:grid-cols-3">
            <ChartPanel class="xl:col-span-2" title="Complaints by category" subtitle="All complaints" :loading="loading && !stats"
                :error="stats ? '' : loadError" :empty="!categoryBreakdown.length" empty-text="No categorised complaints yet." @retry="loadDashboard">
                <BarList :items="categoryBreakdown" />
            </ChartPanel>

            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-100 px-5 py-4">
                    <h3 class="text-base font-semibold text-slate-900">Quick actions</h3>
                    <p class="mt-0.5 text-sm text-slate-500">Jump to common admin tasks.</p>
                </header>
                <ul class="divide-y divide-slate-100 p-2">
                    <li v-for="action in quickActions" :key="action.to">
                        <RouterLink :to="action.to" class="flex items-center gap-3 rounded-lg px-3 py-3 hover:bg-slate-50">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                <AppIcon :name="action.icon" class="h-5 w-5" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-medium text-slate-900">{{ action.label }}</span>
                                <span class="block truncate text-xs text-slate-500">{{ action.hint }}</span>
                            </span>
                            <AppIcon name="chevron-right" class="h-4 w-4 text-slate-400" />
                        </RouterLink>
                    </li>
                </ul>
            </section>
        </div>

        <!-- Recent complaints -->
        <div class="mt-6">
            <ComplaintsTable heading="Recent complaints" description="Newest submissions first. Search, filter or sort to find a complaint."
                compact>
                <template #actions>
                    <RouterLink to="/admin/complaints" class="text-sm font-medium text-blue-600 hover:text-blue-700">View all</RouterLink>
                </template>
            </ComplaintsTable>
        </div>
    </AppShell>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import axios from 'axios';
import { RouterLink } from 'vue-router';
import AppShell from '../../layouts/AppShell.vue';
import AppIcon from '../../components/AppIcon.vue';
import StatCard from '../../components/StatCard.vue';
import ChartPanel from '../../components/ChartPanel.vue';
import ComplaintsTable from '../../components/ComplaintsTable.vue';
import TrendChart from '../../components/charts/TrendChart.vue';
import DonutChart from '../../components/charts/DonutChart.vue';
import BarList from '../../components/charts/BarList.vue';
import { extractMessage } from '../../utils/http';
import { STATUS_META } from '../../utils/status';

const stats = ref(null);
const trend = ref([]);
const categoryBreakdown = ref([]);
const period = ref('month');
const loading = ref(true);
const loadError = ref('');

const cards = [
    { key: 'total', label: 'Total Complaints', icon: 'layers', tone: 'blue', to: '/admin/complaints' },
    { key: 'pending', label: 'Pending', icon: 'clock', tone: 'amber', to: { path: '/admin/complaints', query: { status: 'pending' } } },
    { key: 'in_progress', label: 'In Progress', icon: 'loader', tone: 'blue', to: { path: '/admin/complaints', query: { status: 'in_progress' } } },
    { key: 'resolved', label: 'Resolved', icon: 'check-circle', tone: 'green', to: { path: '/admin/complaints', query: { status: 'resolved' } } },
    { key: 'rejected', label: 'Rejected', icon: 'x-circle', tone: 'red', to: { path: '/admin/complaints', query: { status: 'rejected' } } },
];

const quickActions = [
    { to: '/admin/complaints', label: 'Manage complaints', hint: 'Review, assign and update statuses', icon: 'file-text' },
    { to: '/admin/categories', label: 'Manage categories', hint: 'Edit the complaint taxonomy', icon: 'tag' },
    { to: '/admin/users', label: 'Manage users', hint: 'Roles, access and password resets', icon: 'users' },
];

const PERIOD_LABELS = { day: 'Today, by hour', week: 'Last 7 days', month: 'This month, by day', year: 'This year, by month' };
const periodSubtitle = computed(() => PERIOD_LABELS[period.value]);
const trendTotal = computed(() => trend.value.reduce((sum, item) => sum + Number(item.total || 0), 0));

const statusSegments = computed(() => Object.entries(STATUS_META).map(([key, meta]) => ({
    label: meta.label,
    color: meta.color,
    value: Number(stats.value?.[key] || 0),
})));

let requestId = 0;
async function loadDashboard() {
    const current = ++requestId;
    loading.value = true;
    loadError.value = '';

    try {
        const { data } = await axios.get('/api/admin/dashboard', { params: { period: period.value } });
        if (current !== requestId) return;
        stats.value = data.stats;
        trend.value = data.trend || [];
        categoryBreakdown.value = data.category_breakdown || [];
    } catch (error) {
        if (current !== requestId) return;
        loadError.value = extractMessage(error, 'Unable to load dashboard data.');
    } finally {
        if (current === requestId) loading.value = false;
    }
}

watch(period, loadDashboard);
onMounted(loadDashboard);
</script>
