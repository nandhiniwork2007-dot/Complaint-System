<template>
<<<<<<< HEAD
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
=======
    <GuestLayout
        title="Create account"
        subtitle="Start submitting and tracking complaints online."
        variant="Create your account"
        :steps="registrationSteps"
        :step="1"
    >
        <template #content>
            <form class="mt-10 grid gap-4" @submit.prevent="submit">
                <Input
                    v-model="form.name"
                    label="Full name"
                    name="name"
                    placeholder="Alex Rivera"
                    :error="error('name')"
                    required
                    autocomplete="name"
                />
                <Input
                    v-model="form.email"
                    label="Email address"
                    type="email"
                    name="email"
                    placeholder="you@company.com"
                    :error="error('email')"
                    required
                    autocomplete="email"
                />
                <Input
                    v-model="form.phone"
                    label="Phone number"
                    name="phone"
                    placeholder="+1 (555) 000-0000"
                    autocomplete="tel"
                />
                <Input
                    v-model="form.password"
                    label="Password"
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    :error="error('password')"
                    required
                    autocomplete="new-password"
                />
                <Input
                    v-model="form.password_confirmation"
                    label="Confirm password"
                    type="password"
                    name="password_confirmation"
                    placeholder="••••••••"
                    :error="error('password_confirmation')"
                    required
                    autocomplete="new-password"
                />
                <Button type="submit" variant="primary" :loading="loading" class="w-full">
                    {{ loading ? 'Creating account...' : 'Create account' }}
                </Button>
            </form>

            <div class="mt-10 border-t border-surface-border pt-6">
                <p class="text-sm text-text-secondary text-center">
                    Already registered?{{ ' ' }}
                    <RouterLink class="font-medium text-primary hover:text-primary-dark" to="/login">Sign in</RouterLink>
                </p>
            </div>
        </template>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    </GuestLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter, RouterLink } from 'vue-router';
import GuestLayout from '../../layouts/GuestLayout.vue';
<<<<<<< HEAD
=======
import Input from '../../components/Input.vue';
import Button from '../../components/Button.vue';
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
import { useAuthStore } from '../../stores/auth';
import { useToastStore } from '../../stores/toasts';

const auth = useAuthStore();
const router = useRouter();
const toast = useToastStore();
const loading = ref(false);
const errors = ref({});
const form = reactive({ name: '', email: '', phone: '', password: '', password_confirmation: '' });

<<<<<<< HEAD
=======
const registrationSteps = ['Create account', 'Verify email', 'Get started'];

>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
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
<<<<<<< HEAD
            toast.success('Registration completed. Check your inbox for the verification link.');
=======
            toast.success('Account created. Check your inbox to verify your email.');
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
        }

        router.push('/verify-email');
    } catch (error) {
        errors.value = error.response?.data?.errors ?? {};
    } finally {
        loading.value = false;
    }
}
<<<<<<< HEAD
</script>
=======
</script>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
