<template>
    <AppShell :section="layoutSection" title="Profile" subtitle="Manage your personal details and password.">
        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100">
                <h3 class="text-xl font-semibold">Personal details</h3>
                <form class="mt-6 grid gap-4" @submit.prevent="updateProfile">
                    <label class="grid gap-2">
                        <span class="text-sm font-medium text-slate-700">Name</span>
                        <input v-model="profile.name"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Name" />
                        <small class="text-xs text-red-500">{{ profileError('name') }}</small>
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium text-slate-700">Email</span>
                        <input v-model="profile.email"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Email" />
                        <small class="text-xs text-red-500">{{ profileError('email') }}</small>
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium text-slate-700">Phone</span>
                        <input v-model="profile.phone"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Phone" />
                        <small class="text-xs text-red-500">{{ profileError('phone') }}</small>
                    </label>
                    <button
                        class="w-full rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100"
                        :disabled="savingProfile">
                        {{ savingProfile ? 'Saving...' : 'Save Profile' }}
                    </button>
                </form>
            </section>
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100">
                <h3 class="text-xl font-semibold">Change password</h3>
                <form class="mt-6 grid gap-4" @submit.prevent="updatePassword">
                    <label class="grid gap-2">
                        <span class="text-sm font-medium text-slate-700">Current password</span>
                        <input v-model="password.current_password" type="password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Current password" />
                        <small class="text-xs text-red-500">{{ passwordError('current_password') }}</small>
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium text-slate-700">New password</span>
                        <input v-model="password.password" type="password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="New password" />
                        <small class="text-xs text-red-500">{{ passwordError('password') }}</small>
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium text-slate-700">Confirm password</span>
                        <input v-model="password.password_confirmation" type="password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Confirm password" />
                    </label>
                    <button
                        class="w-full rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100"
                        :disabled="savingPassword">
                        {{ savingPassword ? 'Updating...' : 'Update Password' }}
                    </button>
                </form>
            </section>
        </div>
    </AppShell>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import axios from 'axios';
import AppShell from '../../layouts/AppShell.vue';
import { useAuthStore } from '../../stores/auth';
import { useToastStore } from '../../stores/toasts';
import { extractMessage } from '../../utils/http';

const auth = useAuthStore();
const toast = useToastStore();
const profile = reactive({ name: '', email: '', phone: '' });
const password = reactive({ current_password: '', password: '', password_confirmation: '' });
const profileErrors = ref({});
const passwordErrors = ref({});
const savingProfile = ref(false);
const savingPassword = ref(false);
const layoutSection = computed(() => (auth.isStaff ? 'admin' : 'user'));

onMounted(() => {
    profile.name = auth.user?.name || '';
    profile.email = auth.user?.email || '';
    profile.phone = auth.user?.phone || '';
});

function profileError(key) {
    return profileErrors.value[key]?.[0] ?? '';
}

function passwordError(key) {
    return passwordErrors.value[key]?.[0] ?? '';
}

async function updateProfile() {
    if (savingProfile.value) return;
    profileErrors.value = {};
    savingProfile.value = true;

    try {
        const { data } = await axios.patch('/api/profile', profile);
        auth.user = data.data;
        toast.success(data.message || 'Profile updated successfully.');
    } catch (error) {
        profileErrors.value = error.response?.data?.errors ?? {};
        toast.error(extractMessage(error, 'Unable to update profile.'));
    } finally {
        savingProfile.value = false;
    }
}

async function updatePassword() {
    if (savingPassword.value) return;
    passwordErrors.value = {};
    savingPassword.value = true;

    try {
        const { data } = await axios.put('/api/profile/password', password);
        password.current_password = '';
        password.password = '';
        password.password_confirmation = '';
        toast.success(data.message || 'Password updated successfully.');
    } catch (error) {
        passwordErrors.value = error.response?.data?.errors ?? {};
        toast.error(extractMessage(error, 'Unable to update password.'));
    } finally {
        savingPassword.value = false;
    }
}
</script>
