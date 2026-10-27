<template>
    <AppShell section="user" title="Submit a Complaint" subtitle="Describe the issue clearly so the team can act quickly.">
    <template #header>
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-bold text-slate-900">Submit a Complaint</h1>
            <p class="text-sm text-slate-500">Please provide as much detail as possible to help us resolve your issue.</p>
        </div>
        <div class="flex items-center gap-3">
            <RouterLink to="/dashboard" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Back to Dashboard
            </RouterLink>
        </div>
    </template>
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <ComplaintForm :categories="categories" :errors="errors" :submitting="submitting" @submit="submit" />
        </div>
    </AppShell>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import AppShell from '../../layouts/AppShell.vue';
import ComplaintForm from '../../components/ComplaintForm.vue';
import { useToastStore } from '../../stores/toasts';
import { extractMessage, normalizeItems } from '../../utils/http';

const router = useRouter();
const toast = useToastStore();
const categories = ref([]);
const errors = ref({});
const submitting = ref(false);

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/categories');
        categories.value = normalizeItems(data);
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load categories.'));
    }
});

async function submit(payload) {
    submitting.value = true;
    errors.value = {};

    const formData = new FormData();
    Object.entries(payload).forEach(([key, value]) => {
        if (key === 'attachments') {
            value.forEach((file) => formData.append('attachments[]', file));
        } else {
            formData.append(key, value ?? '');
        }
    });

    try {
        const { data } = await axios.post('/api/complaints', formData);
        toast.success(data.message || 'Complaint submitted successfully.');
        router.push(`/complaints/${data.data.id}`);
    } catch (error) {
        errors.value = error.response?.data?.errors ?? {};
        toast.error(extractMessage(error, 'Unable to submit complaint.'));
    } finally {
        submitting.value = false;
    }
}
</script>
