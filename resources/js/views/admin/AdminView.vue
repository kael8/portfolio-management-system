<script setup>
import Navbar from "@/components/admin/Navbar.vue";
import VerticalNavbar from "@/components/admin/VerticalNavbar.vue";
import { ChevronRightIcon } from "@heroicons/vue/24/solid";
import { ref, onMounted, onUnmounted } from "vue";

const isNavbarVisible = ref(false);
const windowWidth = ref(window.innerWidth);

const toggleNavbar = () => {
    isNavbarVisible.value = !isNavbarVisible.value;
};

const updateWindowWidth = () => {
    windowWidth.value = window.innerWidth;
};

onMounted(() => {
    window.addEventListener("resize", updateWindowWidth);
});

onUnmounted(() => {
    window.removeEventListener("resize", updateWindowWidth);
});
</script>

<template>
    <div>
        <Navbar />
        <button
            @click="toggleNavbar"
            :class="{
                'left-64': isNavbarVisible,
                'left-4': !isNavbarVisible,
            }"
            class="fixed top-4 z-10 p-2 bg-gray-800 text-white rounded-md md:hidden transition-all duration-300"
        >
            <ChevronRightIcon
                class="w-6 h-6 transition-transform duration-300"
                :class="{
                    'rotate-180': isNavbarVisible,
                    'rotate-0': !isNavbarVisible,
                }"
            />
        </button>
        <div>
            <VerticalNavbar :isNavbarVisible="isNavbarVisible" />
            <div
                :class="{
                    'ml-64': windowWidth >= 768,
                    'ml-0': windowWidth < 768,
                }"
                class="flex-1 mt-20 transition-all duration-300"
            >
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Optional custom styling */
</style>
