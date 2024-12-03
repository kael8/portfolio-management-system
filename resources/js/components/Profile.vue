<script setup>
import { ref, onMounted } from "vue";
import apiClient from "@/services/apiClient";
import PulseLoader from "vue-spinner/src/PulseLoader.vue";

const isLoading = ref(false);

const profile = ref({
    user: {
        name: "",
        email: "",
    },
    title: "",
    description: "",
    phone: "",
    address: "",
    twitter: "",
    linkedin: "",
    github: "",
    image_url: "https://via.placeholder.com/150",
});

onMounted(async () => {
    isLoading.value = true;
    try {
        const response = await apiClient.get("/profile");
        if (response.data && response.data.profile) {
            profile.value.user.name = response.data.profile.name;
            profile.value.user.email = response.data.profile.email;
            profile.value = {
                ...profile.value,
                ...response.data.profile.profile,
            };
        }
    } catch (error) {
        console.error("Error fetching profile:", error);
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <div
        class="w-full bg-gray-800 rounded-lg shadow-2xl flex flex-col md:flex-row items-center mt-10"
    >
        <!-- Profile Picture Section (top on mobile, left on larger screens) -->
        <div class="relative w-80 h-80 p-6">
            <PulseLoader
                v-if="isLoading"
                color="#ffffff"
                class="absolute inset-0 flex items-center justify-center"
                size="10px"
            />
            <img
                v-else
                :src="
                    profile.image_url ||
                    'https://via.placeholder.com/320x320?text=No+Image'
                "
                alt="Profile Image"
                class="w-full h-full object-cover rounded-xl border-4 border-gray-700 shadow-md"
            />
        </div>

        <!-- Text Section (bottom on mobile, right on larger screens) -->
        <div class="p-10 flex-1 text-center md:text-left">
            <h2 class="text-5xl font-extrabold text-white mb-4">
                {{ profile.user.name }}
            </h2>
            <p class="text-2xl text-gray-400 mb-6">{{ profile.title }}</p>
            <p class="text-lg text-gray-300 leading-relaxed">
                {{ profile.description }}
            </p>
        </div>
    </div>
</template>

<style scoped>
/* Optional custom styles */
</style>
