<script setup>
import { RouterLink, useRoute } from "vue-router";
import { computed } from "vue";
import { authState } from "@/services/auth";

const route = useRoute();
const isActiveLink = (routePath) => route.path === routePath;

const isOwner = computed(() => authState.isOwner);

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
                to="/admin/blog-post"
                class="p-2 hover:bg-gray-700 flex items-center w-full"
                :class="{ 'bg-gray-700': isActiveLink('/admin/blog-post') }"
            >
                Blog Posts
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
            <!-- Viewing as Guest Sign -->
            <span v-if="!isOwner" class="text-sm text-gray-300">
                Viewing as Guest
            </span>
        </div>
    </div>
</template>

<style scoped>
/* Optional custom styling */
</style>
