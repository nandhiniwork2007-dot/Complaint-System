<template>
    <form class="grid gap-4" @submit.prevent="submitForm">
        <div class="grid gap-4 md:grid-cols-2">
            <label class="grid gap-2">
                <span class="text-sm font-medium text-slate-700">Title</span>
                <input v-model="form.title" placeholder="Enter a descriptive title"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    type="text" />
                <small class="text-xs text-red-500">{{ errors.title?.[0] }}</small>
            </label>
            <label class="grid gap-2">
                <span class="text-sm font-medium text-slate-700">Category</span>
                <select v-model="form.complaint_category_id"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">Select a category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}
                    </option>
                </select>
                <small class="text-xs text-red-500">{{ errors.complaint_category_id?.[0] }}</small>
            </label>
        </div>

        <label class="grid gap-2">
            <span class="text-sm font-medium text-slate-700">Description</span>
            <textarea v-model="form.description" rows="8" placeholder="Describe your complaint in detail..."
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
            <small class="text-xs text-red-500">{{ errors.description?.[0] }}</small>
        </label>

        <label class="grid gap-2">
            <span class="text-sm font-medium text-slate-700">Attachments</span>
            <div class="relative">
                <input multiple accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full rounded-lg border border-dashed border-slate-300 bg-white px-4 py-3 text-sm" type="file"
                    @change="captureFiles" />
            </div>
            <small class="text-xs text-slate-500">Up to 5 files. Images and PDF only.</small>
            <small class="text-xs text-red-500">{{ errors['attachments.0']?.[0] || errors.attachments?.[0] }}</small>
        </label>

        <button :disabled="submitting"
            class="w-full rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100 disabled:opacity-60"
            :class="submitting ? 'cursor-not-allowed' : 'cursor-pointer'">
            {{ submitting ? 'Submitting...' : submitLabel }}
        </button>
    </form>
</template>

<script setup>
import { reactive, ref, watch } from 'vue';

const props = defineProps({
    complaint: { type: Object, default: null },
    categories: { type: Array, default: () => [] },
    errors: { type: Object, default: () => ({}) },
    submitting: { type: Boolean, default: false },
    submitLabel: { type: String, default: 'Submit complaint' },
});

const emit = defineEmits(['submit']);

const form = reactive({
    title: '',
    complaint_category_id: '',
    description: '',
    attachments: [],
});

watch(() => props.complaint,
    (complaint) => {
        form.title = complaint?.title ?? '';
        form.complaint_category_id = complaint?.category?.id ?? '';
        form.description = complaint?.description ?? '';
        form.attachments = [];
    },
    { immediate: true },
);

function captureFiles(event) {
    form.attachments = Array.from(event.target.files || []);
}

function submitForm() {
    emit('submit', { ...form });
}
</script>
