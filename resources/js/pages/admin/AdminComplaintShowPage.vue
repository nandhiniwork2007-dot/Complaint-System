<template>
    <AppShell section="admin" :title="complaint?.title || 'Complaint Review'" subtitle="Update status, assign the case, and capture internal notes.">
    <template #header>
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-bold text-slate-900">Complaint Review</h1>
            <p class="text-sm text-slate-500">Update status, assign the case, and capture internal notes.</p>
        </div>
        <div class="flex items-center gap-3">
            <RouterLink to="/admin/complaints" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Back to Complaints
            </RouterLink>
        </div>
    </template>
        <div v-if="complaint" class="grid gap-6 xl:grid-cols-[1.55fr_1fr]">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">{{ complaint.reference }} • {{ complaint.user?.name }}</p>
                        <h3 class="mt-2 text-2xl font-semibold">{{ complaint.title }}</h3>
                    </div>
                    <StatusBadge :status="complaint.status" />
                </div>
                <p class="mt-4 whitespace-pre-line text-slate-700">{{ complaint.description }}</p>

                <div class="mt-8 grid gap-3">
                    <a v-for="attachment in complaint.attachments" :key="attachment.id" :href="attachment.download_url"
                        class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 hover:border-indigo-300 hover:bg-indigo-50 transition-colors duration-200">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <span class="text-sm font-medium text-slate-700">{{ attachment.name }}</span>
                    </a>
                </div>

                <div class="mt-8 grid gap-3">
                    <div v-for="message in complaint.messages" :key="message.id" class="rounded-lg px-4 py-4"
                        :class="message.is_internal ? 'bg-amber-50 border-l-4 border-amber-400' : 'bg-slate-50 border-l-4 border-slate-300'">
                        <p class="text-sm font-semibold">{{ message.user?.name }} <span
                                class="text-xs uppercase text-slate-500">{{ message.is_internal ? 'Internal' : 'Public'
                                }}</span></p>
                        <p class="mt-2 text-sm text-slate-700">{{ message.message }}</p>
                    </div>
                </div>
            </section>

            <section class="grid gap-6">
                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <h4 class="text-lg font-semibold">Update complaint</h4>
                    <form class="mt-5 grid gap-4" @submit.prevent="save">
                        <select v-model="form.status"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <select v-model="form.assigned_to"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option value="">Unassigned</option>
                            <option v-for="member in staff" :key="member.id" :value="member.id">{{ member.name }}
                            </option>
                        </select>
                        <textarea v-model="form.public_message" rows="4"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Visible response to user"></textarea>
                        <textarea v-model="form.internal_note" rows="4"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Internal note"></textarea>
                        <button
                            class="w-full rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100"
                            :disabled="loading">
                            Save Changes
                        </button>
                    </form>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100">
                    <h4 class="text-lg font-semibold">Audit log</h4>
                    <div class="mt-4 grid gap-3 text-sm text-slate-600">
                        <div v-for="log in complaint.audit_logs" :key="log.id"
                            class="rounded-lg bg-slate-50 px-4 py-4 border-l-4 border-indigo-300">
                            <p class="font-semibold text-slate-900">{{ log.action }}</p>
                            <p class="mt-1">{{ log.description }}</p>
                            <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-400">{{ log.user }} • {{
                                log.created_at }}</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppShell>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
import AppShell from '../../layouts/AppShell.vue';
import StatusBadge from '../../components/StatusBadge.vue';
import { useToastStore } from '../../stores/toasts';
import { extractMessage } from '../../utils/http';

const route = useRoute();
const toast = useToastStore();
const complaint = ref(null);
const staff = ref([]);
const form = reactive({ status: 'pending', assigned_to: '', public_message: '', internal_note: '' });

async function load() {
    try {
        const { data } = await axios.get(`/api/admin/complaints/${route.params.id}`);
        complaint.value = data.data;
        staff.value = data.staff;
        form.status = complaint.value.status;
        form.assigned_to = complaint.value.assignee?.id || '';
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load complaint details.'));
    }
}

async function save() {
    try {
        const { data } = await axios.patch(`/api/admin/complaints/${route.params.id}`, form);
        complaint.value = data.data;
        form.public_message = '';
        form.internal_note = '';
        toast.success(data.message || 'Complaint updated successfully.');

        if (data.warning) {
            const message = data.mail_error ? `${data.warning} ${data.mail_error}` : data.warning;
            toast.error(message);
        }
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to update complaint.'));
    }
}

onMounted(load);
</script>