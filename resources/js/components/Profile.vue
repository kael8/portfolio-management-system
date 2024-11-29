<script setup>
import { defineProps } from "vue";
const props = defineProps({
    userName: String,
    userImage: String,
});
</script>

<template>
    <div
        class="w-full bg-gray-800 rounded-lg shadow-2xl flex flex-col md:flex-row items-center mt-10"
    >
        <!-- Profile Picture Section (top on mobile, left on larger screens) -->
        <div class="p-6">
            <img
                :src="userImage"
                alt="Profile Picture"
                class="w-80 h-80 object-cover rounded-xl border-4 border-gray-700 shadow-md"
            />
        </div>

        <!-- Text Section (bottom on mobile, right on larger screens) -->
        <div class="p-10 flex-1 text-center md:text-left">
            <h2 class="text-5xl font-extrabold text-white mb-4">
                {{ userName }}
            </h2>
            <p class="text-2xl text-gray-400 mb-6">Web Developer</p>
            <p class="text-lg text-gray-300 leading-relaxed">
                Passionate about building responsive, user-friendly web
                applications using modern frameworks. Skilled in Vue.js,
                Laravel, and frontend tools, with a dedication to continual
                learning and professional development.
            </p>
        </div>
    </div>
</template>

<style scoped>
/* Optional custom styles */
</style>
