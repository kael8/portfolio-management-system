<script setup>
import { onMounted, ref } from "vue";
import FuneralHome from "@/assets/images/works/funeralhome.png";
import Pharmacy from "@/assets/images/works/pharmacy.png";
import Quotation from "@/assets/images/works/quotation.png";
import apiClient from "@/services/apiClient";

const works = ref([]);

onMounted(async () => {
    try {
        const response = await apiClient.get("/projects");
        works.value = response.data.projects;
    } catch (error) {
        console.error("Error fetching works:", error);
    }
});

const showModal = ref(false);
const selectedWork = ref(null);

const openModal = (work) => {
    selectedWork.value = work;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedWork.value = null;
};
</script>

<template>
    <div class="flex-1 text-center mb-10">
        <h2 class="text-5xl font-extrabold text-white mb-10">Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
                v-for="(work, index) in works"
                :key="index"
                class="bg-gray-800 rounded-lg shadow-2xl p-3 transform transition duration-300 hover:scale-105 hover:bg-gray-700 cursor-pointer"
                @click="openModal(work)"
            >
                <h3 class="text-3xl font-bold text-white mb-4">
                    {{ work.title }}
                </h3>
                <div class="h-48 overflow-hidden rounded-lg mb-4">
                    <img
                        v-if="work.image"
                        :src="work.image"
                        alt="Work Image"
                        class="w-full h-full object-contain"
                    />
                </div>
            </div>
        </div>

        <!-- Modal -->
        <transition name="modal">
            <div
                v-if="showModal"
                class="fixed inset-0 flex items-center justify-center p-6 mt-14 bg-black bg-opacity-50"
                @click.self="closeModal"
            >
                <div
                    class="bg-gray-800 rounded-lg shadow-lg px-6 py-4 max-w-3xl w-full relative overflow-y-auto max-h-full"
                >
                    <button
                        @click="closeModal"
                        class="absolute top-2 right-4 text-gray-500 hover:text-gray-700"
                    >
                        &times;
                    </button>
                    <h3 class="text-3xl font-bold text-white mb-4">
                        {{ selectedWork.title }}
                    </h3>
                    <div class="w-full overflow-hidden rounded-lg mb-4">
                        <img
                            v-if="selectedWork.image"
                            :src="selectedWork.image"
                            alt="Work Image"
                            class="w-full h-full object-contain"
                        />
                    </div>
                    <p class="text-lg text-white text-justify">
                        {{ selectedWork.description }}
                    </p>
                </div>
            </div>
        </transition>
    </div>
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
