<script setup>
import { defineProps, ref } from "vue";
import FuneralHome from "@/assets/images/works/funeralhome.png";
import Pharmacy from "@/assets/images/works/pharmacy.png";
import Quotation from "@/assets/images/works/quotation.png";

const props = defineProps({
    works: Array,
});

const works = ref([
    {
        title: "Funeral Home",
        description: `
      Developed a feature-rich website for a US-based funeral home to enhance lead generation and user experience. Utilized AWS, Cloudinary, Stripe, GitHub Copilot, Twilio, and SendGrid for various functionalities. Key contributions include payment integration, SMS notifications, email system, API development, and deployment on AWS EC2.
    `,
        image: FuneralHome,
    },
    {
        title: "Pharmacy Sales Analytics",
        description: `
      Built using Laravel, this project provides functionalities for user authentication, analytics management, media management, inventory management, sales tracking, and user profiles. It streamlines pharmacy operations by integrating various management tools into a single platform.
    `,
        image: Pharmacy,
    },
    {
        title: "Quotation Generator",
        description: `
      Developed using Laravel, this tool allows users to create quotations based on inputs, automatically calculates totals, and generates printable PDFs. It simplifies the process of managing and generating quotations for various services.
    `,
        image: Quotation,
    },
]);

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
