<script setup>
import { ref } from "vue";

const info = ref({
    education: "",
});
</script>

<template>
    <div
        class="flex flex-col w-full h-full items-center rounded-md bg-gray-800 text-white justify-center p-6"
    >
        <h1 class="text-4xl font-bold mb-6">About</h1>
        <!-- Education -->
        <div class="w-full mb-4">
            <label
                for="education"
                class="text-sm font-medium text-gray-300 mb-1"
                >Education</label
            >
            <input
                v-model="info.education"
                id="education"
                name="education"
                type="text"
                class="w-full p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                placeholder="Education"
            />
        </div>
    </div>
</template>
