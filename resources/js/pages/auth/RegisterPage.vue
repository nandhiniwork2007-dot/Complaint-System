<template>
    <GuestLayout title="Create account" subtitle="Start submitting and tracking complaints online.">
        <form class="mt-8 grid gap-4" @submit.prevent="submit">
            <div class="grid gap-4">
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-slate-700">Full Name</span>
                    <input v-model="form.name" type="text" placeholder="Enter your full name"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    <small class="text-xs text-red-500">{{ error('name') }}</small>
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-slate-700">Email</span>
                    <input v-model="form.email" type="email" placeholder="Enter your email"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    <small class="text-xs text-red-500">{{ error('email') }}</small>
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-slate-700">Phone</span>
                    <input v-model="form.phone" type="tel" placeholder="Enter your phone number"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-slate-700">Password</span>
                    <input v-model="form.password" type="password" placeholder="Create a password"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    <small class="text-xs text-red-500">{{ error('password') }}</small>
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-slate-700">Confirm Password</span>
                    <input v-model="form.password_confirmation" type="password" placeholder="Confirm your password"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                </label>
            </div>
            <button class="mt-6 w-full rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-100" :disabled="loading">
                {{ loading ? 'Creating account...' : 'Register' }}
            </button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-500">
            Already registered?
            <RouterLink to="/login" class="font-semibold text-indigo-600 hover:text-indigo-700">Login here</RouterLink>
        </p>
    </GuestLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter, RouterLink } from 'vue-router';
import GuestLayout from '../../layouts/GuestLayout.vue';
import { useAuthStore } from '../../stores/auth';
import { useToastStore } from '../../stores/toasts';

const auth = useAuthStore();
const router = useRouter();
const toast = useToastStore();
const loading = ref(false);
const errors = ref({});
const form = reactive({ name: '', email: '', phone: '', password: '', password_confirmation: '' });

function error(key) {
    return errors.value[key]?.[0] ?? '';
}

async function submit() {
    loading.value = true;
    errors.value = {};

    try {
        const data = await auth.register(form);

        if (data.mail_warning) {
            const message = data.mail_error ? `${data.mail_warning} ${data.mail_error}` : data.mail_warning;
            toast.error(message);
        } else {
            toast.success('Registration completed. Check your inbox for the verification link.');
        }

        router.push('/verify-email');
    } catch (error) {
        errors.value = error.response?.data?.errors ?? {};
    } finally {
        loading.value = false;
    }
}
</script>