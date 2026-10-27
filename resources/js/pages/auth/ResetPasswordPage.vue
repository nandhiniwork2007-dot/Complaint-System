<template>
<<<<<<< HEAD
    <GuestLayout title="Set new password" subtitle="Enter the token from email and choose a new password.">
        <form class="mt-8 grid gap-4" @submit.prevent="submit">
            <label class="grid gap-2 text-sm"><span>Email</span><input v-model="form.email" type="email"
                    class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3" /></label>
            <label class="grid gap-2 text-sm"><span>Reset token</span><input v-model="form.token"
                    class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3" /></label>
            <label class="grid gap-2 text-sm"><span>Password</span><input v-model="form.password" type="password"
                    class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3" /></label>
            <label class="grid gap-2 text-sm"><span>Confirm password</span><input v-model="form.password_confirmation"
                    type="password" class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3" /></label>
            <button class="rounded-full bg-teal-400 px-5 py-3 text-sm font-semibold text-slate-950 cursor-pointer">Reset password</button>
        </form>
        <p v-if="message" class="mt-4 text-sm text-emerald-300">{{ message }}</p>
=======
    <GuestLayout title="Set a new password" subtitle="Create a strong new password for your account." variant="Reset password">
        <template #content>
            <form class="mt-10 grid gap-4" @submit.prevent="submit">
                <Input
                    v-model="form.email"
                    label="Email address"
                    type="email"
                    name="email"
                    placeholder="you@company.com"
                    required
                    autocomplete="email"
                />
                <Input
                    v-model="form.token"
                    label="Reset token"
                    name="token"
                    placeholder="Paste your reset token"
                    required
                />
                <Input
                    v-model="form.password"
                    label="New password"
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    :error="error('password')"
                    required
                    autocomplete="new-password"
                />
                <Input
                    v-model="form.password_confirmation"
                    label="Confirm new password"
                    type="password"
                    name="password_confirmation"
                    placeholder="••••••••"
                    :error="error('password_confirmation')"
                    required
                    autocomplete="new-password"
                />
                <Button type="submit" variant="primary" :loading="loading" class="w-full">
                    {{ loading ? 'Resetting...' : 'Reset password' }}
                </Button>
            </form>

            <p v-if="message" class="mt-6 rounded-lg border border-success/20 bg-success/10 px-4 py-3 text-sm text-success">
                {{ message }}
            </p>
        </template>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    </GuestLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import GuestLayout from '../../layouts/GuestLayout.vue';
<<<<<<< HEAD
=======
import Input from '../../components/Input.vue';
import Button from '../../components/Button.vue';
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9

const route = useRoute();
const params = new URLSearchParams(window.location.search);
const message = ref('');
<<<<<<< HEAD
=======
const loading = ref(false);
const errors = ref({});
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
const form = reactive({
    email: params.get('email') || '',
    token: route.params.token || '',
    password: '',
    password_confirmation: '',
});

<<<<<<< HEAD
async function submit() {
    const { data } = await axios.post('/api/auth/reset-password', form);
    message.value = data.message;
=======
function error(key) {
    return errors.value[key]?.[0] ?? '';
}

async function submit() {
    loading.value = true;
    errors.value = {};
    try {
        const { data } = await axios.post('/api/auth/reset-password', form);
        message.value = data.message;
        form.password = '';
        form.password_confirmation = '';
    } catch (error) {
        errors.value = error.response?.data?.errors ?? {};
    } finally {
        loading.value = false;
    }
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
}
</script>
