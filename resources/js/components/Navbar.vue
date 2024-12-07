<script setup>
import { RouterLink, useRoute } from "vue-router";
import { computed } from "vue";
import { authState } from "@/services/auth";
import { useToast } from "vue-toastification";
import axios from "axios";
import Cookies from "js-cookie";
import { useRouter } from "vue-router";
import apiClient from "@/services/apiClient";

const route = useRoute();
const router = useRouter();
const toast = useToast();

const isAuthenticated = computed(() => authState.isAuthenticated);

const logout = async () => {
    try {
        await apiClient.post("/auth/logout");
        Cookies.remove("auth_token");
        axios.defaults.headers.common["Authorization"] = "";
        authState.isAuthenticated = false;
        toast.success("Logged out successfully");
    } catch (error) {
        toast.error("Failed to log out");
    }
};

const isActiveLink = (routePath) => route.path === routePath;
</script>

<template>
    <nav class="border-b border-secondary/60 backdrop-blur-md">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between">
                <div
                    class="flex flex-1 items-center justify-center md:items-stretch md:justify-start"
                >
                    <!-- Logo -->
                    <RouterLink
                        class="flex flex-shrink-0 items-center mr-4"
                        to="/"
                    >
                        <span
                            class="hidden md:block text-highlight/90 text-2xl font-bold ml-2"
                        >
                            Portfolio
                        </span>
                    </RouterLink>

                    <!-- Navigation Links -->
                    <div class="md:ml-auto">
                        <div class="flex space-x-2">
                            <!-- Admin Dashboard -->
                            <RouterLink
                                v-if="isAuthenticated"
                                to="/admin/dashboard"
                                :class="[
                                    isActiveLink('/admin/dashboard')
                                        ? 'bg-secondary/90'
                                        : 'hover:bg-highlight/30',
                                    'text-white/80 px-3 py-2 rounded-md',
                                ]"
                            >
                                Admin Panel
                            </RouterLink>

                            <!-- Blog -->
                            <RouterLink
                                to="/blog"
                                :class="[
                                    isActiveLink('/blog')
                                        ? 'bg-secondary/90'
                                        : 'hover:bg-highlight/30',
                                    'text-white/80 px-3 py-2 rounded-md',
                                ]"
                            >
                                Blog
                            </RouterLink>

                            <!-- Home Link -->
                            <RouterLink
                                to="/"
                                :class="[
                                    isActiveLink('/')
                                        ? 'bg-secondary/90'
                                        : 'hover:bg-highlight/30',
                                    'text-white/80 px-3 py-2 rounded-md text-white',
                                ]"
                            >
                                Home
                            </RouterLink>

                            <!-- Conditional Login/Logout Link -->
                            <div v-if="isAuthenticated">
                                <button
                                    @click="logout"
                                    :class="[
                                        'hover:bg-highlight/30 ',
                                        'text-white/80 px-3 py-2 rounded-md',
                                    ]"
                                >
                                    Logout
                                </button>
                            </div>

                            <RouterLink
                                v-else
                                to="/auth/login"
                                :class="[
                                    isActiveLink('/auth/login')
                                        ? 'bg-secondary/90 text-white'
                                        : 'hover:bg-highlight/30 ',
                                    'text-white/80 px-3 py-2 rounded-md',
                                ]"
                            >
                                Login
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>
/* Optional custom styling */
</style>
