<template>
<<<<<<< HEAD
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
=======
    <div class="min-h-screen bg-bg text-text-main selection:bg-primary/30 selection:text-text-main">
        <!-- Sidebar -->
        <aside
            class="fixed left-0 top-0 bottom-0 w-72 z-40 border-r border-surface-border/60 bg-sidebar backdrop-blur-xl transition-transform duration-300 ease-in-out"
            :class="{ '-translate-x-full': isMobile && !isSidebarOpen }"
        >
            <div class="flex flex-col h-full">
                <!-- Brand -->
                <div class="flex items-center gap-3 h-14 px-5 shrink-0 border-b border-surface-border/40">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-linear-to-br from-primary via-violet-500 to-primary shadow-lg shadow-primary/25">
                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-text-main leading-tight tracking-tight">Complaint<span class="text-primary">Hub</span></div>
                        <div class="text-[10px] text-text-muted uppercase tracking-widest font-semibold">Support Portal</div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-4 px-3">
                    <template v-for="group in navGroups">
                        <div
                            v-if="group.items.length"
                            :key="group.label"
                            class="mb-4"
                        >
                            <div class="mb-1.5 text-[10px] font-bold text-text-muted uppercase tracking-widest px-2">
                                {{ group.label }}
                            </div>
                            <RouterLink
                                v-for="item in group.items"
                                :key="item.to"
                                :to="item.to"
                                class="flex items-center gap-3 px-2.5 py-2 rounded-lg mb-0.5 text-sm transition-all duration-150 group/nav"
                                :class="isActive(item.to) ? 'bg-primary/10 text-primary border border-primary/20' : 'text-text-secondary hover:text-text-main hover:bg-surface/50'"
                            >
                                <Icon :name="item.icon" :class="isActive(item.to) ? 'text-primary' : 'text-text-muted group-hover/nav:text-text-main'" class="h-4 w-4 shrink-0 transition-colors" />
                                <span class="font-medium">{{ item.label }}</span>
                                <span v-if="item.badge" class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-primary/20 text-primary">{{ item.badge }}</span>
                            </RouterLink>
                        </div>
                    </template>
                </nav>

                <!-- User section -->
                <div class="px-3 py-4 border-t border-surface-border/40 shrink-0">
                    <div class="relative">
                        <div
                            class="flex items-center gap-3 px-2 py-2 rounded-lg cursor-pointer hover:bg-surface/50 transition-colors"
                            @click="toggleProfileMenu"
                        >
                            <Avatar :src="user.avatar" :name="user.name" size="md" />
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-text-main truncate">{{ user.name }}</p>
                                <p class="text-[11px] text-text-muted uppercase tracking-wider font-semibold">{{ user.roleLabel }}</p>
                            </div>
                            <ChevronDownIcon :class="['h-4 w-4 text-text-muted transition-transform duration-200', isProfileMenuOpen ? 'rotate-180' : '']" />
                        </div>

                        <Transition
                            enter-active-class="transition duration-150 ease-out"
                            enter-from-class="opacity-0 -translate-y-1"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition duration-100 ease-in"
                            leave-from-class="opacity-100 translate-y-0"
                            leave-to-class="opacity-0 -translate-y-1"
                        >
                            <div v-if="isProfileMenuOpen" class="absolute bottom-full left-0 right-0 mb-2 z-50">
                                <div class="rounded-xl border border-surface-border bg-surface shadow-xl p-1.5">
                                    <button
                                        v-for="item in profileMenuItems"
                                        :key="item.label"
                                        @click="() => { isProfileMenuOpen = false; if (item.action) item.action(); else router.push(item.to); }"
                                        class="flex w-full items-center gap-3 px-3 py-2.5 text-sm text-text-main rounded-lg hover:bg-surface-elevated transition-colors"
                                    >
                                        <Icon :name="item.icon" class="h-4 w-4 text-text-muted" />
                                        <span>{{ item.label }}</span>
                                    </button>
                                </div>
                            </div>
                        </Transition>

                        <button
                            @click="handleLogout"
                            class="mt-2 w-full flex items-center gap-3 px-2 py-2 text-sm text-error rounded-lg hover:bg-error/10 transition-colors"
                        >
                            <LogOutIcon class="h-4 w-4" />
                            <span class="font-medium">Sign out</span>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile overlay -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-out"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isMobile && isSidebarOpen"
                class="fixed inset-0 z-30 bg-black/70 backdrop-blur-sm"
                @click="toggleSidebar"
            ></div>
        </Transition>

        <!-- App body -->
        <div
            class="flex min-h-screen flex-col transition-all duration-300"
            :class="isMobile ? 'pl-0' : 'pl-72'"
        >
            <!-- Top navigation -->
            <header class="sticky top-0 z-30 shrink-0 border-b border-surface-border/40 bg-bg/80 backdrop-blur-xl">
                <div class="flex min-h-14 items-center gap-3 py-3" :class="isMobile ? 'px-4' : 'px-6 lg:px-8'">
                    <button
                        @click="toggleSidebar"
                        class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg text-text-muted hover:text-text-main hover:bg-surface-elevated transition-colors"
                        :class="{ 'hidden': !isMobile }"
                        aria-label="Toggle navigation"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <div class="flex-1">
                        <PageHeader
                            :title="pageTitle"
                            :subtitle="pageSubtitle"
                            :breadcrumbs="breadcrumbs"
                        >
                            <template #actions>
                                <div class="flex flex-wrap items-center gap-2">
                                    <slot name="actions" />
                                    <button
                                        class="flex h-8 w-8 items-center justify-center rounded-lg text-text-muted hover:text-text-main hover:bg-surface-elevated transition-colors"
                                        aria-label="Search"
                                    >
                                        <SearchIcon class="h-4 w-4" />
                                    </button>
                                    <button
                                        class="relative flex h-8 w-8 items-center justify-center rounded-lg text-text-muted hover:text-text-main hover:bg-surface-elevated transition-colors"
                                        aria-label="Notifications"
                                    >
                                        <BellIcon class="h-4 w-4" />
                                        <span v-if="notificationCount" class="absolute -top-0.5 -right-0.5 h-4 w-4 flex items-center justify-center rounded-full bg-error text-[10px] font-bold text-white"></span>
                                    </button>
                                    <Avatar
                                        :name="user.name"
                                        :src="user.avatar"
                                        size="sm"
                                        class="bg-surface-elevated ring-2 ring-surface-border"
                                    />
                                </div>
                            </template>
                        </PageHeader>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
                    </div>
                </div>
            </header>

<<<<<<< HEAD
            <main id="main-content" class="mx-auto w-full max-w-[1400px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                <slot />
=======
            <!-- Page content -->
            <main class="relative flex-1 px-4 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto w-full max-w-7xl">
                    <slot />
                </div>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
            </main>
        </div>
    </div>
</template>

<script setup>
<<<<<<< HEAD
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
=======
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import {
    ChevronDownIcon,
    LogOutIcon,
    SearchIcon,
    BellIcon,
} from '@lucide/vue';

import Avatar from '../components/Avatar.vue';
import Icon from '../components/Icon.vue';
import PageHeader from '../components/PageHeader.vue';

const props = defineProps({
    section: { type: String, default: 'user' },
    title: { type: String, default: '' },
    subtitle: { type: String, default: '' },
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
});

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();
<<<<<<< HEAD
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
=======

const isMobile = ref(window.innerWidth < 768);
const isSidebarOpen = ref(!isMobile.value);
const isProfileMenuOpen = ref(false);

const notificationCount = ref(0);

const user = computed(() => {
    const u = auth.user;
    if (!u) {
        return { name: 'Guest', roleLabel: '', avatar: '' };
    }
    return {
        name: u.name,
        roleLabel: u.role === 'staff' ? 'Administrator' : u.role.charAt(0).toUpperCase() + u.role.slice(1).replace('_', ' '),
        avatar: u.avatar ?? '',
    };
});

const isStaff = computed(() => auth.isStaff);

function isActive(path) {
    if (route.path === path) {
        return true;
    }

    if (path === '/complaints/new' || path === '/dashboard') {
        return false;
    }

    return route.path.startsWith(`${path}/`);
}

const navGroups = computed(() => {
    if (isStaff.value) {
        return [
            {
                label: 'Administration',
                items: [
                    { to: '/admin/dashboard', label: 'Dashboard', icon: 'LayoutDashboardIcon' },
                    { to: '/admin/complaints', label: 'Complaints', icon: 'FileTextIcon' },
                    { to: '/admin/categories', label: 'Categories', icon: 'TagIcon' },
                    { to: '/admin/users', label: 'Users', icon: 'UsersIcon' },
                    { to: '/admin/settings', label: 'Settings', icon: 'SettingsIcon' },
                ],
            },
        ];
    }

    return [
        {
            label: 'Workspace',
            items: [
                { to: '/dashboard', label: 'Dashboard', icon: 'LayoutDashboardIcon' },
                { to: '/complaints', label: 'My Complaints', icon: 'FileTextIcon' },
                { to: '/complaints/new', label: 'Submit Complaint', icon: 'FilePlusIcon' },
                { to: '/profile', label: 'Profile', icon: 'UserIcon' },
            ],
        },
    ];
});

const profileMenuItems = computed(() => {
    const items = [
        { label: 'Profile', to: '/profile', icon: 'UserIcon' },
    ];
    if (isStaff.value) {
        items.push({ label: 'Administration', to: '/admin/dashboard', icon: 'ShieldCheckIcon' });
    }
    return items;
});

const routeTitles = {
    '/dashboard': 'Dashboard',
    '/complaints': 'My Complaints',
    '/complaints/new': 'Submit Complaint',
    '/complaints/:id': 'Complaint Details',
    '/profile': 'Profile & Settings',
    '/admin/dashboard': 'Admin Dashboard',
    '/admin/complaints': 'Complaint Management',
    '/admin/complaints/:id': 'Complaint Review',
    '/admin/categories': 'Categories',
    '/admin/users': 'User Management',
    '/admin/settings': 'System Settings',
};

const routeSubtitles = {
    '/dashboard': 'Your personalized overview.',
    '/complaints': 'Track and manage your submitted complaints.',
    '/complaints/new': 'Describe the issue so the team can act quickly.',
    '/profile': 'Manage your personal details and security.',
    '/admin/dashboard': 'Operational command center.',
    '/admin/complaints': 'Review and manage all complaints.',
    '/admin/categories': 'Manage the complaint taxonomy.',
    '/admin/users': 'Manage accounts, roles, and status.',
    '/admin/settings': 'Control system-wide preferences.',
};

/** Literal paths must win over `:id` patterns so /complaints/new is not read as a detail page. */
function fromRouteMap(map, fallback) {
    const keys = Object.keys(map).sort((a, b) => {
        const aIsPattern = a.includes(':') ? 1 : 0;
        const bIsPattern = b.includes(':') ? 1 : 0;

        if (aIsPattern !== bIsPattern) {
            return aIsPattern - bIsPattern;
        }

        return b.length - a.length;
    });

    for (const key of keys) {
        if (new RegExp(`^${key.replace(':id', '[^/]+')}$`).test(route.path)) {
            return map[key];
        }
    }

    return fallback;
}

const pageTitle = computed(() => props.title || fromRouteMap(routeTitles, 'Dashboard'));

const pageSubtitle = computed(() => props.subtitle || fromRouteMap(routeSubtitles, ''));

const breadcrumbs = computed(() => {
    const path = route.path;
    const crumbs = [{ label: 'Home', to: '/' }];
    if (path.startsWith('/admin')) {
        crumbs.push({ label: 'Administration' });
    } else if (path === '/dashboard') {
        crumbs.push({ label: 'Dashboard' });
    } else if (path.startsWith('/complaints')) {
        crumbs.push({ label: 'Complaints', to: '/complaints' });
    } else if (path === '/profile') {
        crumbs.push({ label: 'Profile' });
    }
    return crumbs;
});

function toggleSidebar() {
    if (isMobile.value) {
        isSidebarOpen.value = !isSidebarOpen.value;
    }
}

function toggleProfileMenu() {
    isProfileMenuOpen.value = !isProfileMenuOpen.value;
}

async function handleLogout() {
    try {
        await auth.logout();
        router.push('/login');
    } catch {
        router.push('/login');
    }
}

onMounted(() => {
    const handleResize = () => {
        const mobile = window.innerWidth < 768;
        isMobile.value = mobile;
        if (!mobile) {
            isSidebarOpen.value = true;
            isProfileMenuOpen.value = false;
        }
    };
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
});

watch(
    () => auth.user,
    () => {
        if (auth.isAuthenticated && isMobile.value) {
            isSidebarOpen.value = false;
        }
    },
);
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
</script>
