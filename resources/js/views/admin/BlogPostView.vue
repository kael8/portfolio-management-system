<script setup>
import AdminView from "@/views/admin/AdminView.vue";
import Post from "@/components/Post.vue";
import { ref, onMounted } from "vue";
import apiClient from "@/services/apiClient";
import { CameraIcon } from "@heroicons/vue/24/outline";
import { useToast } from "vue-toastification";

const showModal = ref(false);
const userImage = ref("https://via.placeholder.com/150"); // Replace with the actual default image path
const toast = useToast();

onMounted(async () => {
    try {
        const response = await apiClient.get("/profile-image");
        userImage.value = response.data.image;
    } catch (error) {
        console.error("Failed to fetch profile image:", error);
    }
});

const postForm = ref({
    title: "",
    content: "",
    images: [], // Array to store image files and their previews
});

const createPost = async () => {
    const formData = new FormData();
    formData.append("title", postForm.value.title);
    formData.append("content", postForm.value.content);

    postForm.value.images.forEach((image) => {
        if (image.file) {
            formData.append("images[]", image.file);
        }
    });

    try {
        const response = await apiClient.post("/create-post", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        if (response.status === 201) {
            toast.success("Post created successfully");
            showModal.value = false;
            postForm.value = { title: "", content: "", images: [] }; // Reset the form
        } else {
            toast.error("Failed to create post");
        }
    } catch (error) {
        console.error("Error creating post:", error);
        toast.error("Error creating post");
    }
};

const addImageInput = () => {
    postForm.value.images.push({ file: null, preview: null });
};

const handleFileChange = (files) => {
    [...files].forEach((file) => {
        if (
            postForm.value.images.some(
                (image) => image.file?.name === file.name
            )
        ) {
            // Skip duplicates
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            postForm.value.images.push({ file, preview: e.target.result });
        };
        reader.readAsDataURL(file);
    });
};

const removeImageInput = (index) => {
    postForm.value.images.splice(index, 1);
};
</script>

<template>
    <AdminView>
        <div
            class="flex flex-col items-center justify-start min-h-screen text-white"
        >
            <!-- Create Post Button -->
            <div class="w-full max-w-2xl p-4">
                <button
                    @click="showModal = true"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full"
                >
                    What's on your mind?
                </button>
            </div>

            <!-- Post List -->
            <div class="w-full max-w-2xl p-4">
                <Post />
            </div>

            <!-- Modal -->
            <transition name="modal">
                <div
                    v-if="showModal"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                >
                    <div
                        class="bg-gray-800 w-full max-w-2xl p-6 rounded-lg shadow-lg text-gray-400 overflow-y-auto max-h-[90vh]"
                    >
                        <!-- Modal Header -->
                        <div class="flex items-center mb-4">
                            <img
                                :src="userImage"
                                alt="User Avatar"
                                class="w-10 h-10 rounded-full mr-3 object-cover"
                            />
                            <h2 class="text-lg font-bold">Create Post</h2>
                        </div>

                        <!-- Modal Body -->
                        <form @submit.prevent="createPost">
                            <!-- Title Input -->
                            <div class="mb-4">
                                <label
                                    for="title"
                                    class="block text-gray-400 font-medium mb-2"
                                >
                                    Title
                                </label>
                                <input
                                    v-model="postForm.title"
                                    id="title"
                                    type="text"
                                    placeholder="Enter post title"
                                    class="w-full px-4 py-2 bg-gray-900 border focus:outline-none focus:ring-2 focus:ring-blue-500 border-gray-700 rounded-md"
                                    required
                                />
                            </div>

                            <!-- Content Textarea -->
                            <div class="mb-4">
                                <textarea
                                    v-model="postForm.content"
                                    placeholder="What's on your mind?"
                                    class="w-full px-4 py-2 bg-gray-900 border focus:outline-none focus:ring-2 focus:ring-blue-500 border-gray-700 rounded-md resize-none"
                                    rows="4"
                                    required
                                ></textarea>
                            </div>

                            <!-- Drag and Drop Upload Section -->
                            <div
                                class="mb-4 border border-dashed border-gray-600 p-4 rounded-md flex flex-col items-center justify-center"
                                @dragover.prevent
                                @dragenter.prevent
                                @dragleave.prevent
                                @drop.prevent="
                                    (event) =>
                                        handleFileChange(
                                            event.dataTransfer.files
                                        )
                                "
                            >
                                <p class="text-gray-400 mb-2">
                                    Drag and drop images here
                                </p>
                                <button
                                    type="button"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold p-3 rounded-full relative"
                                >
                                    <CameraIcon class="h-6 w-6" />
                                    <!-- File Input -->
                                    <input
                                        type="file"
                                        accept="image/*"
                                        class="absolute inset-0 opacity-0 cursor-pointer"
                                        multiple
                                        @change="
                                            (event) =>
                                                handleFileChange(
                                                    event.target.files
                                                )
                                        "
                                    />
                                </button>
                            </div>

                            <!-- Image Previews -->
                            <div
                                v-if="postForm.images.length"
                                class="flex flex-wrap gap-4 max-h-60 overflow-y-auto p-2 border border-gray-700 rounded-md mb-4"
                            >
                                <div
                                    v-for="(image, index) in postForm.images"
                                    :key="index"
                                    class="relative flex flex-col items-center"
                                >
                                    <!-- Remove Button -->
                                    <button
                                        @click="removeImageInput(index)"
                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-700"
                                    >
                                        ✕
                                    </button>
                                    <img
                                        v-if="image.preview"
                                        :src="image.preview"
                                        alt="Preview"
                                        class="w-24 h-24 object-cover rounded-md"
                                    />
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="flex justify-end space-x-4">
                                <button
                                    @click="showModal = false"
                                    type="button"
                                    class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Publish
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </transition>
        </div>
    </AdminView>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.modal-enter-from {
    transform: scale(0.9) translateY(50%);
    opacity: 0;
}

.modal-enter-to {
    transform: scale(1) translateY(0);
    opacity: 1;
}

.modal-leave-from {
    transform: scale(1) translateY(0);
    opacity: 1;
}

.modal-leave-to {
    transform: scale(0.9) translateY(50%);
    opacity: 0;
}
</style>
