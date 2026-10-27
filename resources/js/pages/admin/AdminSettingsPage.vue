<template>
    <AppShell section="admin" title="Settings" subtitle="Control system-wide notification and template preferences.">
        <template #header>
            <div class="flex flex-col gap-1">
                <h1 class="text-2xl font-bold text-slate-900">System Settings</h1>
                <p class="text-sm text-slate-500">Control system-wide notification and template preferences.</p>
            </div>
        </template>
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100">
            <div class="grid gap-4">
                <label v-for="setting in settings" :key="setting.id" class="grid gap-2">
                    <span class="text-sm font-medium text-slate-700">{{ setting.label }}</span>
                    <input v-if="setting.type !== 'textarea'" v-model="settingValues[setting.id]"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    <textarea v-else v-model="settingValues[setting.id]" rows="4"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                </label>
            </div>
            <button class="mt-6 w-full rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100"
                @click="save">Save Settings</button>
        </div>
    </AppShell>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { useToastStore } from '../../stores/toasts';
import AppShell from '../../layouts/AppShell.vue';
import { extractMessage, normalizeItems } from '../../utils/http';

const toast = useToastStore();
const settings = ref([]);
const settingValues = ref({});

async function load() {
    try {
        const { data } = await axios.get('/api/admin/settings');
        const items = normalizeItems(data);

        settings.value = items;
        settingValues.value = Object.fromEntries(items.map((setting) => [setting.id, setting.value?.body ?? setting.value ?? '']));
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to load settings.'));
    }
}

async function save() {
    try {
        const payload = { settings: {} };

        for (const [id, value] of Object.entries(settingValues.value)) {
            payload.settings[id] = { value };
        }

        const { data } = await axios.put('/api/admin/settings', payload);
        toast.success(data.message || 'Settings saved successfully.');
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to save settings.'));
    }
}

onMounted(load);
</script>
