<template>
<<<<<<< HEAD
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
=======
    <AppShell
        section="admin"
        :title="complaint?.title || 'Complaint review'"
        :subtitle="`Case #${complaint?.reference} · ${complaint?.user?.name}`"
    >
        <div v-if="!complaint" class="py-16 text-center">
            <EmptyState title="Loading complaint…" description="Please wait while we fetch the case details." iconColor="text-text-muted" />
        </div>

        <div v-else class="grid gap-6 lg:grid-cols-[1.35fr_1fr]">
            <!-- Case details -->
            <section class="grid gap-6">
                <Card padded bordered>
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
                        <div>
                            <p class="text-xs font-semibold text-text-muted uppercase tracking-wider">Case reference</p>
                            <p class="text-sm font-mono font-semibold text-text-main">#{{ complaint.reference }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <Badge :variant="complaint.status" class="text-xs font-bold">
                                {{ complaint.status.replace('_', ' ') }}
                            </Badge>
                            <Badge :variant="priorityKey(complaint.priority)" class="text-xs">
                                {{ complaint.priority.charAt(0).toUpperCase() + complaint.priority.slice(1) }} priority
                            </Badge>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-text-main mb-2">{{ complaint.title }}</h2>
                    <p class="whitespace-pre-line text-text-secondary leading-relaxed mb-6">{{ complaint.description }}</p>

                    <div class="grid gap-3 sm:grid-cols-3 border-t border-surface-border pt-5">
                        <div class="flex flex-col gap-1">
                            <span class="text-text-muted">Submitted by</span>
                            <div class="flex items-center gap-2">
                                <Avatar :name="complaint.user?.name" size="xs" class="shrink-0" />
                                <span class="text-sm text-text-main">{{ complaint.user?.name }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-text-muted">Category</span>
                            <span class="text-sm text-text-main">{{ complaint.category?.name || 'Uncategorized' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-text-muted">Assigned to</span>
                            <span class="text-sm text-text-main">{{ complaint.assignee?.name || 'Unassigned' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-text-muted">Submitted</span>
                            <span class="text-sm text-text-main">{{ formatDateTime(complaint.submitted_at) }}</span>
                        </div>
                        <div v-if="complaint.first_admin_action_at" class="flex flex-col gap-1">
                            <span class="text-text-muted">First response</span>
                            <span class="text-sm text-text-main">{{ formatDateTime(complaint.first_admin_action_at) }}</span>
                        </div>
                        <div v-if="complaint.resolved_at" class="flex flex-col gap-1">
                            <span class="text-text-muted">Resolved</span>
                            <span class="text-sm text-text-success">{{ formatDateTime(complaint.resolved_at) }}</span>
                        </div>
                    </div>

                    <div v-if="complaint.attachments.length" class="mt-6 border-t border-surface-border pt-5">
                        <h4 class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-3">Attachments</h4>
                        <div class="grid gap-2">
                            <a
                                v-for="attachment in complaint.attachments"
                                :key="attachment.id"
                                :href="attachment.download_url"
                                class="flex items-center gap-2 rounded-lg border border-surface-border bg-surface px-3 py-2 text-xs text-text-secondary hover:bg-surface-elevated hover:text-text-main transition-colors"
                                download
                            >
                                <Icon name="PaperclipIcon" class="h-3.5 w-3.5" />
                                <span class="truncate">{{ attachment.name }}</span>
                                <Icon name="DownloadIcon" class="h-3.5 w-3.5 shrink-0 opacity-50" />
                            </a>
                        </div>
                    </div>
                </Card>
            </section>

            <!-- Case management -->
            <section class="grid gap-6">
                <!-- Update form -->
                <Card padded bordered class="sticky top-6">
                    <h3 class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-4">Update case</h3>
                    <form class="grid gap-4" @submit.prevent="saveCase">
                        <div class="field">
                            <label class="block text-xs font-medium text-text-secondary mb-1.5">Status</label>
                            <Select v-model="form.status" name="status">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="rejected">Rejected</option>
                            </Select>
                        </div>

                        <div class="field">
                            <label class="block text-xs font-medium text-text-secondary mb-1.5">Assign to</label>
                            <Select v-model="form.assigned_to" name="assigned_to">
                                <option value="">Unassigned</option>
                                <option v-for="member in staff" :key="member.id" :value="member.id">
                                    {{ member.name }} ({{ member.role }})
                                </option>
                            </Select>
                        </div>

                        <div class="field">
                            <label class="block text-xs font-medium text-text-secondary mb-1.5">Public response</label>
                            <p class="text-xs text-text-secondary mb-1">Visible to the customer.</p>
                            <Textarea v-model="form.public_message" name="public_message" rows="3" placeholder="Type a response to the customer..." />
                        </div>

                        <div class="field">
                            <label class="block text-xs font-medium text-text-secondary mb-1.5">Internal note</label>
                            <p class="text-xs text-text-secondary mb-1">Staff-only note, not shared with the customer.</p>
                            <Textarea v-model="form.internal_note" name="internal_note" rows="3" placeholder="Internal notes for the support team..." />
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <Badge variant="default" class="text-[10px]">{{ saving ? 'Saving...' : 'Ready' }}</Badge>
                            <Button
                                type="submit"
                                variant="primary"
                                :loading="saving"
                                class="w-auto"
                            >
                                Save changes
                            </Button>
                        </div>
                    </form>
                </Card>

                <!-- Messages -->
                <Card padded bordered>
                    <h3 class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-4">
                        Conversation ({{ complaint.messages.length }})
                    </h3>
                    <div v-if="complaint.messages.length" class="space-y-4 max-h-[420px] overflow-y-auto pr-2">
                        <div
                            v-for="message in complaint.messages"
                            :key="message.id"
                            class="p-3 rounded-lg border border-surface-border"
                            :class="{ 'bg-surface-elevated/50': message.is_internal }"
                        >
                            <div class="flex items-center gap-2 mb-1">
                                <Avatar :name="message.user?.name" size="xs" class="shrink-0" />
                                <span class="text-xs font-semibold text-text-main">{{ message.user?.name }}</span>
                                <span class="text-xs text-text-muted">{{ message.is_internal ? 'Internal' : 'Public' }}</span>
                                <span class="text-xs text-text-muted ml-auto">{{ formatDateTime(message.created_at) }}</span>
                            </div>
                            <p class="text-sm text-text-secondary whitespace-pre-line">{{ message.message }}</p>
                        </div>
                    </div>
                    <div v-else class="py-4 text-center text-xs text-text-secondary">No messages yet.</div>
                </Card>

                <!-- Audit log -->
                <Card padded bordered class="max-h-[300px] overflow-hidden flex flex-col">
                    <h3 class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-4">Audit log</h3>
                    <div class="flex-1 overflow-y-auto pr-2 space-y-3 text-xs">
                        <div
                            v-for="log in complaint.audit_logs"
                            :key="log.id"
                            class="p-2 rounded-lg border border-surface-border bg-surface/50"
                        >
                            <p class="text-xs font-semibold text-text-main">{{ log.action }}</p>
                            <p class="text-[10px] text-text-secondary leading-relaxed">{{ log.description }}</p>
                            <p class="text-[10px] text-text-muted mt-1">{{ log.user }} · {{ formatDateTime(log.created_at) }}</p>
                        </div>
                        <div v-if="!complaint.audit_logs?.length" class="text-[10px] text-text-secondary text-center py-2">No activity recorded.</div>
                    </div>
                </Card>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
            </section>
        </div>
    </AppShell>
</template>

<script setup>
<<<<<<< HEAD
import { onMounted, reactive, ref } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
import AppShell from '../../layouts/AppShell.vue';
import StatusBadge from '../../components/StatusBadge.vue';
import { useToastStore } from '../../stores/toasts';
import { extractMessage } from '../../utils/http';
=======
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
import { useToastStore } from '../../stores/toasts';
import { extractMessage } from '../../utils/http';
import AppShell from '../../layouts/AppShell.vue';
import Card from '../../components/Card.vue';
import Select from '../../components/Select.vue';
import Textarea from '../../components/Textarea.vue';
import Button from '../../components/Button.vue';
import Badge from '../../components/Badge.vue';
import Icon from '../../components/Icon.vue';
import Avatar from '../../components/Avatar.vue';
import EmptyState from '../../components/EmptyState.vue';
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9

const route = useRoute();
const toast = useToastStore();
const complaint = ref(null);
const staff = ref([]);
<<<<<<< HEAD
const form = reactive({ status: 'pending', assigned_to: '', public_message: '', internal_note: '' });
=======
const form = ref({ status: 'pending', assigned_to: '', public_message: '', internal_note: '' });
const saving = ref(false);
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9

async function load() {
    try {
        const { data } = await axios.get(`/api/admin/complaints/${route.params.id}`);
        complaint.value = data.data;
<<<<<<< HEAD
        staff.value = data.staff;
        form.status = complaint.value.status;
        form.assigned_to = complaint.value.assignee?.id || '';
=======
        staff.value = data.staff || [];
        form.value.status = complaint.value.status;
        form.value.assigned_to = complaint.value.assignee?.id || '';
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load complaint details.'));
    }
}

<<<<<<< HEAD
async function save() {
    try {
        const { data } = await axios.patch(`/api/admin/complaints/${route.params.id}`, form);
        complaint.value = data.data;
        form.public_message = '';
        form.internal_note = '';
        toast.success(data.message || 'Complaint updated successfully.');

=======
async function saveCase() {
    saving.value = true;
    try {
        const { data } = await axios.patch(`/api/admin/complaints/${route.params.id}`, form.value);
        complaint.value = data.data;
        form.value.public_message = '';
        form.value.internal_note = '';
        toast.success(data.message || 'Complaint updated successfully.');
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
        if (data.warning) {
            const message = data.mail_error ? `${data.warning} ${data.mail_error}` : data.warning;
            toast.error(message);
        }
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to update complaint.'));
<<<<<<< HEAD
    }
}

onMounted(load);
</script>
=======
    } finally {
        saving.value = false;
    }
}

function priorityKey(priority) {
    const map = { low: 'low', normal: 'normal', high: 'high', urgent: 'urgent' };
    return map[priority] || 'normal';
}

function formatDateTime(iso) {
    if (!iso) return '';
    return new Date(iso).toLocaleString(undefined, { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}

onMounted(load);
</script>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
