<template>
<<<<<<< HEAD
    <GuestLayout title="Reset link" subtitle="We will email you a password reset link.">
        <form class="mt-8 grid gap-4" @submit.prevent="submit">
            <label class="grid gap-2 text-sm"><span>Email</span><input v-model="email" type="email"
                    class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3" /></label>
            <button class="rounded-full bg-teal-400 px-5 py-3 text-sm font-semibold text-slate-950 cursor-pointer">Send
                reset link</button>
        </form>
        <p v-if="message" class="mt-4 text-sm text-emerald-300">{{ message }}</p>
=======
    <GuestLayout title="Reset your password" subtitle="Enter your email and we will send you a reset link." variant="Password reset">
        <template #content>
            <form class="mt-10 grid gap-4" @submit.prevent="submit">
                <Input
                    v-model="email"
                    label="Email address"
                    type="email"
                    name="email"
                    placeholder="you@company.com"
                    required
                    autocomplete="email"
                />
                <Button type="submit" variant="primary" :loading="loading" class="w-full">
                    {{ loading ? 'Sending...' : 'Send reset link' }}
                </Button>
            </form>

            <p v-if="message" class="mt-6 rounded-lg border border-success/20 bg-success/10 px-4 py-3 text-sm text-success">
                {{ message }}
            </p>

            <p class="mt-10 text-sm text-text-secondary text-center">
                Remember your password?{{ ' ' }}
                <RouterLink class="font-medium text-primary hover:text-primary-dark" to="/login">Sign in</RouterLink>
            </p>
        </template>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    </GuestLayout>
</template>

<script setup>
import { ref } from 'vue';
<<<<<<< HEAD
import axios from 'axios';
import GuestLayout from '../../layouts/GuestLayout.vue';

const email = ref('');
const message = ref('');

async function submit() {
    const { data } = await axios.post('/api/auth/forgot-password', { email: email.value });
    message.value = data.message;
=======
import { RouterLink } from 'vue-router';
import axios from 'axios';
import GuestLayout from '../../layouts/GuestLayout.vue';
import Input from '../../components/Input.vue';
import Button from '../../components/Button.vue';

const email = ref('');
const message = ref('');
const loading = ref(false);

async function submit() {
    loading.value = true;
    try {
        const { data } = await axios.post('/api/auth/forgot-password', { email: email.value });
        message.value = data.message;
    } catch (error) {
        message.value = '';
    } finally {
        loading.value = false;
    }
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
}
</script>
