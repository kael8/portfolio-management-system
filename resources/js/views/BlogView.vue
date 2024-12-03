<script setup>
import Navbar from "@/components/Navbar.vue";
import Post from "@/components/Post.vue";
</script>

<template>
    <Navbar />
    <div class="items-center justify-center min-h-screen">
        <h1 class="text-4xl font-bold text-center text-white p-2">Blog</h1>
        <div class="mx-auto w-full md:w-1/2">
            <Post />
        </div>
    </div>
</template>
