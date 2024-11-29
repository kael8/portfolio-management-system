<script setup>
import axios from "axios";
import router from "@/router";
import { reactive } from "vue";
import { useToast } from "vue-toastification";
import AuthService from "../services/authService";

const form = reactive({
    email: "",
    password: "",
});

const toast = useToast();

const loginWithGoogle = async () => {
    try {
        const response = await AuthService.loginWithGoogle();
        toast.success("Logged in successfully");
    } catch (error) {
        if (error.response) {
            toast.error(`Failed to login: ${error.response.data.message}`);
            console.error("Error response:", error.response);
        } else if (error.request) {
            toast.error("No response received from the server");
            console.error("Error request:", error.request);
        } else {
            toast.error("Error in setting up the request");
            console.error("Error message:", error.message);
        }
    }
};

const handleSubmit = async () => {
    try {
        const response = await axios.post("/api/login", {
            email: form.email,
            password: form.password,
        });

        toast.success("Logged in successfully");
        console.log(response.data);
        // Redirect to another page if needed
        // router.push('/dashboard');
    } catch (error) {
        if (error.response) {
            toast.error(`Failed to login: ${error.response.data.message}`);
            console.error("Error response:", error.response);
        } else if (error.request) {
            toast.error("No response received from the server");
            console.error("Error request:", error.request);
        } else {
            toast.error("Error in setting up the request");
            console.error("Error message:", error.message);
        }
    }
};
</script>

<template>
    <div class="pt-10 flex-1 text-center">
        <h2 class="text-5xl font-extrabold text-white mb-10">Login</h2>

        <!-- Login Form -->
        <div class="flex justify-center mb-10">
            <div
                class="bg-gray-800 rounded-lg shadow-2xl p-8 transform transition duration-300 w-full md:w-2/3 lg:w-1/2"
            >
                <h3 class="text-3xl font-bold text-white mb-6">Login</h3>
                <form class="space-y-6">
                    <button
                        type="button"
                        @click="loginWithGoogle"
                        class="p-3 rounded-lg bg-red-600 text-white hover:bg-red-700 transition duration-300"
                    >
                        Login with Google
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Additional styling if needed */
</style>
