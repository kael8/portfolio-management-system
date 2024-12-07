<script setup>
import Navbar from "@/components/Navbar.vue";
import Background from "@/components/Background.vue";
import { RouterView } from "vue-router";
import { onMounted } from "vue";
import FingerprintJS from "@fingerprintjs/fingerprintjs";
import apiClient from "@/services/apiClient";

const logVisitor = async () => {
    // Initialize FingerprintJS
    const fp = await FingerprintJS.load();

    // Get the visitor identifier
    const result = await fp.get();
    const visitorId = result.visitorId;

    // Send the visitor ID to the backend
    apiClient
        .post("/log-visitor", { visitorId })
        .then((response) => {
            console.log(response.data.message);
        })
        .catch((error) => {
            console.error("Error logging visitor:", error);
        });
};

// Log visitor when the component is mounted
onMounted(() => {
    logVisitor();
});
</script>

<template>
    <Background />
    <RouterView />
</template>
