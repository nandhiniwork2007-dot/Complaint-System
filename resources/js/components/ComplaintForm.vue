<template>
<<<<<<< HEAD
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
=======
    <form class="grid gap-5" @submit.prevent="submitForm">
        <div class="grid gap-4 md:grid-cols-2">
            <div class="field">
                <label class="block text-sm font-medium text-text-secondary mb-1.5">Title</label>
                <Input
                    v-model="form.title"
                    type="text"
                    name="title"
                    placeholder="Brief summary of the issue"
                    :error="errors.title"
                    required
                />
            </div>

            <div class="field">
                <label class="block text-sm font-medium text-text-secondary mb-1.5">Category</label>
                <Select
                    v-model="form.complaint_category_id"
                    name="complaint_category_id"
                    placeholder="Select a category"
                    :error="errors.complaint_category_id"
                    required
                >
                    <option value="">Choose a category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </Select>
            </div>
        </div>

        <div class="field">
            <label class="block text-sm font-medium text-text-secondary mb-1.5">Description</label>
            <p class="text-xs text-text-secondary mb-2">Describe what happened, when, and what you expected instead.</p>
            <Textarea
                v-model="form.description"
                name="description"
                rows="8"
                placeholder="e.g. I was charged twice for the same subscription on May 1st."
                :error="errors.description"
                required
            />
        </div>

        <div class="field">
            <label class="block text-sm font-medium text-text-secondary mb-1.5">Attachments</label>
            <p class="text-xs text-text-secondary mb-2">Up to 5 files. Images and PDF only, 5 MB each.</p>
            <FileAttachment
                v-model="form.attachments"
                :error="errors.attachments"
            />
        </div>

        <div class="pt-2">
            <Button
                type="submit"
                variant="primary"
                :disabled="submitting"
                :loading="submitting"
            >
                {{ submitting ? 'Saving...' : submitLabel }}
            </Button>
        </div>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    </form>
</template>

<script setup>
import { reactive, ref, watch } from 'vue';
<<<<<<< HEAD
=======
import Input from './Input.vue';
import Select from './Select.vue';
import Textarea from './Textarea.vue';
import FileAttachment from './FileAttachment.vue';
import Button from './Button.vue';
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9

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

<<<<<<< HEAD
watch(() => props.complaint,
=======
watch(
    () => props.complaint,
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    (complaint) => {
        form.title = complaint?.title ?? '';
        form.complaint_category_id = complaint?.category?.id ?? '';
        form.description = complaint?.description ?? '';
        form.attachments = [];
    },
    { immediate: true },
);

<<<<<<< HEAD
function captureFiles(event) {
    form.attachments = Array.from(event.target.files || []);
}

=======
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
function submitForm() {
    emit('submit', { ...form });
}
</script>
