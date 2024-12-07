<template>
    <transition name="modal">
        <div
            v-if="show"
            class="fixed inset-0 flex items-center justify-center z-50"
        >
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="bg-gray-800 rounded-lg p-6 z-10 text-white">
                <h2 class="text-xl font-bold mb-4">Login Required</h2>
                <p class="mb-4">You need to log in to perform this action.</p>
                <div class="flex justify-end">
                    <button
                        @click="close"
                        class="mr-4 px-4 py-2 bg-gray-300 rounded text-black"
                    >
                        Cancel
                    </button>
                    <router-link
                        to="/auth/login"
                        class="px-4 py-2 bg-blue-500 text-white rounded"
                        >Login</router-link
                    >
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(["close"]);

const close = () => {
    emit("close");
};
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.5s, transform 0.5s;
}
.modal-enter-from, .modal-leave-to /* .modal-leave-active in <2.1.8 */ {
    opacity: 0;
    transform: scale(0.9);
}
.modal-enter-to,
.modal-leave-from {
    opacity: 1;
    transform: scale(1);
}
</style>
