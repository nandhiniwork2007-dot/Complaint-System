<template>
    <div class="min-h-screen bg-slate-50">
        <!-- Sidebar Navigation -->
        <aside v-if="!isMobile || isSidebarOpen" class="fixed inset-y-0 left-0 w-64 bg-navy-900 text-white transition-transform duration-300 ease-in-out z-40" :class="{ '-translate-x-full': !isSidebarOpen && isMobile }">
            <div class="flex flex-col h-full">
                <div class="p-4 bg-navy-800">
                    <h2 class="text-xl font-bold">Complaint System</h2>
                </div>
                <nav class="flex-1 overflow-y-auto">
                    <RouterLink v-for="item in navItems" :key="item.to" :to="item.to" class="block px-4 py-3 text-sm font-medium hover:bg-navy-700 transition-colors" :class="{ 'bg-navy-700': isActive(item.to) }">
                        <span class="flex items-center gap-3">
                            <component :is="item.icon" class="w-5 h-5" />
                            {{ item.label }}
                        </span>
                    </RouterLink>
                </nav>
                <div class="p-4 border-t border-navy-700">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-600 flex items-center justify-center text-white font-semibold">
                            {{ userInitials }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium">{{ user.name }}</p>
                            <p class="text-xs text-slate-400">{{ user.role }}</p>
                        </div>
                        <button @click="toggleSidebar" class="p-2 hover:bg-navy-700 rounded-lg">
                            <ChevronLeftIcon class="w-5 h-5" />
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 transition-margin duration-300" :class="{ 'ml-64': !isMobile && isSidebarOpen }">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center gap-3">
                            <button @click="toggleSidebar" class="p-2 rounded-lg hover:bg-slate-100 text-slate-600" :class="{ 'hidden': isMobile && !isSidebarOpen }">
                                <MenuIcon class="w-6 h-6" />
                            </button>
                            <div>
                                <h1 class="text-xl font-bold text-slate-800">{{ title }}</h1>
                                <p class="text-sm text-slate-600">{{ subtitle }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <button class="p-2 rounded-lg hover:bg-slate-100 text-slate-600">
                                <BellIcon class="w-6 h-6" />
                            </button>
                            <button class="p-2 rounded-lg hover:bg-slate-100 text-slate-600">
                                <SearchIcon class="w-6 h-6" />
                            </button>
                            <div class="relative">
                                <button @click="isProfileOpen = true" class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-100">
                                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                                        {{ userInitials }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-700">{{ user.name }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <slot />
            </main>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div v-if="isMobile && isSidebarOpen" class="fixed inset-0 bg-black/50 z-30" @click="toggleSidebar"></div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { ChevronLeftIcon, MenuIcon, BellIcon, SearchIcon } from 'lucide-vue-next';

const route = useRoute();
const isMobile = ref(window.innerWidth < 768);
const isSidebarOpen = ref(!isMobile.value);
const isProfileOpen = ref(false);

const user = ref({
    name: 'John Doe',
    role: 'Administrator',
    email: 'john@example.com'
    // Will be populated from auth store
});

const userInitials = computed(() => {
    return user.value.name
        ?.split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
});

const navItems = ref([
    { to: '/admin/dashboard', label: 'Dashboard', icon: 'DashboardIcon' },
    { to: '/admin/complaints', label: 'Complaints', icon: 'FileTextIcon' },
    { to: '/admin/categories', label: 'Categories', icon: 'TagIcon' },
    { to: '/admin/users', label: 'Users', icon: 'UsersIcon' },
]);

const isActive = (path) => {
    return route.path === path || route.path.startsWith(path + '/');
};

const toggleSidebar = () => {
    if (isMobile.value) {
        isSidebarOpen.value = !isSidebarOpen.value;
    }
    // For desktop, always show sidebar
};

onMounted(() => {
    const handleResize = () => {
        isMobile.value = window.innerWidth < 768;
        if (!isMobile.value) {
            isSidebarOpen.value = true;
        }
    };
    window.addEventWindowListener('resize', handleResize);
    // Cleanup
    return () => {
        window.removeEventListener('resize', handleResize);
    };

    // Load user data from auth store
    // user.value = useAuthStore().user;
});
</script>

<style scoped>
:deep() {
    .sidebar-transition {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
}
</style>