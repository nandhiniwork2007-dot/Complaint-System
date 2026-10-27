<template>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        <a href="#main-content"
            class="sr-only rounded-lg bg-white px-4 py-2 text-sm font-medium text-blue-700 shadow focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60]">
            Skip to content
        </a>

        <!-- Sidebar -->
        <aside id="app-sidebar"
            class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col bg-navy-900 text-slate-300 transition-transform duration-200 ease-out lg:translate-x-0"
            :class="[mobileOpen ? 'translate-x-0' : '-translate-x-full', collapsed ? 'lg:w-20' : 'lg:w-64']"
            aria-label="Primary">
            <div class="flex h-16 shrink-0 items-center gap-3 border-b border-white/10 px-4">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white">
                    <AppIcon name="shield" class="h-5 w-5" />
                </span>
                <span class="text-sm font-semibold leading-tight text-white" :class="collapsed ? 'lg:hidden' : ''">
                    Campus Complaint Management System
                </span>
                <button type="button"
                    class="ml-auto rounded-lg p-1.5 text-slate-400 hover:bg-white/10 hover:text-white lg:hidden"
                    aria-label="Close navigation" @click="mobileOpen = false">
                    <AppIcon name="x" class="h-5 w-5" />
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-500"
                    :class="collapsed ? 'lg:hidden' : ''">
                    {{ section === 'admin' ? 'Administration' : 'Menu' }}
                </p>
                <RouterLink v-for="item in navItems" :key="item.to" :to="item.to" :title="item.label"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                    :class="[isActive(item) ? 'bg-blue-600 text-white shadow-sm' : 'hover:bg-white/10 hover:text-white', collapsed ? 'lg:justify-center' : '']"
                    :aria-current="isActive(item) ? 'page' : undefined">
                    <AppIcon :name="item.icon" class="h-5 w-5 shrink-0" />
                    <span :class="collapsed ? 'lg:hidden' : ''">{{ item.label }}</span>
                </RouterLink>
            </nav>

            <div class="space-y-1 border-t border-white/10 px-3 py-3">
                <RouterLink v-if="section === 'admin'" to="/profile" title="Profile"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                    :class="[route.path === '/profile' ? 'bg-blue-600 text-white' : 'hover:bg-white/10 hover:text-white', collapsed ? 'lg:justify-center' : '']">
                    <AppIcon name="user" class="h-5 w-5 shrink-0" />
                    <span :class="collapsed ? 'lg:hidden' : ''">Profile</span>
                </RouterLink>
                <button type="button" title="Log out"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-white/10 hover:text-white disabled:opacity-60"
                    :class="collapsed ? 'lg:justify-center' : ''" :disabled="loggingOut" @click="logout">
                    <AppIcon name="log-out" class="h-5 w-5 shrink-0" />
                    <span :class="collapsed ? 'lg:hidden' : ''">{{ loggingOut ? 'Signing out…' : 'Log out' }}</span>
                </button>
            </div>
        </aside>

        <div v-if="mobileOpen" class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden" aria-hidden="true"
            @click="mobileOpen = false"></div>

        <!-- Main column -->
        <div class="transition-[padding] duration-200" :class="collapsed ? 'lg:pl-20' : 'lg:pl-64'">
            <header class="sticky top-0 z-20 border-b border-slate-200 bg-white">
                <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                    <button type="button"
                        class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden"
                        aria-label="Open navigation" aria-controls="app-sidebar" :aria-expanded="mobileOpen"
                        @click="mobileOpen = true">
                        <AppIcon name="menu" class="h-5 w-5" />
                    </button>
                    <button type="button"
                        class="hidden rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:inline-flex"
                        :aria-label="collapsed ? 'Expand sidebar' : 'Collapse sidebar'" :aria-pressed="collapsed"
                        @click="toggleCollapsed">
                        <AppIcon name="panel-left" class="h-5 w-5" />
                    </button>

                    <div class="min-w-0 flex-1">
                        <slot name="header">
                            <h1 class="truncate text-lg font-semibold text-slate-900">{{ title }}</h1>
                            <p v-if="subtitle" class="hidden truncate text-sm text-slate-500 sm:block">{{ subtitle }}</p>
                        </slot>
                    </div>

                    <div ref="menuRoot" class="relative">
                        <button type="button"
                            class="flex items-center gap-2 rounded-lg p-1.5 pr-2 hover:bg-slate-100"
                            aria-haspopup="menu" :aria-expanded="menuOpen" @click="menuOpen = !menuOpen">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white">
                                {{ initials }}
                            </span>
                            <span class="hidden text-left sm:block">
                                <span class="block text-sm font-medium leading-tight text-slate-900">{{ user?.name }}</span>
                                <span class="block text-xs leading-tight text-slate-500">{{ roleLabel }}</span>
                            </span>
                            <AppIcon name="chevron-down" class="hidden h-4 w-4 text-slate-400 sm:block" />
                        </button>
                        <div v-if="menuOpen" role="menu"
                            class="absolute right-0 mt-2 w-60 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
                            <div class="border-b border-slate-100 px-4 py-3">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ user?.name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ user?.email }}</p>
                            </div>
                            <RouterLink to="/profile" role="menuitem"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
                                <AppIcon name="user" class="h-4 w-4" /> Profile
                            </RouterLink>
                            <button type="button" role="menuitem"
                                class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50"
                                @click="logout">
                                <AppIcon name="log-out" class="h-4 w-4" /> Log out
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <main id="main-content" class="mx-auto w-full max-w-[1400px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import AppIcon from '../components/AppIcon.vue';
import { useAuthStore } from '../stores/auth';
import { useToastStore } from '../stores/toasts';
import { extractMessage } from '../utils/http';

const props = defineProps({
    title: { type: String, default: '' },
    subtitle: { type: String, default: '' },
    section: { type: String, default: 'user' },
});

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();
const toast = useToastStore();

const STORAGE_KEY = 'ccms.sidebar.collapsed';
const readCollapsed = () => {
    try {
        return localStorage.getItem(STORAGE_KEY) === '1';
    } catch (error) {
        return false;
    }
};

const collapsed = ref(readCollapsed());
const mobileOpen = ref(false);
const menuOpen = ref(false);
const menuRoot = ref(null);
const loggingOut = ref(false);

const user = computed(() => auth.user);
const initials = computed(() => (user.value?.name || '?').split(' ').filter(Boolean).map((part) => part[0]).join('').toUpperCase().slice(0, 2));
const ROLE_LABELS = { admin: 'Administrator', manager: 'Manager', support_agent: 'Support Agent', user: 'Student' };
const roleLabel = computed(() => ROLE_LABELS[user.value?.role] || 'Member');

const navItems = computed(() => {
    if (props.section === 'admin') {
        const items = [
            { to: '/admin/dashboard', label: 'Overview', icon: 'dashboard', exact: true },
            { to: '/admin/complaints', label: 'Complaints', icon: 'file-text' },
            { to: '/admin/categories', label: 'Categories', icon: 'tag' },
            { to: '/admin/users', label: 'Users', icon: 'users' },
            { to: '/admin/reports', label: 'Reports & Analytics', icon: 'chart' },
        ];
        // The audit history endpoint is limited to roles that can manage users.
        if (user.value?.can_manage_users) {
            items.push({ to: '/admin/audit', label: 'Audit History', icon: 'history' });
        }
        items.push({ to: '/admin/settings', label: 'Settings', icon: 'settings' });
        return items;
    }

    return [
        { to: '/dashboard', label: 'Dashboard', icon: 'dashboard', exact: true },
        { to: '/complaints', label: 'My Complaints', icon: 'file-text', match: (path) => path === '/complaints' || (path.startsWith('/complaints/') && path !== '/complaints/new') },
        { to: '/complaints/new', label: 'New Complaint', icon: 'plus', exact: true },
        { to: '/profile', label: 'Profile', icon: 'user', exact: true },
    ];
});

function isActive(item) {
    const path = route.path;
    if (item.match) return item.match(path);
    if (item.exact) return path === item.to;
    return path === item.to || path.startsWith(`${item.to}/`);
}

function toggleCollapsed() {
    collapsed.value = !collapsed.value;
    try {
        localStorage.setItem(STORAGE_KEY, collapsed.value ? '1' : '0');
    } catch (error) {
        // Preference simply won't persist.
    }
}

async function logout() {
    if (loggingOut.value) return;
    loggingOut.value = true;
    try {
        await auth.logout();
        menuOpen.value = false;
        mobileOpen.value = false;
        await router.push('/login');
    } catch (error) {
        toast.error(extractMessage(error, 'Unable to sign out. Please try again.'));
    } finally {
        loggingOut.value = false;
    }
}

function onDocumentClick(event) {
    if (menuOpen.value && menuRoot.value && !menuRoot.value.contains(event.target)) {
        menuOpen.value = false;
    }
}

function onKeydown(event) {
    if (event.key === 'Escape') {
        menuOpen.value = false;
        mobileOpen.value = false;
    }
}

watch(() => route.fullPath, () => {
    mobileOpen.value = false;
    menuOpen.value = false;
});

watch(() => props.title, (value) => {
    document.title = value ? `${value} · Campus Complaint Management System` : 'Campus Complaint Management System';
}, { immediate: true });

onMounted(() => {
    document.addEventListener('click', onDocumentClick);
    document.addEventListener('keydown', onKeydown);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', onDocumentClick);
    document.removeEventListener('keydown', onKeydown);
});
</script>
