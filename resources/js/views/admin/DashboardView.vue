<script setup>
import AdminView from "@/views/admin/AdminView.vue";
import Navbar from "@/components/Navbar.vue";
import Profile from "@/components/Profile.vue";
import Skills from "@/components/Skills.vue";
import Works from "@/components/Works.vue";
import About from "@/components/About.vue";
import Contact from "@/components/Contact.vue";
import userImage from "@/assets/images/arcayl.jpg";
// Define your name and image URL
const userName = "Arcayl T. Maisog";
</script>

<template>
    <AdminView>
        <div class="flex flex-col items-center justify-center min-h-screen">
            <div class="flex items-center justify-center w-full px-5 mb-10">
                <Profile :userName="userName" :userImage="userImage" />
            </div>
            <div class="flex items-center justify-center w-full px-5">
                <Skills />
            </div>
            <div class="flex items-center justify-center w-full px-5">
                <Works />
            </div>
            <div class="flex items-center justify-center w-full px-5">
                <About />
            </div>
            <div class="flex items-center justify-center w-full">
                <Contact />
            </div>
        </div>
    </AdminView>
</template>
