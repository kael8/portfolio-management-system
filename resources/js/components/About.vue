<script setup>
import { ref, onMounted } from "vue";
import apiClient from "@/services/apiClient";

const state = ref({
    school: "",
    degree: "",
    description: "",
});

onMounted(async () => {
    try {
        const response = await apiClient.get("/about");
        if (response.data && response.data.about) {
            state.value = response.data.about;
        }
    } catch (error) {
        console.error(error);
        // Handle error
    }
});
</script>

<template>
    <div class="p-10 flex-1 text-center">
        <h2 class="text-5xl font-extrabold text-white mb-10">About Me</h2>

        <h3 class="text-3xl font-bold text-white mb-4">Education</h3>
        <p class="text-lg text-gray-300 leading-relaxed mb-10">
            {{ state.school }}<br />
            {{ state.degree }}
        </p>

        <h3 class="text-3xl font-bold text-white mb-4">Profile</h3>
        <p class="text-lg text-gray-300 leading-relaxed mb-10">
            {{ state.description }}
        </p>
    </div>
</template>

<style scoped>
/* Additional styling if needed */
</style>
