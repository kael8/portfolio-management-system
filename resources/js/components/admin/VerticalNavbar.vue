<script setup>
import { RouterLink, useRoute } from "vue-router";
import { computed } from "vue";

const route = useRoute();
const isActiveLink = (routePath) => route.path === routePath;

const props = defineProps({
    isNavbarVisible: Boolean,
});

const navbarClasses = computed(() => ({
    "translate-x-0": props.isNavbarVisible,
    "-translate-x-full": !props.isNavbarVisible,
}));
</script>

<template>
    <div
        :class="navbarClasses"
        class="flex flex-col w-64 h-screen bg-gray-800 text-white fixed top-0 transform transition-transform duration-300 md:translate-x-0 z-10"
    >
        <div class="flex items-center justify-center h-16 bg-gray-900">
            <h1 class="text-xl font-bold">Admin Panel</h1>
        </div>
        <div
            class="flex flex-col items-center justify-center border-b border-gray-700"
        >
            <RouterLink
                to="/admin/dashboard"
                class="p-2 hover:bg-gray-700 flex items-center w-full"
                :class="{ 'bg-gray-700': isActiveLink('/admin/dashboard') }"
            >
                Dashboard
            </RouterLink>
            <RouterLink
                to="/admin/manage"
                class="p-2 hover:bg-gray-700 flex items-center w-full"
                :class="{ 'bg-gray-700': isActiveLink('/admin/manage') }"
            >
                Manage Portfolio
            </RouterLink>
            <RouterLink
                to="/admin/posts"
                class="p-2 hover:bg-gray-700 flex items-center w-full"
                :class="{ 'bg-gray-700': isActiveLink('/admin/posts') }"
            >
                Posts
            </RouterLink>
            <RouterLink
                to="/admin/comments"
                class="p-2 hover:bg-gray-700 flex items-center w-full"
                :class="{ 'bg-gray-700': isActiveLink('/admin/comments') }"
            >
                Comments
            </RouterLink>
        </div>
        <div
            class="flex flex-col items-center justify-center border-b border-gray-700"
        >
            <RouterLink
                to="/admin/settings"
                class="p-2 hover:bg-gray-700 flex items-center w-full"
                :class="{ 'bg-gray-700': isActiveLink('/admin/settings') }"
            >
                Settings
            </RouterLink>
            <RouterLink
                to="/admin/profile"
                class="p-2 hover:bg-gray-700 flex items-center w-full"
                :class="{ 'bg-gray-700': isActiveLink('/admin/profile') }"
            >
                Profile
            </RouterLink>
        </div>
        <div class="flex items-center justify-center h-16">
            <RouterLink
                to="/admin/logout"
                class="p-2 hover:bg-gray-700 flex items-center w-full"
            >
                Logout
            </RouterLink>
        </div>
    </div>
</template>

<style scoped>
/* Optional custom styling */
</style>
