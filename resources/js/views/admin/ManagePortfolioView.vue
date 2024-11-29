<script setup>
import AdminView from "@/views/admin/AdminView.vue";
import Skills from "@/components/admin/Skills.vue";
import Introduction from "@/components/admin/Introduction.vue";
import ProfilePhoto from "@/components/admin/ProfilePhoto.vue";
import Project from "@/components/admin/Project.vue";
import About from "@/components/admin/About.vue";
</script>

<template>
    <AdminView>
        <div class="h-full">
            <main
                class="block w-full md:inline-block md:align-top md:w-[60%] p-4"
            >
                <!-- Main content goes here -->
                <Introduction />
                <Project />
            </main>
            <aside
                class="block w-full md:inline-block md:align-top md:w-[40%] p-4"
            >
                <Skills />
                <ProfilePhoto />
                <About />
            </aside>
        </div>
    </AdminView>
</template>

<style scoped>
/* Optional custom styling */
</style>
