<script setup>
import { RouterLink } from "vue-router";
</script>

<template>
    <section class="text-center flex flex-col justify-center items-center h-96">
        <div
            class="card bg-highlight/80 rounded-2xl backdrop-blur-md shadow-lg"
        >
            <div class="card-body p-6">
                <i
                    class="pi pi-exclamation-triangle text-yellow-500/90 text-7xl mb-5"
                ></i>
                <h1 class="text-6xl font-bold text-white/90 mb-4">
                    404 Not Found
                </h1>
                <p class="text-xl text-white/80 mb-5">
                    This page does not exist
                </p>
                <RouterLink
                    to="/"
                    class="bg-secondary/90 hover:bg-highlight/30 text-white/80 px-3 py-2 rounded-md text-white"
                >
                    Go Back
                </RouterLink>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Remove the need for custom round class */
</style>
