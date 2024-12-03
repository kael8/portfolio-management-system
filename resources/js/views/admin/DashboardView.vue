<script setup>
import AdminView from "@/views/admin/AdminView.vue";
import Profile from "@/components/Profile.vue";
import Skills from "@/components/Skills.vue";
import Works from "@/components/Works.vue";
import About from "@/components/About.vue";
import Contact from "@/components/Contact.vue";
</script>

<template>
    <AdminView>
        <div class="flex flex-col items-center justify-center min-h-screen">
            <div class="flex items-center justify-center w-full px-5 mb-10">
                <Profile />
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
