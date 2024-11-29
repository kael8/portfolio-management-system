<script setup>
import { reactive, onMounted, ref } from "vue";
import { PlusIcon, TrashIcon, CheckIcon } from "@heroicons/vue/24/outline";
import apiClient from "@/services/apiClient";
import { useToast } from "vue-toastification";

const state = reactive({
    skills: [],
});

onMounted(async () => {
    const fetchSkills = async () => {
        try {
            const response = await apiClient.get("/skills");

            state.skills = response.data.skills.map((skill) => ({
                id: skill.id,
                name: skill.name,
                type: skill.type,
                filteredNames: [],
            }));
        } catch (error) {
            console.error("Error fetching skills:", error);
        }
    };

    fetchSkills();
});
const toast = useToast();

const names = reactive([
    "HTML",
    "CSS",
    "JavaScript",
    "Vue.js",
    "React",
    "Angular",
    "Svelte",
    "Node.js",
    "Express",
    "Laravel",
    "Symfony",
    "Django",
    "Flask",
    "Spring",
    "Hibernate",
    "JPA",
    "MySQL",
    "PostgreSQL",
    "MongoDB",
    "SQLite",
    "RESTful APIs",
    "GraphQL",
    "Stripe",
    "Twilio",
    "SendGrid",
    "Git",
    "GitHub",
    "GitLab",
    "Bitbucket",
    "VS Code",
    "Sublime Text",
    "Atom",
    "WebStorm",
    "Postman",
    "Insomnia",
    "AWS (EC2, S3)",
    "Azure",
    "Google Cloud",
    "Heroku",
    "Cloudinary",
    "Netlify",
    "Vercel",
    "Firebase",
    "Docker",
    "Kubernetes",
    "Jenkins",
    "Travis CI",
    "CircleCI",
    "Netlify CI",
    "GitHub Actions",
    "Selenium",
    "Cypress",
    "Jest",
    "Mocha",
    "Chai",
    "PHPUnit",
    "Jasmine",
    "Karma",
    "OWASP Top 10",
    "Cryptography",
    "Penetration Testing",
    "Security Auditing",
    "React Native",
]);

const nameToType = {
    HTML: "Frontend",
    CSS: "Frontend",
    JavaScript: "Frontend",
    "Vue.js": "Frontend",
    React: "Frontend",
    Angular: "Frontend",
    Svelte: "Frontend",
    "Node.js": "Backend",
    Express: "Backend",
    Laravel: "Backend",
    Symfony: "Backend",
    Django: "Backend",
    Flask: "Backend",
    Spring: "Backend",
    Hibernate: "Backend",
    JPA: "Backend",
    MySQL: "Database",
    PostgreSQL: "Database",
    MongoDB: "Database",
    SQLite: "Database",
    "RESTful APIs": "Backend",
    GraphQL: "Backend",
    Stripe: "Tools",
    Twilio: "Tools",
    SendGrid: "Tools",
    Git: "Tools",
    GitHub: "Tools",
    GitLab: "Tools",
    Bitbucket: "Tools",
    "VS Code": "Tools",
    "Sublime Text": "Tools",
    Atom: "Tools",
    WebStorm: "Tools",
    Postman: "Tools",
    Insomnia: "Tools",
    "AWS (EC2, S3)": "Cloud",
    Azure: "Cloud",
    "Google Cloud": "Cloud",
    Heroku: "Cloud",
    Cloudinary: "Cloud",
    Netlify: "Cloud",
    Vercel: "Cloud",
    Firebase: "Cloud",
    Docker: "DevOps",
    Kubernetes: "DevOps",
    Jenkins: "DevOps",
    "Travis CI": "DevOps",
    CircleCI: "DevOps",
    "Netlify CI": "DevOps",
    "GitHub Actions": "DevOps",
    Selenium: "Testing",
    Cypress: "Testing",
    Jest: "Testing",
    Mocha: "Testing",
    Chai: "Testing",
    PHPUnit: "Testing",
    Jasmine: "Testing",
    Karma: "Testing",
    "OWASP Top 10": "Security",
    Cryptography: "Security",
    "Penetration Testing": "Security",
    "Security Auditing": "Security",
    "React Native": "Mobile",
};

const addSkill = () => {
    state.skills.push({
        id: "",
        name: "",
        type: "",
        filteredNames: [],
    });
};

const removeSkill = (id) => {
    state.skills = state.skills.filter((skill) => skill.id !== id);
};

const submitSkills = async () => {
    const validSkills = state.skills.filter(
        (skill) => skill.name.trim() !== "" && skill.type.trim() !== ""
    );
    if (validSkills.length === 0) {
        alert("Please add at least one valid skill.");
        return;
    }
    console.log("Submitted skills:", validSkills);
    try {
        const response = await apiClient.post("/skills", validSkills);
        console.log(response.data);
        toast.success("Skills submitted successfully");
        const fetchSkills = async () => {
            try {
                const response = await apiClient.get("/skills");

                state.skills = response.data.skills.map((skill) => ({
                    id: skill.id,
                    name: skill.name,
                    type: skill.type,
                    filteredNames: [],
                }));
            } catch (error) {
                console.error("Error fetching skills:", error);
            }
        };

        fetchSkills();
    } catch (error) {
        console.error("Error submitting skills:", error);
        toast.error("Failed to submit skills");
    }
};

const filteredNames = (input) => {
    return names.filter((name) =>
        name.toLowerCase().includes(input.toLowerCase())
    );
};
</script>

<template>
    <!--Manage Skills-->
    <div
        class="flex flex-col w-full h-full items-center rounded-md bg-gray-800 text-white justify-center p-6 mb-8"
    >
        <h1 class="text-4xl font-bold mb-6">Skills</h1>

        <div class="w-full max-w-md">
            <div
                v-for="skill in state.skills"
                :key="skill.id"
                class="relative flex items-center mb-2 space-x-2"
            >
                <input
                    v-model="skill.name"
                    type="text"
                    placeholder="Skill name"
                    class="flex-1 p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                    @input="skill.filteredNames = filteredNames(skill.name)"
                />
                <div
                    v-if="skill.filteredNames && skill.filteredNames.length"
                    class="absolute top-full left-0 right-0 bg-gray-700 rounded-md z-10"
                >
                    <div
                        v-for="(name, index) in skill.filteredNames"
                        :key="index"
                        class="p-2 hover:bg-gray-600 cursor-pointer"
                        @click="
                            skill.name = name;
                            skill.type = nameToType[name];
                            skill.filteredNames = [];
                        "
                    >
                        {{ name }}
                    </div>
                </div>
                <button
                    @click="removeSkill(skill.id)"
                    class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-md"
                >
                    <TrashIcon class="h-5 w-5" />
                </button>
            </div>
            <button
                @click="addSkill"
                class="w-full p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md mb-4 flex items-center justify-center space-x-2"
            >
                <PlusIcon class="h-5 w-5" />
                <span>Add Skill</span>
            </button>
            <button
                @click="submitSkills"
                class="w-full p-3 bg-green-600 hover:bg-green-700 text-white rounded-md flex items-center justify-center space-x-2"
            >
                <CheckIcon class="h-5 w-5" />
                <span>Submit</span>
            </button>
        </div>
    </div>
</template>

<style scoped>
/* Optional custom styling */
</style>
