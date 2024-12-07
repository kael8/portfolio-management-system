<script setup>
import { RouterLink, useRoute } from "vue-router";
import { useToast } from "vue-toastification";
import apiClient from "@/services/apiClient";
import router from "@/router";
import { setAuthState, setIsOwner } from "@/services/auth";

const route = useRoute();
const toast = useToast();

const isActiveLink = (routePath) => route.path === routePath;

const handleLogout = async () => {
    try {
        await apiClient.post("/auth/logout");
        toast.success("Logged out successfully");
        setAuthState(false);
        setIsOwner(false);
        router.push("/auth/login");
    } catch (error) {
        console.error(error);
        toast.error("Failed to logout");
    }
};
</script>

<template>
    <nav class="fixed top-0 w-full z-10 bg-gray-900">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="flex h-[63px] items-center justify-between">
                <div
                    class="flex flex-1 items-center h-16 justify-center md:items-stretch md:justify-start"
                ></div>

                <!-- Navigation Links -->
                <div class="md:ml-auto flex items-center">
                    <form @submit.prevent="handleLogout">
                        <!-- Logout Button -->
                        <button
                            type="submit"
                            :class="[
                                'hover:bg-highlight/30',
                                'text-white/80 px-3 py-2 rounded-md',
                            ]"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>
/* Optional custom styling */
</style>
