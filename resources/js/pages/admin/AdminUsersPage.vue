<template>
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
                    <option value="">All roles</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="support_agent">Support Agent</option>
                    <option value="user">User</option>
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
        </div>
    </AppShell>
</template>

<script setup>
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
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load users.'));
    }
}

function resetFilters() {
    Object.assign(filters, initialFilters);
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
    }
}

async function resetPassword(user) {
    try {
        const { data } = await axios.post(`/api/admin/users/${user.id}/reset-password`);
        const message = data.mail_error ? `${data.message} ${data.mail_error}` : data.message;
        toast.success(message || 'Password reset link processed.');
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to send password reset link.'));
    }
}

onMounted(load);
</script>