<script setup>
import PulseLoader from "vue-spinner/src/PulseLoader.vue";
import { onMounted } from "vue";
import Cookies from "js-cookie";
import axios from "axios";
import router from "@/router";
import { useToast } from "vue-toastification";

const toast = useToast();

const handleGoogleCallback = () => {
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get("token");
    const isOwner = urlParams.get("isOwner");

    if (token) {
        // Store the token as a cookie
        Cookies.set("auth_token", token, { expires: 30, secure: true });

        // Set the token in the axios default headers
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

        toast.success("Logged in successfully");
        if (isOwner === "1") {
            router.push("/admin/dashboard");
        } else {
            router.push("/blog");
        }
    } else {
        toast.error("Failed to handle Google callback");
        console.error("No token found in the URL");
    }
};

onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("token")) {
        handleGoogleCallback();
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
