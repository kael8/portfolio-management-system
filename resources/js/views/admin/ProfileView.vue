<script setup>
import { ref, onMounted } from "vue";
import AdminView from "@/views/admin/AdminView.vue";
import apiClient from "@/services/apiClient";
import { useToast } from "vue-toastification";

const toast = useToast();

const profileForm = ref({
    name: "",
    email: "",
    currentPassword: "",
    newPassword: "",
    confirmPassword: "",
    isPrivate: false,
    profileImage: null,
});

const profileImagePreview = ref(null);

const loadProfile = async () => {
    try {
        const response = await apiClient.get("/get-account");
        profileForm.value = {
            ...profileForm.value,
            name: response.data.account.name,
            email: response.data.account.email,
            isPrivate: response.data.account.isPrivate === 1,
        };
        profileImagePreview.value = response.data.account.profile?.image_url;
    } catch (error) {
        toast.error("Failed to load profile information");
    }
};

const updateProfile = async () => {
    try {
        const formData = new FormData();
        formData.append("name", profileForm.value.name);

        if (
            profileForm.value.newPassword &&
            profileForm.value.newPassword !== profileForm.value.confirmPassword
        ) {
            toast.error("Passwords do not match");
            return;
        }

        if (profileForm.value.newPassword) {
            formData.append(
                "currentPassword",
                profileForm.value.currentPassword
            );
            formData.append("password", profileForm.value.newPassword);
        }

        formData.append("isPrivate", profileForm.value.isPrivate ? "1" : "0");
        if (profileForm.value.profileImage) {
            formData.append("image", profileForm.value.profileImage);
        }

        await apiClient.post("/update-account", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        toast.success("Profile updated successfully");
    } catch (error) {
        toast.error("Failed to update profile");
    }
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        profileForm.value.profileImage = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            profileImagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

onMounted(() => {
    loadProfile();
});
</script>

<template>
    <AdminView>
        <div
            class="flex flex-col w-full h-full items-center text-white justify-center p-6 mb-8"
        >
            <div
                class="flex flex-col items-center w-full p-5 mb-10 rounded-md bg-gray-800"
            >
                <h1 class="text-4xl font-bold mb-6 w-full">
                    Profile Information
                </h1>
                <div
                    class="flex flex-col items-center w-full px-10 mb-10 space-y-4"
                >
                    <div class="w-full flex justify-center mb-4">
                        <div class="relative w-32 h-32">
                            <img
                                :src="
                                    profileImagePreview ||
                                    'https://via.placeholder.com/150'
                                "
                                alt="Profile Image Preview"
                                class="rounded-full w-32 h-32 object-cover"
                            />
                            <label
                                for="profileImage"
                                class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full cursor-pointer opacity-0 hover:opacity-100 transition-opacity"
                            >
                                <i class="fas fa-camera text-white text-xl"></i>
                            </label>
                            <input
                                id="profileImage"
                                type="file"
                                @change="handleImageUpload"
                                class="hidden"
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        <div>
                            <label
                                for="name"
                                class="text-sm font-medium text-gray-300 mb-1"
                                >Name</label
                            >
                            <input
                                v-model="profileForm.name"
                                id="name"
                                name="name"
                                type="text"
                                class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                                placeholder="Name"
                            />
                        </div>
                        <div>
                            <label
                                for="email"
                                class="text-sm font-medium text-gray-300 mb-1"
                                >Email</label
                            >
                            <input
                                v-model="profileForm.email"
                                id="email"
                                name="email"
                                type="text"
                                class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                                placeholder="Email"
                                disabled
                            />
                        </div>
                        <div>
                            <label
                                for="newPassword"
                                class="text-sm font-medium text-gray-300 mb-1"
                                >New Password</label
                            >
                            <input
                                v-model="profileForm.newPassword"
                                id="newPassword"
                                name="newPassword"
                                type="password"
                                class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                                placeholder="New Password"
                            />
                        </div>
                        <div>
                            <label
                                for="confirmPassword"
                                class="text-sm font-medium text-gray-300 mb-1"
                                >Confirm Password</label
                            >
                            <input
                                v-model="profileForm.confirmPassword"
                                id="confirmPassword"
                                name="confirmPassword"
                                type="password"
                                class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                                placeholder="Confirm Password"
                            />
                        </div>
                    </div>
                    <div class="w-full flex items-center mt-4">
                        <input
                            v-model="profileForm.isPrivate"
                            type="checkbox"
                            class="mr-2"
                        />
                        <label class="text-sm font-medium text-gray-300"
                            >Make my profile private</label
                        >
                    </div>
                    <button
                        @click="updateProfile"
                        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        Update Profile
                    </button>
                </div>
            </div>
        </div>
    </AdminView>
</template>

<style scoped>
/* Optional custom styles */
</style>
