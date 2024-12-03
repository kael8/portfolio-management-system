<script setup>
import PulseLoader from "vue-spinner/src/PulseLoader.vue";
import { onMounted } from "vue";
import apiClient from "@/services/apiClient";

onMounted(async () => {
    try {
        const response = await apiClient.get("/auth-check", {
            withCredentials: true,
        });
        if (response.data && response.data.authenticated) {
            window.location.href = "/admin/dashboard";
        } else {
            window.location.href = "/auth/login";
        }
    } catch (error) {
        console.error(error);
        window.location.href = "/auth/login";
    }
});
</script>

<template>
    <div class="flex items-center justify-center min-h-screen">
        <div
            class="flex flex-col items-center justify-center rounded-md text-white p-20"
        >
            <h1 class="text-4xl font-bold mb-6">Redirecting</h1>
            <PulseLoader color="#ffffff" size="15px" />
        </div>
    </div>
</template>

<style scoped>
/* Optional custom styles */
</style>
