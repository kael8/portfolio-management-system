<script setup>
import Navbar from "@/components/Navbar.vue";
import { reactive } from "vue";
import { useToast } from "vue-toastification";
import Cookies from "js-cookie";
import axios from "axios";
import router from "@/router";

const form = reactive({
    email: "",
    password: "",
    agreeToTerms: false,
});

const toast = useToast();

const loginWithGoogle = async () => {
    if (!form.agreeToTerms) {
        toast.error("You must agree to the terms and conditions");
        return;
    }

    try {
        window.location.href = "/api/auth/google";
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

const loginWithForm = async () => {
    if (!form.agreeToTerms) {
        toast.error("You must agree to the terms and conditions");
        return;
    }

    try {
        const response = await axios.post("/api/auth/signin", {
            email: form.email,
            password: form.password,
        });
        const token = response.data.token;
        const isOwner = response.data.isOwner;

        // Store the token as a cookie
        Cookies.set("auth_token", token, { expires: 30, secure: true });

        // Set the token in the axios default headers
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

        router.push("/auth/redirect?token=" + token + "&isOwner=" + isOwner);
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
    <Navbar />
    <div class="pt-10 flex-1 text-center">
        <!-- Login Form -->
        <div class="flex justify-center mb-10">
            <div
                class="bg-gray-800 rounded-lg shadow-2xl p-8 transform transition duration-300 w-full md:w-2/3 lg:w-1/2"
            >
                <h3 class="text-3xl font-bold text-white mb-6">Login</h3>
                <form @submit.prevent="loginWithForm" class="space-y-6">
                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="Email"
                        class="w-full p-2 rounded bg-gray-700 text-white"
                        required
                    />
                    <input
                        v-model="form.password"
                        type="password"
                        placeholder="Password"
                        class="w-full p-2 rounded bg-gray-700 text-white"
                        required
                    />
                    <div class="flex items-center">
                        <input
                            v-model="form.agreeToTerms"
                            type="checkbox"
                            id="agreeToTerms"
                            class="mr-2"
                            required
                        />
                        <label for="agreeToTerms" class="text-gray-400">
                            I agree to the
                            <router-link
                                to="/terms-and-conditions"
                                class="text-blue-500 hover:underline"
                            >
                                terms and conditions
                            </router-link>
                            and
                            <router-link
                                to="/privacy-policy"
                                class="text-blue-500 hover:underline"
                            >
                                privacy policy </router-link
                            >.
                        </label>
                    </div>
                    <button
                        type="submit"
                        class="p-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition duration-300 w-full"
                    >
                        Sign In
                    </button>
                </form>
                <div class="flex items-center justify-center w-full my-4">
                    <hr class="w-full border-gray-700" />
                    <span class="px-2 text-gray-500">or</span>
                    <hr class="w-full border-gray-700" />
                </div>
                <button
                    type="button"
                    @click="loginWithGoogle"
                    class="p-3 rounded-lg bg-red-600 text-white hover:bg-red-700 transition duration-300 w-full mt-4 flex items-center justify-center"
                >
                    <i class="fab fa-google mr-2"></i>
                    Login with Google
                </button>
                <div class="mt-4">
                    <router-link
                        to="/auth/signup"
                        class="text-blue-500 hover:underline"
                    >
                        Don't have an account? Sign up
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Additional styling if needed */
</style>
