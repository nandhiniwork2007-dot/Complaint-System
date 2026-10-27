<template>
<<<<<<< HEAD
    <AppShell section="admin" title="Users" subtitle="Activate, deactivate, re-role, and trigger password resets.">
        <template #header>
            <div class="flex flex-col gap-1">
                <h1 class="text-2xl font-bold text-slate-900">User Management</h1>
                <p class="text-sm text-slate-500">Activate, deactivate, re-role, and trigger password resets.</p>
            </div>
        </template>
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100">
            <div class="grid gap-3 md:grid-cols-5">
                <input v-model="filters.search" placeholder="Search name, email, phone"
                    class="md:col-span-2 rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <select v-model="filters.role"
                    class="rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
=======
    <AppShell section="admin" title="User management" subtitle="Manage accounts, roles, and status across the portal.">
        <template #actions>
            <Input v-model="filters.search" label="Search" placeholder="Name, email, or phone" class="w-64 shrink-0" />
        </template>

        <Card padded bordered class="mb-6">
            <div class="grid gap-3 sm:grid-cols-4 lg:grid-cols-5">
                <Select v-model="filters.role" label="Role" class="col-span-full">
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
                    <option value="">All roles</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="support_agent">Support Agent</option>
                    <option value="user">User</option>
<<<<<<< HEAD
                </select>
                <select v-model="filters.is_active"
                    class="rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">All statuses</option>
                    <option value="true">Active</option>
                    <option value="false">Inactive</option>
                </select>
                <div class="flex gap-3">
                    <button
                        class="flex-1 rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100"
                        @click="load">Apply</button>
                    <button class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors duration-200"
                        @click="resetFilters">Reset</button>
                </div>
            </div>

            <div v-if="users.length" class="mt-6 grid gap-3">
                <div v-for="user in users" :key="user.id" class="rounded-lg border border-slate-200 px-4 py-4 hover:border-indigo-200 hover:bg-indigo-50/30 transition-all duration-200">
                    <div class="grid gap-4 lg:grid-cols-[1.3fr_auto_auto_auto] lg:items-center">
                        <div>
                            <p class="font-semibold text-slate-900">{{ user.name }}</p>
                            <p class="text-sm text-slate-500">{{ user.email }}</p>
                            <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-400">
                                {{ user.phone || 'No phone' }} • {{ user.email_verified_at ? 'Verified' : 'Unverified' }}
                            </p>
                        </div>
                        <select v-model="user.role"
                            class="rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="support_agent">Support Agent</option>
                            <option value="user">User</option>
                        </select>
                        <select v-model="user.is_active"
                            class="rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option :value="true">Active</option>
                            <option :value="false">Inactive</option>
                        </select>
                        <div class="flex gap-2">
                            <button
                                class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700 transition-all duration-200"
                                @click="save(user)">Save</button>
                            <button
                                class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors duration-200"
                                @click="resetPassword(user)">Reset Password</button>
                        </div>
                    </div>
                </div>
            </div>

            <p v-else class="mt-6 rounded-lg bg-slate-50 px-4 py-6 text-sm text-slate-500 text-center">No users match the current filters.</p>
=======
                </Select>
                <Select v-model="filters.is_active" label="Status" class="col-span-full">
                    <option value="">All statuses</option>
                    <option value="true">Active</option>
                    <option value="false">Inactive</option>
                </Select>
                <div class="flex items-end gap-2 col-span-full lg:col-span-1">
                    <Button variant="outline" @click="loadUsers" class="w-full">Apply</Button>
                    <Button variant="ghost" @click="resetFilters" class="w-full">Reset</Button>
                </div>
            </div>
        </Card>

        <div v-if="users.data?.length" class="mb-6">
            <Table :columns="tableColumns" :rows="users.data" :rowKey="row => row.id">
                <template #header-name>Name</template>
                <template #header-email>Email</template>
                <template #header-phone>Phone</template>
                <template #header-role>Role</template>
                <template #header-status>Status</template>
                <template #header-verified>Verified</template>
                <template #header-actions>Actions</template>

                <template #cell-name="{ row }">
                    <div class="flex items-center gap-2">
                        <Avatar :name="row.name" size="xs" class="shrink-0" />
                        <span class="text-sm font-medium text-text-main">{{ row.name }}</span>
                    </div>
                </template>
                <template #cell-email="{ row }">
                    <span class="text-sm text-text-main truncate max-w-xs">{{ row.email }}</span>
                </template>
                <template #cell-phone="{ row }">
                    <span class="text-xs text-text-secondary">{{ row.phone || '—' }}</span>
                </template>
                <template #cell-role="{ row }">
                    <select
                        v-model="row.editRole"
                        class="block w-full rounded-lg border border-surface-border bg-surface px-2.5 py-1.5 text-xs text-text-main focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                    >
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="support_agent">Support Agent</option>
                        <option value="user">User</option>
                    </select>
                </template>
                <template #cell-status="{ row }">
                    <Badge :variant="row.is_active ? 'success' : 'default'" class="text-xs">
                        {{ row.is_active ? 'Active' : 'Inactive' }}
                    </Badge>
                </template>
                <template #cell-verified="{ row }">
                    <span class="text-xs">{{ row.email_verified_at ? 'Yes' : 'No' }}</span>
                </template>

                <template #actions="{ row }">
                    <div class="flex items-center gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            :loading="savingIds.has(row.id)"
                            @click="saveUser(row)"
                            class="w-auto"
                            :disabled="savingIds.has(row.id)"
                        >
                            Save
                        </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            :loading="resettingIds.has(row.id)"
                            @click="resetPassword(row)"
                            class="w-auto text-error border-error hover:bg-error/10 hover:text-error"
                            :disabled="resettingIds.has(row.id)"
                        >
                            Reset password
                        </Button>
                    </div>
                </template>
            </Table>

            <Pagination
                :current-page="users.current_page"
                :per-page="users.per_page"
                :total="users.total"
                @update:current-page="changePage"
            />
        </div>

        <div v-else class="py-16 text-center">
            <EmptyState title="No users found" description="Try adjusting your filters or search terms." iconColor="text-text-muted" />
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
        </div>
    </AppShell>
</template>

<script setup>
<<<<<<< HEAD
import { onMounted, reactive, ref } from 'vue';
import axios from 'axios';
import AppShell from '../../layouts/AppShell.vue';
import { useToastStore } from '../../stores/toasts';
import { extractMessage, normalizeItems } from '../../utils/http';

const toast = useToastStore();
const users = ref([]);
const initialFilters = { search: '', role: '', is_active: '' };
const filters = reactive({ ...initialFilters });

async function load() {
    try {
        const { data } = await axios.get('/api/admin/users', { params: filters });
        users.value = normalizeItems(data);
=======
import { onMounted, reactive, ref, computed } from 'vue';
import axios from 'axios';
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
const users = ref({ data: [], current_page: 1, per_page: 12, total: 0 });
const initialFilters = { search: '', role: '', is_active: '', page: 1 };
const filters = reactive({ ...initialFilters });
const savingIds = ref(new Set());
const resettingIds = ref(new Set());

const tableColumns = ['name', 'email', 'phone', 'role', 'status', 'verified', 'actions'];

async function loadUsers() {
    try {
        const { data } = await axios.get('/api/admin/users', { params: { ...filters, page: filters.page } });
        users.value = {
            data: normalizeItems(data).map((u) => ({ ...u, editRole: u.role })),
            current_page: data.current_page ?? 1,
            per_page: data.per_page ?? 12,
            total: data.total ?? 0,
        };
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load users.'));
    }
}

function resetFilters() {
    Object.assign(filters, initialFilters);
<<<<<<< HEAD
    load();
}

async function save(user) {
    try {
        const { data } = await axios.patch(`/api/admin/users/${user.id}`, {
            role: user.role,
            is_active: user.is_active,
        });

        toast.success(data.message || 'User updated successfully.');
        await load();
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to update user.'));
=======
    loadUsers();
}

function changePage(page) {
    filters.page = page;
    loadUsers();
}

async function saveUser(user) {
    savingIds.value.add(user.id);
    try {
        await axios.patch(`/api/admin/users/${user.id}`, {
            role: user.editRole,
            is_active: user.is_active,
        });
        toast.success('User updated successfully.');
        await loadUsers();
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to update user.'));
        await loadUsers();
    } finally {
        savingIds.value.delete(user.id);
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    }
}

async function resetPassword(user) {
<<<<<<< HEAD
=======
    resettingIds.value.add(user.id);
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    try {
        const { data } = await axios.post(`/api/admin/users/${user.id}/reset-password`);
        const message = data.mail_error ? `${data.message} ${data.mail_error}` : data.message;
        toast.success(message || 'Password reset link processed.');
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to send password reset link.'));
<<<<<<< HEAD
    }
}

onMounted(load);
</script>
=======
    } finally {
        resettingIds.value.delete(user.id);
    }
}

onMounted(loadUsers);
</script>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
