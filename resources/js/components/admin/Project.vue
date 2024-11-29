<script setup>
import { ref, onMounted } from "vue";
import apiClient from "@/services/apiClient";
import { useToast } from "vue-toastification";
import PulseLoader from "vue-spinner/src/PulseLoader.vue";

const projects = ref([]);

const showModal = ref(false);
const showDeleteModal = ref(false);
const isEditing = ref(false);
const currentProject = ref({
    id: null,
    title: "",
    description: "",
    image: "",
});
const selectedFile = ref(null);
const isLoading = ref(false);
const projectToDelete = ref(null);

const toast = useToast();

const fetchProjects = async () => {
    try {
        const response = await apiClient.get("/projects");
        if (response.data && response.data.projects) {
            projects.value = response.data.projects;
        }
    } catch (error) {
        console.error("Failed to fetch projects:", error);
    }
};

onMounted(async () => {
    fetchProjects();
});

const openModal = (project = null) => {
    if (
        project &&
        project.id !== null &&
        project.title &&
        project.description &&
        project.image
    ) {
        console.log("Editing project:", project);
        isEditing.value = true;
        currentProject.value = { ...project };
    } else {
        console.log("Adding new project");
        isEditing.value = false;
        currentProject.value = {
            id: null,
            title: "",
            description: "",
            image: "",
        };
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    isEditing.value = false; // Reset isEditing state
    currentProject.value = {
        id: null,
        title: "",
        description: "",
        image: "",
    };
    selectedFile.value = null;
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        selectedFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            currentProject.value.image = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const saveProject = async () => {
    isLoading.value = true;
    try {
        const formData = new FormData();
        formData.append("title", currentProject.value.title);
        formData.append("description", currentProject.value.description);
        if (selectedFile.value) {
            formData.append("image", selectedFile.value);
        } else {
            formData.append("image_url", currentProject.value.image);
        }

        let response;
        if (isEditing.value) {
            response = await apiClient.post(
                `/projects/${currentProject.value.id}/update`,
                formData
            );
        } else {
            response = await apiClient.post("/projects", formData);
        }

        if (response.status === 200 || response.status === 201) {
            toast.success(
                `Project ${
                    isEditing.value ? "updated" : "created"
                } successfully`
            );
            if (isEditing.value) {
                const index = projects.value.findIndex(
                    (p) => p.id === currentProject.value.id
                );
                projects.value[index] = response.data.project;
            } else {
                projects.value.push(response.data.project);
            }
            closeModal();
        } else {
            toast.error(
                `Failed to ${isEditing.value ? "update" : "create"} project`
            );
        }
    } catch (error) {
        console.error(
            `Failed to ${isEditing.value ? "update" : "create"} project:`,
            error
        );
        toast.error(
            `Failed to ${isEditing.value ? "update" : "create"} project`
        );
    } finally {
        isLoading.value = false;
        fetchProjects();
    }
};

const confirmDeleteProject = (project) => {
    projectToDelete.value = project;
    showDeleteModal.value = true;
};

const deleteProject = async () => {
    isLoading.value = true;
    if (!projectToDelete.value) return;

    try {
        const response = await apiClient.delete(
            `/projects/${projectToDelete.value.id}`
        );
        if (response.status === 200) {
            toast.success("Project deleted successfully");
            projects.value = projects.value.filter(
                (p) => p.id !== projectToDelete.value.id
            );
        } else {
            toast.error("Failed to delete project");
        }
    } catch (error) {
        console.error("Failed to delete project:", error);
        toast.error("Failed to delete project");
    } finally {
        showDeleteModal.value = false;
        projectToDelete.value = null;
        isLoading.value = false;
        fetchProjects();
    }
};
</script>

<template>
    <div
        class="flex flex-col w-full h-full items-center rounded-md bg-gray-800 text-white justify-center p-6"
    >
        <h1 class="text-4xl font-bold mb-6">Projects</h1>
        <div class="w-full flex justify-end mb-6">
            <button
                @click="openModal"
                class="p-2 bg-green-600 hover:bg-green-700 rounded-md text-white"
            >
                Add Project
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
                v-for="project in projects"
                :key="project.id"
                class="bg-gray-700 rounded-lg shadow-lg p-4 transform transition duration-300 hover:scale-105 hover:bg-gray-600"
            >
                <img
                    :src="project.image"
                    alt="Project Image"
                    class="w-full h-48 object-cover rounded-md mb-4"
                />
                <h2 class="text-2xl font-bold mb-2">{{ project.title }}</h2>
                <p class="text-gray-300 mb-4">{{ project.description }}</p>
                <button
                    @click="openModal(project)"
                    class="mr-2 p-2 bg-blue-600 hover:bg-blue-700 rounded-md text-white"
                >
                    Edit
                </button>
                <button
                    @click="confirmDeleteProject(project)"
                    class="p-2 bg-red-600 hover:bg-red-700 rounded-md text-white"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Project Modal -->
    <transition name="modal">
        <div
            v-if="showModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-10"
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
                <h3 class="text-2xl font-bold text-white mb-4">
                    {{ isEditing ? "Edit Project" : "Add Project" }}
                </h3>
                <input
                    v-model="currentProject.title"
                    type="text"
                    placeholder="Title"
                    class="w-full p-2 border border-gray-700 rounded-md bg-gray-900 text-white mb-4"
                />
                <textarea
                    v-model="currentProject.description"
                    placeholder="Description"
                    class="w-full p-2 border border-gray-700 rounded-md bg-gray-900 text-white mb-4"
                ></textarea>
                <input
                    type="file"
                    @change="handleImageUpload"
                    class="w-full p-2 border border-gray-700 rounded-md bg-gray-900 text-white mb-4"
                />
                <div
                    v-if="currentProject.image"
                    class="mb-4 flex flex-col items-center"
                >
                    <h4 class="text-lg font-bold text-white mb-2">Preview:</h4>
                    <img
                        :src="currentProject.image"
                        alt="Preview"
                        class="w-32 h-32 rounded-[20px] border-4 border-gray-800 shadow-lg object-cover"
                    />
                </div>
                <button
                    @click="saveProject"
                    class="w-full p-2 bg-green-600 hover:bg-green-700 rounded-md text-white"
                    :disabled="isLoading"
                >
                    <span v-if="isLoading">
                        <PulseLoader :color="'#1f2937'" />
                    </span>
                    <span v-else>
                        {{ isEditing ? "Update" : "Save" }}
                    </span>
                </button>
            </div>
        </div>
    </transition>

    <!-- Delete Confirmation Modal -->
    <transition name="modal">
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-10"
            @click.self="showDeleteModal = false"
        >
            <div
                class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md w-full relative"
            >
                <button
                    @click="showDeleteModal = false"
                    class="absolute top-2 right-4 text-gray-500 hover:text-gray-700"
                >
                    &times;
                </button>
                <h3 class="text-2xl font-bold text-white mb-4">
                    Confirm Delete
                </h3>
                <p class="text-gray-300 mb-4">
                    Are you sure you want to delete this project?
                </p>
                <div class="flex justify-end">
                    <button
                        @click="showDeleteModal = false"
                        class="mr-2 p-2 bg-gray-600 hover:bg-gray-700 rounded-md text-white"
                    >
                        Cancel
                    </button>
                    <button
                        @click="deleteProject"
                        class="p-2 bg-red-600 hover:bg-red-700 rounded-md text-white"
                    >
                        <span v-if="isLoading">
                            <PulseLoader :color="'#1f2937'" />
                        </span>
                        <span v-else> Delete </span>
                    </button>
                </div>
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
