<template>
<<<<<<< HEAD
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
=======
    <AppShell section="admin" title="Complaint management" subtitle="Search, filter, and review all complaints across the system.">
        <template #actions>
            <div class="flex items-center gap-2">
                <Input v-model="filters.search" label="Search" placeholder="Title, reference, or user" class="w-64 shrink-0" />
                <Button variant="outline" @click="loadComplaints">Apply</Button>
                <Button variant="ghost" @click="resetFilters">Reset</Button>
            </div>
        </template>

        <Card padded bordered class="mb-6">
            <div class="grid gap-3 sm:grid-cols-4 lg:grid-cols-6">
                <Select v-model="filters.status" label="Status" class="col-span-full sm:col-span-2">
                    <option value="">All statuses</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="rejected">Rejected</option>
                </Select>
                <Select v-model="filters.category_id" label="Category" class="col-span-full sm:col-span-2">
                    <option value="">All categories</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </Select>
                <Input v-model="filters.assigned_to" label="Assigned user ID" type="number" class="col-span-full sm:col-span-1 lg:col-span-1" />
                <div class="flex items-end gap-2 col-span-full sm:col-span-1 lg:col-span-1">
                    <Button variant="outline" @click="loadComplaints" class="w-full">Apply</Button>
                    <Button variant="ghost" @click="resetFilters" class="w-full">Reset</Button>
                </div>
            </div>
        </Card>

        <div v-if="complaints.data?.length" class="mb-6">
            <Table :columns="tableColumns" :rows="complaints.data" :rowKey="row => row.id">
                <template #header-reference>Ref.</template>
                <template #header-title>Title</template>
                <template #header-user>User</template>
                <template #header-category>Category</template>
                <template #header-status>Status</template>
                <template #header-submitted_at>Submitted</template>

                <template #cell-reference="{ row }">
                    <span class="text-xs font-mono font-semibold text-text-muted">#{{ row.reference }}</span>
                </template>
                <template #cell-title="{ row }">
                    <RouterLink :to="`/admin/complaints/${row.id}`" class="text-sm font-medium text-text-main hover:text-primary transition-colors">
                        {{ row.title }}
                    </RouterLink>
                </template>
                <template #cell-user="{ row }">
                    <div class="flex items-center gap-2">
                        <Avatar :name="row.user?.name" size="xs" class="shrink-0" />
                        <span class="text-xs text-text-main">{{ row.user?.name }}</span>
                    </div>
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
                    <RouterLink :to="`/admin/complaints/${row.id}`" class="text-sm font-medium text-primary hover:text-primary-dark transition-colors">Review →</RouterLink>
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
            <EmptyState title="No complaints found" description="Try adjusting your filters or search terms." iconColor="text-text-muted" />
        </div>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    </AppShell>
</template>

<script setup>
<<<<<<< HEAD
import { useRoute } from 'vue-router';
import AppShell from '../../layouts/AppShell.vue';
import ComplaintsTable from '../../components/ComplaintsTable.vue';
import { STATUS_META } from '../../utils/status';

// Dashboard stat cards link here with ?status=…; only accept known statuses.
const route = useRoute();
const status = typeof route.query.status === 'string' && STATUS_META[route.query.status] ? route.query.status : '';
const initialFilters = status ? { status } : {};
=======
import { onMounted, reactive, ref, computed } from 'vue';
import axios from 'axios';
import { RouterLink } from 'vue-router';
import { useToastStore } from '../../stores/toasts';
import { extractMessage, normalizeItems } from '../../utils/http';
import AppShell from '../../layouts/AppShell.vue';
import Card from '../../components/Card.vue';
import Select from '../../components/Select.vue';
import Input from '../../components/Input.vue';
import Button from '../../components/Button.vue';
import Table from '../../components/Table.vue';
import Pagination from '../../components/Pagination.vue';
import EmptyState from '../../components/EmptyState.vue';
import Avatar from '../../components/Avatar.vue';
import Badge from '../../components/Badge.vue';

const toast = useToastStore();
const categories = ref([]);
const complaints = ref({ data: [], current_page: 1, per_page: 12, total: 0 });
const initialFilters = { search: '', status: '', category_id: '', assigned_to: '', page: 1 };
const filters = reactive({ ...initialFilters });

const tableColumns = ['reference', 'title', 'user', 'category', 'status', 'submitted_at'];

async function loadCategories() {
    try {
        const { data } = await axios.get('/api/admin/categories');
        categories.value = normalizeItems(data);
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load categories.'));
    }
}

async function loadComplaints() {
    try {
        const { data } = await axios.get('/api/admin/complaints', { params: { ...filters, page: filters.page } });
        complaints.value = {
            data: normalizeItems(data),
            current_page: data.current_page ?? 1,
            per_page: data.per_page ?? 12,
            total: data.total ?? 0,
        };
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load complaints.'));
    }
}

function resetFilters() {
    Object.assign(filters, initialFilters);
    loadComplaints();
}

function changePage(page) {
    filters.page = page;
    loadComplaints();
}

function formatDate(iso) {
    if (!iso) return '';
    return new Date(iso).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
}

onMounted(async () => {
    try {
        await Promise.all([loadCategories(), loadComplaints()]);
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load complaint management data.'));
    }
});
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
</script>
