<script setup>
import { ref, onMounted } from "vue";
import apiClient from "@/services/apiClient";
import { useToast } from "vue-toastification";

const info = ref({
    user: {
        name: "",
        email: "",
    },
    profile: {
        title: "",
        description: "",
        phone: "",
        address: "",
        twitter: "",
        linkedin: "",
        github: "",
        image_url: "",
    },
});

const toast = useToast();

onMounted(async () => {
    try {
        const response = await apiClient.get("/profile");
        if (response.data && response.data.profile) {
            info.value.user.name = response.data.profile.name;
            info.value.user.email = response.data.profile.email;
            info.value.profile = response.data.profile.profile;
        }
    } catch (error) {
        console.error(error);
        toast.error("Failed to load profile information");
    }
});

const handleSubmit = async () => {
    try {
        console.log(info.value);
        // Send the data to the server
        const response = await apiClient.post("/profile", info.value.profile);

        // Show a success message
        toast.success("Profile information updated successfully");
    } catch (error) {
        console.error(error);
        toast.error("Failed to update profile information");
    }
};
</script>

<template>
    <!--Introduction-->
    <div
        class="flex flex-col w-full h-full items-center rounded-md bg-gray-800 text-white justify-center p-6 mb-8"
    >
        <h1 class="text-4xl font-bold mb-6">Profile Information</h1>
        <div class="flex flex-col items-center w-full px-10 mb-10 space-y-4">
            <form @submit.prevent="handleSubmit">
                <div class="w-full mb-4">
                    <label
                        for="name"
                        class="text-sm font-medium text-gray-300 mb-1"
                        >Name</label
                    >
                    <input
                        v-model="info.user.name"
                        id="name"
                        name="name"
                        type="text"
                        class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                        placeholder="Name"
                        disabled
                    />
                </div>
                <div class="w-full mb-4">
                    <label
                        for="title"
                        class="text-sm font-medium text-gray-300 mb-1"
                        >Title</label
                    >
                    <input
                        v-model="info.profile.title"
                        id="title"
                        name="title"
                        type="text"
                        class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                        placeholder="Title"
                    />
                </div>
                <div class="w-full mb-4">
                    <label
                        for="description"
                        class="text-sm font-medium text-gray-300 mb-1"
                        >Description</label
                    >
                    <textarea
                        v-model="info.profile.description"
                        id="description"
                        name="description"
                        class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                        placeholder="Description"
                    ></textarea>
                </div>

                <label
                    for="email"
                    class="text-sm font-medium text-gray-300 mb-1"
                    >Email</label
                >
                <input
                    v-model="info.user.email"
                    id="email"
                    name="email"
                    type="email"
                    class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 mb-4 text-white"
                    placeholder="Email"
                    disabled
                />

                <label
                    for="phone"
                    class="text-sm font-medium text-gray-300 mb-1"
                    >Phone</label
                >
                <input
                    v-model="info.profile.phone"
                    id="phone"
                    name="phone"
                    type="tel"
                    class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 mb-4 text-white"
                    placeholder="Phone"
                />

                <label
                    for="address"
                    class="text-sm font-medium text-gray-300 mb-1"
                    >Address</label
                >
                <input
                    v-model="info.profile.address"
                    id="address"
                    name="address"
                    type="text"
                    class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 mb-4 text-white"
                    placeholder="Address"
                />

                <div class="flex flex-col w-full space-y-4 mb-4">
                    <h2 class="text-2xl font-bold">Social Media</h2>

                    <label
                        for="twitter"
                        class="text-sm font-medium text-gray-300 mb-1"
                        >Twitter</label
                    >
                    <input
                        v-model="info.profile.twitter"
                        id="twitter"
                        name="twitter"
                        type="text"
                        class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 mb-4 text-white"
                        placeholder="Twitter"
                    />

                    <label
                        for="linkedin"
                        class="text-sm font-medium text-gray-300 mb-1"
                        >LinkedIn</label
                    >
                    <input
                        v-model="info.profile.linkedin"
                        id="linkedin"
                        name="linkedin"
                        type="text"
                        class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 mb-4 text-white"
                        placeholder="LinkedIn"
                    />

                    <label
                        for="github"
                        class="text-sm font-medium text-gray-300 mb-1"
                        >GitHub</label
                    >
                    <input
                        v-model="info.profile.github"
                        id="github"
                        name="github"
                        type="text"
                        class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 mb-4 text-white"
                        placeholder="GitHub"
                    />
                </div>
                <button
                    type="submit"
                    class="w-full p-3 bg-green-600 hover:bg-green-700 text-white rounded-md"
                >
                    Submit
                </button>
            </form>
        </div>
    </div>
</template>

<style scoped>
/* Add any additional custom styles here */
</style>
