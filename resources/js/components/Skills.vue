<script setup>
import { defineProps } from "vue";
import { ref } from "vue";

const props = defineProps({
    frontend: Array,
    backend: Array,
    tools: Array,
});

const skills = ref([
    {
        title: "Frontend",
        items: [
            "HTML5",
            "CSS3",
            "JavaScript",
            "Vue.js",
            "Bootstrap",
            "Tailwind CSS",
        ],
    },
    {
        title: "Backend",
        items: [
            "PHP",
            "Laravel",
            "MySQL",

            "RESTful APIs",
            ,
            "Stripe",
            "Twilio",
            "SendGrid",
        ],
    },
    {
        title: "Tools & Cloud",
        items: [
            "Git",
            "GitHub",
            "VS Code",
            "Postman",
            "AWS (EC2, S3)",
            "Cloudinary",
            "Netlify",
        ],
    },
]);
</script>

<template>
    <div class="flex-1 text-center mb-10">
        <h2 class="text-5xl font-extrabold text-white mb-10">Skills</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
                v-for="(skill, index) in skills"
                :key="index"
                class="bg-gray-800 rounded-lg shadow-2xl p-6 transform transition duration-300 hover:scale-105 hover:bg-gray-700"
            >
                <h3 class="text-3xl font-bold text-white mb-4">
                    {{ skill.title }}
                </h3>
                <ul class="text-lg text-gray-300">
                    <li
                        v-for="(item, index) in skill.items"
                        :key="index"
                        class="mb-2 hover:text-white transition-colors duration-200"
                    >
                        {{ item }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
