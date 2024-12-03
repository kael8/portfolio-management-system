<script setup>
import { ref, onMounted } from "vue";
import VueReactions from "@webzlodimir/vue-reactions";
import "@webzlodimir/vue-reactions/dist/style.css";
import Image from "@/assets/images/arcayl.jpg";
import apiClient from "@/services/apiClient";

// List of posts
const posts = ref([]);

onMounted(async () => {
    try {
        const response = await apiClient.get("/posts");
        posts.value = response.data.posts;
    } catch (error) {
        console.error("Failed to fetch posts:", error);
    }
});

const reactions = [
    { id: 1, label: "Heart", emoji: "❤️" },
    { id: 2, label: "Cool", emoji: "👍" },
    { id: 3, label: "Bad", emoji: "👎" },
];

const updateReactionsCount = (selectedReactions, post) => {
    post.reactions = { heart: 0, cool: 0, bad: 0 };

    const reactionLabels = {
        1: "heart",
        2: "cool",
        3: "bad",
    };

    selectedReactions.forEach((reaction) => {
        if (reaction?.id && reactionLabels[reaction.id]) {
            const label = reactionLabels[reaction.id].toLowerCase();
            post.reactions[label] += 1;
        }
    });
};

const updateSelectedReactions = (newReactions, post) => {
    post.selectedReactions = newReactions;
    updateReactionsCount(newReactions, post);
};

const addComment = (post, commentContent) => {
    if (!commentContent.trim()) {
        alert("Comment cannot be empty!");
        return;
    }
    const newComment = {
        id: Date.now(),
        author: "Anonymous",
        content: commentContent,
        date: new Date().toLocaleString(),
    };
    post.comments.push(newComment);
    post.newComment = ""; // Clear the input field
};

const toggleComments = (post) => {
    post.showComments = !post.showComments;
};
</script>

<template>
    <div class="flex flex-col w-full mb-10 space-y-4">
        <div
            v-for="post in posts"
            :key="post.id"
            class="w-full bg-gray-800 rounded-lg shadow-2xl mb-6"
        >
            <div class="flex flex-col w-full p-6 space-y-4 text-white">
                <!-- Post Author and Title -->
                <div class="flex items-center space-x-2">
                    <img
                        :src="Image"
                        alt="Author"
                        class="w-8 h-8 rounded-full"
                    />
                    <div>
                        <span class="font-bold">{{ post.author }}</span>
                        <span class="text-sm text-gray-400 pl-2">{{
                            post.date
                        }}</span>
                    </div>
                </div>

                <h2 class="text-2xl font-bold mt-2">{{ post.title }}</h2>

                <!-- Post Image -->
                <div class="relative w-full h-64 mt-4">
                    <img
                        :src="post.image"
                        alt="Blog Post Image"
                        class="w-full h-full object-cover rounded-lg"
                    />
                </div>

                <!-- Post Content -->
                <p class="mt-4">{{ post.content }}</p>

                <!-- Reactions -->
                <div class="mt-4 flex space-x-4 text-white">
                    <vue-reactions
                        :model-value="post.selectedReactions"
                        :reactions="reactions"
                        :storage="post.storage"
                        has-dropdown
                        @update:modelValue="
                            (newReactions) =>
                                updateSelectedReactions(newReactions, post)
                        "
                    />
                    <div class="flex space-x-2">
                        <span>Heart: {{ post.reactions.heart }}</span>
                        <span>Cool: {{ post.reactions.cool }}</span>
                        <span>Bad: {{ post.reactions.bad }}</span>
                    </div>
                </div>

                <!-- Comment Section Toggle -->
                <button
                    @click="toggleComments(post)"
                    class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg"
                >
                    {{ post.showComments ? "Hide Comments" : "View Comments" }}
                </button>

                <!-- Comments Section -->
                <div v-if="post.showComments" class="mt-6">
                    <h3 class="text-xl font-bold mb-2">Comments</h3>
                    <div
                        v-for="comment in post.comments"
                        :key="comment.id"
                        class="mb-4"
                    >
                        <p class="text-sm">
                            <strong>{{ comment.author }}</strong> -
                            {{ comment.date }}
                        </p>
                        <p class="text-base">{{ comment.content }}</p>
                    </div>

                    <!-- Add Comment Input -->
                    <div class="mt-4">
                        <textarea
                            v-model="post.newComment"
                            placeholder="Write a comment..."
                            class="w-full p-2 rounded-lg border border-gray-900 bg-gray-700"
                        ></textarea>
                        <button
                            @click="addComment(post, post.newComment)"
                            class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg"
                        >
                            Add Comment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
