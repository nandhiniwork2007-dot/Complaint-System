<template>
<<<<<<< HEAD
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
=======
    <AppShell section="user" title="My complaints" subtitle="Search, filter, and review every complaint you've submitted.">
        <template #actions>
            <Button variant="primary" @click="router.push('/complaints/new')">
                <Icon name="PlusIcon" class="h-4 w-4" />
                <span>Submit complaint</span>
            </Button>
        </template>

        <Card padded bordered class="mb-6">
            <div class="grid gap-3 sm:gap-4 sm:grid-cols-4 lg:grid-cols-6">
                <Input
                    v-model="filters.search"
                    label="Search"
                    placeholder="Title or reference"
                    class="col-span-full sm:col-span-2 lg:col-span-3"
                />
                <Select
                    v-model="filters.status"
                    label="Status"
                    placeholder="All statuses"
                >
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
                    <option value="">All statuses</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="rejected">Rejected</option>
<<<<<<< HEAD
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
=======
                </Select>
                <Select
                    v-model="filters.category_id"
                    label="Category"
                    placeholder="All categories"
                >
                    <option value="">All categories</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </Select>
                <div class="flex items-end gap-2 col-span-full sm:col-span-1">
                    <Input v-model="filters.date_from" label="From" type="date" class="w-full" />
                    <Input v-model="filters.date_to" label="To" type="date" class="w-full" />
                </div>
                <div class="flex items-end gap-2 col-span-full sm:col-span-1">
                    <Button variant="outline" @click="loadComplaints" class="w-full">Apply</Button>
                    <Button variant="ghost" @click="resetFilters" class="w-full">Reset</Button>
                </div>
            </div>
        </Card>

        <div v-if="complaints.data?.length" class="mb-6">
            <Table :columns="tableColumns" :rows="complaints.data" :rowKey="row => row.id">
                <template #header-reference>Ref.</template>
                <template #header-title>Title</template>
                <template #header-category>Category</template>
                <template #header-status>Status</template>
                <template #header-submitted_at>Submitted</template>

                <template #cell-reference="{ row }">
                    <span class="text-xs font-semibold text-text-muted">{{ row.reference }}</span>
                </template>
                <template #cell-title="{ row }">
                    <RouterLink :to="`/complaints/${row.id}`" class="text-sm font-medium text-text-main hover:text-primary transition-colors">
                        {{ row.title }}
                    </RouterLink>
                </template>
                <template #cell-category="{ row }">
                    <span class="text-xs text-text-secondary">{{ row.category?.name || '—' }}</span>
                </template>
                <template #cell-status="{ row }">
                    <Badge :variant="row.status">{{ row.status.replace('_', ' ') }}</Badge>
                </template>
                <template #cell-submitted_at="{ row }">
                    <span class="text-xs text-text-secondary">{{ formatDate(row.submitted_at) }}</span>
                </template>

                <template #actions="{ row }">
                    <RouterLink :to="`/complaints/${row.id}`" class="text-sm font-medium text-primary hover:text-primary-dark transition-colors">View →</RouterLink>
                </template>
            </Table>

            <Pagination
                :current-page="complaints.current_page"
                :per-page="complaints.per_page"
                :total="complaints.total"
                @update:current-page="changePage"
            />
        </div>

        <div v-else class="py-16 text-center">
            <EmptyState
                title="No complaints found"
                :description="hasFilters ? 'Try adjusting your filters to find what you are looking for.' : 'You have not submitted any complaints yet.'"
                iconColor="text-text-muted"
            >
                <template v-if="!hasFilters" #action>
                    <Button variant="primary" @click="router.push('/complaints/new')">
                        Submit your first complaint
                    </Button>
                </template>
            </EmptyState>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
        </div>
    </AppShell>
</template>

<script setup>
<<<<<<< HEAD
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
=======
import { onMounted, reactive, ref, watch, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useToastStore } from '../../stores/toasts';
import { extractMessage, normalizeItems } from '../../utils/http';
import AppShell from '../../layouts/AppShell.vue';
import Card from '../../components/Card.vue';
import Input from '../../components/Input.vue';
import Select from '../../components/Select.vue';
import Button from '../../components/Button.vue';
import Icon from '../../components/Icon.vue';
import { RouterLink } from 'vue-router';
import Table from '../../components/Table.vue';
import Pagination from '../../components/Pagination.vue';
import EmptyState from '../../components/EmptyState.vue';
import Badge from '../../components/Badge.vue';

const router = useRouter();
const toast = useToastStore();
const categories = ref([]);
const complaints = ref({ data: [], current_page: 1, per_page: 10, total: 0 });
const initialFilters = { search: '', status: '', category_id: '', date_from: '', date_to: '', page: 1 };
const filters = reactive({ ...initialFilters });

const tableColumns = ['reference', 'title', 'category', 'status', 'submitted_at'];

const hasFilters = computed(() => {
    return Object.values(filters).some((v, i) => i > 4 && v);
});

async function loadCategories() {
    try {
        const { data } = await axios.get('/api/categories');
        categories.value = normalizeItems(data);
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load categories.'));
    }
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
}

async function loadComplaints() {
    try {
<<<<<<< HEAD
        const { data } = await axios.get('/api/complaints', { params: filters });
        complaints.value = normalizeItems(data);
=======
        const { data } = await axios.get('/api/complaints', { params: { ...filters, page: filters.page } });
        complaints.value = {
            data: normalizeItems(data),
            current_page: data.current_page ?? 1,
            per_page: data.per_page ?? 10,
            total: data.total ?? 0,
        };
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load complaints.'));
    }
}

function resetFilters() {
    Object.assign(filters, initialFilters);
    loadComplaints();
}

<<<<<<< HEAD
=======
function changePage(page) {
    filters.page = page;
    loadComplaints();
}

function formatDate(iso) {
    if (!iso) return '';
    return new Date(iso).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
}

>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
onMounted(async () => {
    try {
        await Promise.all([loadCategories(), loadComplaints()]);
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load complaint filters.'));
    }
});
<<<<<<< HEAD
=======

watch(
    () => filters.page,
    () => {
        loadComplaints();
    },
);
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
</script>
