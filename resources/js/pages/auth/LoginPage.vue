<template>
    <GuestLayout title="Welcome back" subtitle="Sign in to track and manage your complaints.">
        <form class="mt-8 grid gap-4" @submit.prevent="submit">
            <label class="grid gap-2">
                <span class="text-sm font-medium text-slate-700">Email</span>
                <input v-model="form.email" type="email" placeholder="Enter your email"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <small class="text-xs text-red-500">{{ error('email') }}</small>
            </label>
            <label class="grid gap-2">
                <span class="text-sm font-medium text-slate-700">Password</span>
                <input v-model="form.password" type="password" placeholder="Enter your password"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <small class="text-xs text-red-500">{{ error('password') }}</small>
            </label>
            <button class="w-full rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100" :disabled="loading">
                {{ loading ? 'Signing in...' : 'Login' }}
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Need an account?
            <RouterLink to="/register" class="font-semibold text-indigo-600 hover:text-indigo-700">Register here</RouterLink>
        </p>
        <div class="mt-4 text-center">
            <RouterLink to="/forgot-password" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Forgot your password?</RouterLink>
        </div>
    </GuestLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter, RouterLink } from 'vue-router';
import GuestLayout from '../../layouts/GuestLayout.vue';
import { useAuthStore } from '../../stores/auth';

const auth = useAuthStore();
const router = useRouter();
const loading = ref(false);
const errors = ref({});
const form = reactive({ email: '', password: '' });

function error(key) {
    return errors.value[key]?.[0] ?? '';
}

async function submit() {
    loading.value = true;
    errors.value = {};

    try {
        await auth.login(form);
        router.push(auth.user?.is_staff ? '/admin/dashboard' : '/dashboard');
    } catch (error) {
        errors.value = error.response?.data?.errors ?? {};
    } finally {
        loading.value = false;
    }
}
</script>
