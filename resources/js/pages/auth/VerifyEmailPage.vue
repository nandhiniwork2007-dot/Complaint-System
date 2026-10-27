<template>
<<<<<<< HEAD
    <GuestLayout title="Verify email" subtitle="Certain actions are locked until your email is verified.">
        <div class="mt-8 grid gap-4 text-sm text-slate-200">
            <p v-if="verified" class="rounded-2xl bg-emerald-500/20 px-4 py-3 text-emerald-200">Your email is verified. You can continue to the portal.</p>
            <p v-else class="rounded-2xl bg-white/10 px-4 py-3">Check your inbox for the verification link. If you did not receive it, request another email below.</p>
            <p v-if="notice" class="rounded-2xl bg-white/10 px-4 py-3">{{ notice }}</p>
            <button v-if="!verified"
                class="rounded-full bg-teal-400 px-5 py-3 text-sm font-semibold text-slate-950 cursor-pointer"
                @click="resend">Resend verification email</button>
            <button
                class="inline-flex rounded-full border border-white/20 px-5 py-3 text-sm font-semibold text-white cursor-pointer"
                @click="continueToPortal">{{ verified ? 'Continue to portal' : 'Refresh verification status' }}</button>
            <button
                class="inline-flex rounded-full border border-rose-300 px-5 py-3 text-sm font-semibold text-rose-200 cursor-pointer"
                @click="logout">Sign out</button>
        </div>
=======
    <GuestLayout
        title="Verify your email"
        subtitle="Confirm your email address to unlock the portal."
        variant="Verify email"
        :steps="registrationSteps"
        :step="verified ? 3 : 2"
    >
        <template #content>
            <div class="mt-10 space-y-4">
                <div
                    v-if="verified"
                    class="rounded-lg border border-success/20 bg-success/10 px-5 py-4"
                >
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 shrink-0 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-text-main">Your email is verified.</p>
                            <p class="text-xs text-text-secondary">You now have full access to the portal.</p>
                        </div>
                    </div>
                </div>

                <div v-else class="rounded-lg border border-surface-border bg-surface/50 px-5 py-4">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 shrink-0 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-text-main">Check your inbox for the verification link.</p>
                            <p class="text-xs text-text-secondary">Open the email we sent and click the verification link to activate your account.</p>
                        </div>
                    </div>
                </div>

                <p v-if="notice" class="rounded-lg border border-error/20 bg-error/10 px-4 py-3 text-sm text-error">
                    {{ notice }}
                </p>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4">
                    <button v-if="!verified" @click="resend" :disabled="resending" class="flex-1 flex items-center justify-center gap-2 rounded-lg border border-surface-border bg-surface px-4 py-3 text-sm font-medium text-text-main hover:bg-surface-elevated transition-colors disabled:opacity-60">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15 35l2-2m0 0l2-2m-2 2l-2-2m-2 2l-3-3m-3 3l-3-3"></path>
                        </svg>
                        {{ resending ? 'Sending...' : 'Resend verification email' }}
                    </button>

                    <Button
                        variant="primary"
                        :loading="verifying"
                        @click="continueToPortal"
                        class="flex-1"
                    >
                        {{ verifying ? 'Checking...' : (verified ? 'Continue to portal' : 'Refresh status') }}
                    </Button>
                </div>

                <button @click="logout" class="mt-4 flex items-center justify-center gap-1.5 text-xs text-text-secondary hover:text-text-main transition-colors">
                    <LogOutIcon class="h-4 w-4" />
                    <span>Sign out</span>
                </button>
            </div>
        </template>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    </GuestLayout>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
<<<<<<< HEAD
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import GuestLayout from '../../layouts/GuestLayout.vue';
import { useAuthStore } from '../../stores/auth';
import { useToastStore } from '../../stores/toasts';
import { extractMessage } from '../../utils/http';
=======
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import GuestLayout from '../../layouts/GuestLayout.vue';
import Button from '../../components/Button.vue';
import { useAuthStore } from '../../stores/auth';
import { useToastStore } from '../../stores/toasts';
import { extractMessage } from '../../utils/http';
import { LogOutIcon } from '@lucide/vue';
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9

const auth = useAuthStore();
const route = useRoute();
const router = useRouter();
const toast = useToastStore();
const verified = computed(() => !!auth.user?.email_verified_at);
const notice = ref('');
<<<<<<< HEAD

async function refreshStatus(showSuccess = false) {
    await auth.refresh();

    if (verified.value && showSuccess) {
        toast.success('Email verified successfully.');
=======
const resending = ref(false);
const verifying = ref(false);

const registrationSteps = ['Create account', 'Verify email', 'Get started'];

async function refreshStatus(showSuccess = false) {
    verifying.value = true;
    try {
        await auth.refresh();
        if (verified.value && showSuccess) {
            toast.success('Email verified successfully.');
        }
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to refresh verification status.'));
    } finally {
        verifying.value = false;
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    }
}

onMounted(async () => {
    try {
        await refreshStatus(route.query.verified === '1');

        if (route.query.verified === '1') {
            notice.value = 'Your email has been verified. You can continue now.';
        }
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to refresh verification status.'));
    }
});

async function resend() {
<<<<<<< HEAD
=======
    resending.value = true;
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    try {
        const { data } = await axios.post('/api/auth/email/verification-notification');
        notice.value = data.mail_error ? `${data.message} ${data.mail_error}` : data.message;
        toast.success(data.message);
    } catch (error) {
        notice.value = extractMessage(error, 'Unable to resend verification email.');
        const mailError = error?.response?.data?.mail_error;
<<<<<<< HEAD

        if (mailError) {
            notice.value = `${notice.value} ${mailError}`;
        }

        toast.error(notice.value);
=======
        if (mailError) {
            notice.value = `${notice.value} ${mailError}`;
        }
        toast.error(notice.value);
    } finally {
        resending.value = false;
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    }
}

async function continueToPortal() {
    try {
        await refreshStatus();

        if (!verified.value) {
            notice.value = 'Your email is still not verified. Open the mail link first, then continue.';
            toast.error(notice.value);
            return;
        }

        await router.push(auth.user?.is_staff ? '/admin/dashboard' : '/dashboard');
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to continue right now.'));
    }
}

async function logout() {
    try {
        await auth.logout();
        router.push('/login');
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to sign out right now.'));
    }
}
<<<<<<< HEAD
</script>
=======
</script>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
