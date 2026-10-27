<template>
    <AppShell section="admin" title="Categories" subtitle="Manage the complaint taxonomy used by the submission form.">
<<<<<<< HEAD
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
=======
        <div class="grid gap-6 lg:grid-cols-[1fr_1.5fr]">
            <!-- Create category -->
            <Card padded bordered>
                <h3 class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-4">Create category</h3>
                <form class="grid gap-4" @submit.prevent="createCategory">
                    <Input v-model="form.name" label="Name" name="name" placeholder="e.g. Billing" required />
                    <Input v-model="form.type" label="Type" name="type" placeholder="e.g. Payments" required />
                    <Textarea v-model="form.description" label="Description" name="description" rows="3" placeholder="Brief description of this category..." />
                    <div class="flex items-end gap-3 pt-2">
                        <Button variant="outline" type="button" @click="resetForm" class="w-auto">Reset</Button>
                        <Button type="submit" variant="primary" :loading="creating" class="w-auto">Create category</Button>
                    </div>
                </form>
            </Card>

            <!-- Existing categories -->
            <Card padded bordered>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-semibold text-text-muted uppercase tracking-wider">Existing categories</h3>
                    <span class="text-xs text-text-secondary">{{ categories.length }}</span>
                </div>

                <div v-if="categories.length" class="space-y-3">
                    <div
                        v-for="category in categories"
                        :key="category.id"
                        class="flex items-center justify-between gap-3 p-3 rounded-xl border border-surface-border bg-surface hover:bg-surface-elevated/50 transition-colors"
                    >
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-surface-elevated text-text-secondary">
                                <Icon name="TagIcon" class="h-4 w-4" />
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-text-main">{{ category.name }}</p>
                                <p class="text-xs text-text-secondary">{{ category.description || 'No description' }}</p>
                                <p class="text-[10px] text-text-muted mt-0.5 uppercase tracking-wider">{{ category.complaints_count }} complaint{{ category.complaints_count === 1 ? '' : 's' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <Button variant="outline" size="sm" @click="editCategory(category)" class="w-auto">Edit</Button>
                            <button
                                v-if="(category.complaints_count || 0) > 0"
                                class="h-8 w-8 rounded-lg text-text-muted hover:text-error transition-colors"
                                title="Cannot delete - category in use"
                            >
                                <Icon name="Trash2Icon" class="h-4 w-4" />
                            </button>
                            <button
                                v-else
                                @click="confirmDelete(category.id)"
                                class="h-8 w-8 rounded-lg text-text-muted hover:text-error transition-colors"
                                title="Delete category"
                            >
                                <Icon name="Trash2Icon" class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="py-8 text-center">
                    <EmptyState title="No categories yet" description="Create your first complaint category above." iconColor="text-text-muted" />
                </div>
            </Card>
        </div>

        <!-- Delete confirmation -->
        <Modal v-model:open="confirmDeleteOpen" title="Delete category">
            <p class="text-sm text-text-secondary mb-6">Are you sure you want to delete this category? This cannot be undone.</p>
            <template #footer>
                <Button variant="outline" @click="confirmDeleteOpen = false">Cancel</Button>
                <Button variant="danger" @click="proceedDelete" :loading="deleting">Delete</Button>
            </template>
        </Modal>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
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
const categories = ref([]);
const form = reactive({ name: '', type: '', description: '', is_active: true, sort_order: 0 });
const showConfirm = ref(false);
const deleteId = ref(null);

=======
import { onMounted, ref, reactive, computed } from 'vue';
import axios from 'axios';
import { useToastStore } from '../../stores/toasts';
import { extractMessage, normalizeItems } from '../../utils/http';
import AppShell from '../../layouts/AppShell.vue';
import Card from '../../components/Card.vue';
import Input from '../../components/Input.vue';
import Textarea from '../../components/Textarea.vue';
import Button from '../../components/Button.vue';
import Icon from '../../components/Icon.vue';
import Modal from '../../components/Modal.vue';
import EmptyState from '../../components/EmptyState.vue';

const toast = useToastStore();
const categories = ref([]);
const form = ref({ name: '', type: '', description: '' });
const creating = ref(false);
const deleting = ref(false);
const confirmDeleteOpen = ref(false);
const deleteId = ref(null);

const editTarget = ref(null);
const isEditing = computed(() => !!editTarget.value);

>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
async function load() {
    try {
        const { data } = await axios.get('/api/admin/categories');
        categories.value = normalizeItems(data);
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load categories.'));
    }
}

async function createCategory() {
<<<<<<< HEAD
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
=======
    if (isEditing.value) {
        await updateCategory();
        return;
    }
    creating.value = true;
    try {
        await axios.post('/api/admin/categories', form.value);
        toast.success('Category created successfully.');
        resetForm();
        await load();
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to create category.'));
    } finally {
        creating.value = false;
    }
}

async function updateCategory() {
    creating.value = true;
    try {
        await axios.put(`/api/admin/categories/${editTarget.value.id}`, form.value);
        toast.success('Category updated successfully.');
        resetForm();
        await load();
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to update category.'));
    } finally {
        creating.value = false;
    }
}

function editCategory(category) {
    editTarget.value = category;
    form.value = { name: category.name, type: category.type, description: category.description || '' };
}

function resetForm() {
    editTarget.value = null;
    form.value = { name: '', type: '', description: '' };
}

function confirmDelete(id) {
    deleteId.value = id;
    confirmDeleteOpen.value = true;
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
}

async function proceedDelete() {
    if (!deleteId.value) return;
<<<<<<< HEAD

    try {
        const { data } = await axios.delete(`/api/admin/categories/${deleteId.value}`);
        toast.success(data.message || 'Category deleted successfully.');
=======
    deleting.value = true;
    try {
        await axios.delete(`/api/admin/categories/${deleteId.value}`);
        toast.success('Category deleted successfully.');
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
        await load();
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to delete category.'));
    } finally {
<<<<<<< HEAD
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
=======
        confirmDeleteOpen.value = false;
        deleteId.value = null;
        deleting.value = false;
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    }
}

onMounted(load);
</script>
