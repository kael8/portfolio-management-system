<script setup>
import { ref, onMounted } from "vue";
import apiClient from "@/services/apiClient";
import { useToast } from "vue-toastification";
import PulseLoader from "vue-spinner/src/PulseLoader.vue";

/*const info = ref({
    school: "Southern Leyte State University",
    degree: "Bachelor of Science in Information Technology",
    description:
        "I'm a seasoned full-stack web developer with a strong focus on PHP Laravel, jQuery, and AWS. I thrive on optimizing systems and backends, consistently delivering scalable, robust solutions that enhance both performance and user experience. My work is driven by a commitment to creating intuitive, user-friendly interfaces and improving backend efficiency, which has resulted in noticeably faster load times in past projects. I'm passionate about staying at the forefront of technology, using tools like ChatGPT and GitHub Copilot to streamline development workflows and push the boundaries of what's possible in web development.",
});*/

const info = ref({
    school: "",
    degree: "",
    description: "",
});

onMounted(async () => {
    try {
        const response = await apiClient.get("/about");
        if (response.data && response.data.about) {
            info.value = response.data.about;
        }
    } catch (error) {
        console.error(error);
        toast.error("Failed to load about information");
    }
});

const toast = useToast();
const isLoading = ref(false);

const saveInfo = async () => {
    // Logic to save the information
    isLoading.value = true;

    try {
        const response = await apiClient.post("/about", info.value);
        if (response.status === 201) {
            toast.success("Information saved successfully");
        } else {
            toast.error("Failed to save information");
            s;
        }
    } catch (error) {
        console.error(error);
        toast.error(error.response.data.message || "Failed to create post");
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <div
        class="flex flex-col w-full h-full items-center rounded-md bg-gray-800 text-white justify-center p-6"
    >
        <h1 class="text-4xl font-bold mb-6">About</h1>

        <!-- School -->
        <div class="w-full mb-4">
            <label for="school" class="text-sm font-medium text-gray-300 mb-1"
                >School</label
            >
            <input
                v-model="info.school"
                id="school"
                name="school"
                type="text"
                class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                placeholder="School"
            />
        </div>

        <!-- Degree -->
        <div class="w-full mb-4">
            <label for="degree" class="text-sm font-medium text-gray-300 mb-1"
                >Degree</label
            >
            <input
                v-model="info.degree"
                id="degree"
                name="degree"
                type="text"
                class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                placeholder="Degree"
            />
        </div>

        <!-- description -->
        <div class="w-full mb-4">
            <label
                for="description"
                class="text-sm font-medium text-gray-300 mb-1"
                >Description</label
            >
            <textarea
                v-model="info.description"
                id="description"
                name="description"
                rows="6"
                class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                placeholder="description"
            ></textarea>
        </div>

        <!-- Submit Button -->
        <button
            @click="saveInfo"
            class="w-full p-3 bg-green-600 hover:bg-green-700 rounded-md text-white"
        >
            <span v-if="isLoading">
                <PulseLoader color="white" size="8px" />
            </span>
            <span v-else>Save</span>
        </button>
    </div>
</template>
