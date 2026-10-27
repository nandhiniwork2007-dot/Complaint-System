<template>
    <AppShell section="admin" title="Categories" subtitle="Manage the complaint taxonomy used by the submission form.">
        <template #header>
            <div class="flex flex-col gap-1">
                <h1 class="text-2xl font-bold text-slate-900">Category Management</h1>
                <p class="text-sm text-slate-500">Manage the complaint taxonomy used by the submission form.</p>
            </div>
        </template>
        <div class="grid gap-6 xl:grid-cols-[1fr_1.2fr]">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100">
                <h3 class="text-xl font-semibold text-slate-900">New Category</h3>
                <form class="mt-6 grid gap-4" @submit.prevent="createCategory">
                    <input v-model="form.name" placeholder="Category name"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    <input v-model="form.type" placeholder="Category type (optional)"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    <textarea v-model="form.description" rows="3" placeholder="Description (optional)"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                    <button class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100">
                        Create Category
                    </button>
                </form>
            </section>
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100">
                <h3 class="text-xl font-semibold text-slate-900">Existing Categories</h3>
                <div class="mt-4 grid gap-3">
                    <div v-for="category in categories" :key="category.id"
                        class="relative rounded-lg border border-slate-200 px-4 py-4 hover:border-indigo-200 hover:bg-indigo-50/30 transition-all duration-200">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold text-slate-900">{{ category.name }}</p>
                                <p class="text-sm text-slate-500">{{ category.description }}</p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Linked complaints: {{ category.complaints_count || 0 }}
                                </p>
                            </div>
                            <div class="relative group">
                                <button :disabled="(category.complaints_count || 0) > 0"
                                    class="text-sm font-semibold px-3 py-1.5 rounded-lg transition-colors duration-200 focus:outline-none"
                                    :class="(category.complaints_count || 0) > 0
                                        ? 'text-slate-400 cursor-not-allowed bg-slate-100'
                                        : 'text-red-600 hover:bg-red-50'"
                                    @click="confirmDelete(category.id)">
                                    Delete
                                </button>
                                <div v-if="(category.complaints_count || 0) > 0" class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 px-3 py-1 bg-slate-800 text-white text-xs rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap z-50">
                                    Cannot delete: active complaints exist
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div v-if="showConfirm" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-800">Delete Category</h3>
                <p class="mt-2 text-sm text-slate-600">Are you sure you want to delete this category? This action cannot be undone.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button @click="cancelDelete" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors duration-200">Cancel</button>
                    <button @click="proceedDelete" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors duration-200">Delete</button>
                </div>
            </div>
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
const categories = ref([]);
const form = reactive({ name: '', type: '', description: '', is_active: true, sort_order: 0 });
const showConfirm = ref(false);
const deleteId = ref(null);

async function load() {
    try {
        const { data } = await axios.get('/api/admin/categories');
        categories.value = normalizeItems(data);
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load categories.'));
    }
}

async function createCategory() {
    try {
        const { data } = await axios.post('/api/admin/categories', form);
        toast.success(data.message || 'Category created successfully.');
        form.name = '';
        form.type = '';
        form.description = '';
        await load();
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to create category.'));
    }
}
function confirmDelete(id) {
    deleteId.value = id;
    showConfirm.value = true;
}

function cancelDelete() {
    showConfirm.value = false;
    deleteId.value = null;
}

async function proceedDelete() {
    if (!deleteId.value) return;

    try {
        const { data } = await axios.delete(`/api/admin/categories/${deleteId.value}`);
        toast.success(data.message || 'Category deleted successfully.');
        await load();
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to delete category.'));
    } finally {
        cancelDelete();
    }
}
async function destroyCategory(id) {
    try {
        const { data } = await axios.delete(`/api/admin/categories/${id}`);
        toast.success(data.message || 'Category deleted successfully.');
        await load();
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to delete category.'));
    }
}

onMounted(load);
</script>
