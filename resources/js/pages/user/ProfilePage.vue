<template>
    <AppShell :section="layoutSection" title="Profile" subtitle="Manage your personal details and password.">
<<<<<<< HEAD
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
=======
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Account info card -->
            <div class="lg:col-span-1">
                <Card padded bordered>
                    <div class="flex flex-col items-center gap-3 mb-6">
                        <Avatar :name="profile.name" :src="user.avatar" size="xl" class="border border-surface-border" />
                        <div>
                            <p class="text-sm font-semibold text-text-main">{{ profile.name }}</p>
                            <p class="text-xs text-text-secondary">{{ profile.email }}</p>
                        </div>
                    </div>
                    <div class="space-y-4 text-sm">
                        <div class="flex items-center gap-3 p-3 rounded-lg border border-surface-border bg-surface/50">
                            <Icon name="PhoneIcon" class="h-4 w-4 text-text-muted shrink-0" />
                            <span class="text-text-secondary">Phone</span>
                            <span class="ml-auto text-text-main">{{ profile.phone || 'Not provided' }}</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-lg border border-surface-border bg-surface/50">
                            <Icon name="MailIcon" class="h-4 w-4 text-text-muted shrink-0" />
                            <span class="text-text-secondary">Email</span>
                            <span class="ml-auto text-text-main truncate">{{ profile.email }}</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-lg border border-surface-border bg-surface/50">
                            <Icon name="ShieldCheckIcon" class="h-4 w-4 shrink-0" />
                            <span class="text-text-secondary">Account type</span>
                            <span class="ml-auto text-text-main capitalize">{{ user.role }}</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-lg border border-surface-border bg-surface/50">
                            <Icon name="CalendarIcon" class="h-4 w-4 text-text-muted shrink-0" />
                            <span class="text-text-secondary">Member since</span>
                            <span class="ml-auto text-text-main">{{ formatDate(user.created_at) }}</span>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Profile form -->
            <div class="lg:col-span-2">
                <Card padded bordered>
                    <h3 class="text-sm font-semibold text-text-main mb-4">Personal details</h3>
                    <form class="grid gap-4" @submit.prevent="updateProfile">
                        <Input
                            v-model="profile.name"
                            label="Full name"
                            name="name"
                            placeholder="Alex Rivera"
                            :error="profileErrors.name"
                            required
                        />
                        <Input
                            v-model="profile.email"
                            label="Email address"
                            type="email"
                            name="email"
                            placeholder="you@company.com"
                            :error="profileErrors.email"
                            required
                            autocomplete="email"
                        />
                        <Input
                            v-model="profile.phone"
                            label="Phone number"
                            name="phone"
                            placeholder="+1 (555) 000-0000"
                            :error="profileErrors.phone"
                            autocomplete="tel"
                        />
                        <Button type="submit" variant="primary" :loading="profileLoading" class="w-full">Save profile</Button>
                    </form>
                </Card>

                <Card padded bordered class="mt-6">
                    <h3 class="text-sm font-semibold text-text-main mb-4">Change password</h3>
                    <form class="grid gap-4" @submit.prevent="updatePassword">
                        <Input
                            v-model="password.current_password"
                            label="Current password"
                            type="password"
                            name="current_password"
                            placeholder="••••••••"
                            :error="passwordErrors.current_password"
                            required
                            autocomplete="current-password"
                        />
                        <Input
                            v-model="password.password"
                            label="New password"
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            :error="passwordErrors.password"
                            required
                            autocomplete="new-password"
                        />
                        <Input
                            v-model="password.password_confirmation"
                            label="Confirm new password"
                            type="password"
                            name="password_confirmation"
                            placeholder="••••••••"
                            :error="passwordErrors.password_confirmation"
                            required
                            autocomplete="new-password"
                        />
                        <Button type="submit" variant="primary" :loading="passwordLoading" class="w-full">Update password</Button>
                    </form>
                </Card>
            </div>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
        </div>
    </AppShell>
</template>

<script setup>
<<<<<<< HEAD
import { computed, onMounted, reactive, ref } from 'vue';
import axios from 'axios';
import AppShell from '../../layouts/AppShell.vue';
=======
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import AppShell from '../../layouts/AppShell.vue';
import Card from '../../components/Card.vue';
import Input from '../../components/Input.vue';
import Button from '../../components/Button.vue';
import Icon from '../../components/Icon.vue';
import Avatar from '../../components/Avatar.vue';
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
import { useAuthStore } from '../../stores/auth';
import { useToastStore } from '../../stores/toasts';
import { extractMessage } from '../../utils/http';

const auth = useAuthStore();
const toast = useToastStore();
<<<<<<< HEAD
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
=======
const layoutSection = computed(() => (auth.isStaff ? 'admin' : 'user'));
const user = computed(() => auth.user);
const profile = ref({ name: '', email: '', phone: '' });
const password = ref({ current_password: '', password: '', password_confirmation: '' });
const profileErrors = ref({});
const passwordErrors = ref({});
const profileLoading = ref(false);
const passwordLoading = ref(false);

onMounted(() => {
    profile.value.name = auth.user?.name || '';
    profile.value.email = auth.user?.email || '';
    profile.value.phone = auth.user?.phone || '';
});

function formatDate(iso) {
    if (!iso) return '';
    return new Date(iso).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
}

async function updateProfile() {
    profileErrors.value = {};
    profileLoading.value = true;
    try {
        const { data } = await axios.patch('/api/profile', profile.value);
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
        auth.user = data.data;
        toast.success(data.message || 'Profile updated successfully.');
    } catch (error) {
        profileErrors.value = error.response?.data?.errors ?? {};
        toast.error(extractMessage(error, 'Unable to update profile.'));
    } finally {
<<<<<<< HEAD
        savingProfile.value = false;
=======
        profileLoading.value = false;
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    }
}

async function updatePassword() {
<<<<<<< HEAD
    if (savingPassword.value) return;
    passwordErrors.value = {};
    savingPassword.value = true;

    try {
        const { data } = await axios.put('/api/profile/password', password);
        password.current_password = '';
        password.password = '';
        password.password_confirmation = '';
        toast.success(data.message || 'Password updated successfully.');
=======
    passwordErrors.value = {};
    passwordLoading.value = true;
    try {
        await axios.put('/api/profile/password', password.value);
        password.value = { current_password: '', password: '', password_confirmation: '' };
        toast.success('Password updated successfully.');
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    } catch (error) {
        passwordErrors.value = error.response?.data?.errors ?? {};
        toast.error(extractMessage(error, 'Unable to update password.'));
    } finally {
<<<<<<< HEAD
        savingPassword.value = false;
=======
        passwordLoading.value = false;
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    }
}
</script>
