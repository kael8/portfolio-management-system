<script setup>
import { ref, onMounted } from "vue";
import apiClient from "@/services/apiClient";
import { useToast } from "vue-toastification";
import PulseLoader from "vue-spinner/src/PulseLoader.vue";

const showModal = ref(false);
const userImage = ref("https://via.placeholder.com/150"); // Replace with the actual default image path
const previewImage = ref(null);
const selectedFile = ref(null);
const isLoading = ref(false);

const toast = useToast();

onMounted(async () => {
    try {
        const response = await apiClient.get("/profile-image");

        userImage.value = response.data.image;
    } catch (error) {
        console.error("Failed to fetch profile image:", error);
    }
});

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    previewImage.value = null;
    selectedFile.value = null;
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        selectedFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const saveImage = async () => {
    if (selectedFile.value) {
        isLoading.value = true;
        try {
            const formData = new FormData();
            formData.append("image", selectedFile.value);

            const response = await apiClient.post(
                "/profile-image",
                formData,
                {}
            );
            if (response.status === 200) {
                toast.success("Image uploaded successfully");
                userImage.value = response.data.image;
                closeModal();
            } else {
                toast.error("Failed to upload image");
            }
        } catch (error) {
            console.error("Failed to upload image:", error);
            toast.error("Failed to upload image");
        } finally {
            isLoading.value = false;
        }
    }
};
</script>

<template>
    <!-- Profile photo -->
    <div
        class="flex flex-col w-full h-full items-center rounded-md bg-gray-800 text-white justify-center p-6 mb-8"
    >
        <h1 class="text-4xl font-bold mb-6">Profile Photo</h1>
        <img
            class="w-32 h-32 rounded-full border-4 border-gray-700 shadow-lg object-cover"
            :src="userImage"
            alt=""
        />
        <button
            @click="openModal"
            class="mt-4 p-2 bg-blue-600 hover:bg-blue-700 rounded-md text-white"
        >
            Upload Photo
        </button>
    </div>

    <!-- Modal -->
    <transition name="modal">
        <div
            v-if="showModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
            @click.self="closeModal"
        >
            <div
                class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md w-full relative"
            >
                <button
                    @click="closeModal"
                    class="absolute top-2 right-4 text-gray-500 hover:text-gray-700"
                >
                    &times;
                </button>
                <h3 class="text-2xl font-bold text-white mb-4">Upload Image</h3>
                <input
                    type="file"
                    @change="handleImageUpload"
                    class="w-full p-2 border border-gray-700 rounded-md bg-gray-900 text-white mb-4"
                />
                <div
                    v-if="previewImage"
                    class="mb-4 flex flex-col items-center"
                >
                    <h4 class="text-lg font-bold text-white mb-2">Preview:</h4>
                    <img
                        :src="previewImage"
                        alt="Preview"
                        class="w-32 h-32 rounded-full border-4 border-gray-700 shadow-lg object-cover"
                    />
                </div>
                <button
                    @click="saveImage"
                    class="w-full p-2 bg-green-600 hover:bg-green-700 rounded-md text-white"
                    :disabled="isLoading"
                >
                    <span v-if="isLoading" class="z-40">
                        <PulseLoader :color="'#1f2937 '" />
                    </span>
                    <span v-else> Save </span>
                </button>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.5s, transform 0.5s;
}
.modal-enter, .modal-leave-to /* .modal-leave-active in <2.1.8 */ {
    opacity: 0;
    transform: scale(0.9);
}
</style>
