<template>
<<<<<<< HEAD
    <div class="min-h-screen bg-slate-50 text-slate-900">
        <!-- Header -->
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600 text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-semibold text-slate-900">Campus Complaint Management System</h1>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex flex-1">
            <div class="mx-auto w-full max-w-md px-4 py-10 sm:px-6 lg:px-8">
                <div class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-900">{{ title }}</h2>
                    <p v-if="subtitle" class="mt-2 text-sm text-slate-500">{{ subtitle }}</p>
                    <slot />
                </div>
            </div>
        </main>
=======
    <div
        class="min-h-screen bg-bg flex items-center justify-center px-4 py-6 sm:px-6 lg:px-8"
    >
        <div class="mx-auto w-full max-w-[420px]">
            <AuthSteps :steps="steps" :current="step" class="pt-2" />

            <div class="flex items-center gap-2 mb-8">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-linear-to-br from-primary to-secondary text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        ></path>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-semibold text-text-main leading-tight">Complaint<span class="text-primary">Hub</span></div>
                    <div class="text-[10px] text-text-muted uppercase tracking-wider">Support Portal</div>
                </div>
            </div>

            <Card bordered elevated class="rounded-[2rem] p-8 sm:p-10 shadow-lg">
                <div class="text-center mb-8">
                    <p class="text-[10px] font-semibold text-primary uppercase tracking-wider mb-2">
                        {{ variant }}
                    </p>
                    <h1 class="text-2xl font-bold text-text-main tracking-tight">{{ title }}</h1>
                    <p v-if="subtitle" class="text-sm text-text-secondary mt-2">
                        {{ subtitle }}
                    </p>
                </div>

                <slot name="content" />
            </Card>
        </div>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    </div>
</template>

<script setup>
<<<<<<< HEAD
defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
=======
import AuthSteps from '../components/AuthSteps.vue';
import Card from '../components/Card.vue';

defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
    variant: { type: String, default: 'Complaint Hub' },
    steps: { type: Array, default: () => [] },
    step: { type: Number, default: 1 },
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
});
</script>
