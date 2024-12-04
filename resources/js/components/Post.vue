<template>
    <div class="flex flex-col w-full mb-10 space-y-4">
        <div
            v-for="post in posts"
            :key="post.id"
            class="w-full bg-gray-800 md:rounded-lg shadow-2xl mb-6"
        >
            <div class="flex flex-col w-full p-6 space-y-4 text-white">
                <div class="flex items-center space-x-2">
                    <img
                        :src="state.profileImage"
                        alt="Author"
                        class="w-8 h-8 rounded-full object-cover"
                    />
                    <div>
                        <span class="font-bold">{{ post.user.name }}</span>
                        <span class="text-sm text-gray-400 pl-2">{{
                            new Date(post.created_at).toLocaleString()
                        }}</span>
                    </div>
                </div>

                <h2 class="text-2xl font-bold mt-2">{{ post.title }}</h2>

                <div class="relative w-full mt-4">
                    <Swiper
                        :modules="[Navigation, Pagination]"
                        :space-between="10"
                        :slides-per-view="1"
                        navigation
                        :pagination="{ clickable: true }"
                        loop
                    >
                        <SwiperSlide
                            v-for="image in post.images"
                            :key="image.id"
                        >
                            <img
                                :src="image.image_url"
                                loading="lazy"
                                alt="Post Image"
                                class="w-full h-full object-cover rounded-lg mb-4"
                            />
                        </SwiperSlide>
                    </Swiper>
                </div>

                <p class="mt-4">{{ post.content }}</p>

                <div class="mt-4 flex space-x-4 text-white">
                    <vue-reactions
                        :model-value="post.selectedReactions"
                        :reactions="reactions"
                        has-dropdown
                        :storage="post.storage"
                        @update:modelValue="
                            updateSelectedReactions($event, post)
                        "
                        @update:storage="updateStorage"
                    />
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center space-x-1">
                            <span class="reaction-icon">❤️</span>
                            <span>{{
                                post.reactions_summary?.hearts_count || 0
                            }}</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <span class="reaction-icon">👍</span>
                            <span>{{
                                post.reactions_summary?.cool_count || 0
                            }}</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <span class="reaction-icon">👎</span>
                            <span>{{
                                post.reactions_summary?.bad_count || 0
                            }}</span>
                        </div>
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

<script setup>
import { ref, onMounted } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination } from "swiper/modules"; // Import Pagination module
import "swiper/swiper-bundle.css"; // Import Swiper's styles
import VueReactions from "@webzlodimir/vue-reactions";
import "@webzlodimir/vue-reactions/dist/style.css";
import apiClient from "@/services/apiClient";

const posts = ref([]);
const state = ref({
    profileImage: "https://via.placeholder.com/150",
    userId: null,
});
const storage = ref([]); // Define the storage variable

const fetchPost = async () => {
    // Reset data

    state.value.profileImage = "https://via.placeholder.com/150";
    state.value.userId = null;

    try {
        const response = await apiClient.get("/get-posts");
        posts.value = [];
        posts.value = response.data.posts;
        state.value.profileImage = response.data.profileImage;
        state.value.userId = response.data.userId;

        posts.value.forEach((post) => {
            post.reactions = { heart: 0, cool: 0, bad: 0 }; // Reset reactions
            post.selectedReactions = []; // Reset selected reactions
            post.comments = post.comments || []; // Default to an empty array
            post.userReactions = {}; // Reset user reactions
            post.storage = []; // Reset storage
            post.reactions_summary = post.reactions_summary || {
                hearts_count: 0,
                cool_count: 0,
                bad_count: 0,
            }; // Ensure reactions_summary is initialized
        });
    } catch (error) {
        console.error("Failed to fetch posts:", error);
    }
};

onMounted(async () => {
    fetchPost();
});

const reactions = [
    { id: 1, label: "Heart", emoji: "❤️" },
    { id: 2, label: "Cool", emoji: "👍" },
    { id: 3, label: "Bad", emoji: "👎" },
];

const updateReactionsCount = (newReactions, post, currentUserId) => {
    const reactionLabels = { 1: "heart", 2: "cool", 3: "bad" };

    // Reset reactions count
    post.reactions = { heart: 0, cool: 0, bad: 0 };

    // Count reactions
    newReactions.forEach((reaction) => {
        if (reaction?.id && reactionLabels[reaction.id]) {
            const label = reactionLabels[reaction.id];
            if (!post.reactions[label]) post.reactions[label] = 0;
            post.reactions[label] += 1;
        }
    });

    // Update the user's reaction
    post.userReactions[currentUserId] = newReactions[0]?.id;
};

const submitReaction = async (postId, reactionId) => {
    try {
        const response = await apiClient.post("/reaction", {
            post_id: postId,
            reaction_id: reactionId,
        });

        // Update the post reactions locally to reflect the change immediately
        const post = posts.value.find((p) => p.id === postId);
        if (post) {
            const reactionSummary = response.data.reaction_summary;
            post.reactions = {
                heart: reactionSummary.hearts_count,
                cool: reactionSummary.cool_count,
                bad: reactionSummary.bad_count,
            };
            post.reactions_summary = {
                hearts_count: reactionSummary.hearts_count,
                cool_count: reactionSummary.cool_count,
                bad_count: reactionSummary.bad_count,
            };
        }
    } catch (error) {
        console.error("Failed to submit reaction:", error);
    }
};

const updateSelectedReactions = (newReactions, post) => {
    const currentUserId = state.value.userId; // Get current user's ID

    // Remove the previous reaction if it exists
    if (post.userReactions[currentUserId]) {
        const previousReactionId = post.userReactions[currentUserId];
        const previousReactionIndex = post.selectedReactions.findIndex(
            (reaction) => reaction.id === previousReactionId
        );
        if (previousReactionIndex !== -1) {
            post.selectedReactions.splice(previousReactionIndex, 1);
        }
    }

    // Add the new reaction
    post.selectedReactions = newReactions;

    updateReactionsCount(newReactions, post, currentUserId);

    // Submit the reaction to the server
    if (newReactions.length > 0) {
        submitReaction(post.id, newReactions[0].id);
    }
};

const updateStorage = (storageArray) => {
    console.log("Updated storage:", storageArray);
    storage.value = storageArray;
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

<style scoped>
.reaction-icon {
    font-size: 1.5rem; /* Adjust the size as needed */
    margin-right: 0.5rem; /* Space between icon and count */
}

:deep(.swiper-pagination) {
    position: absolute;
    bottom: 30px; /* Adjust the vertical position */
    left: 50%;
    transform: translateX(-50%);
    display: flex; /* Align bullets horizontally */
    justify-content: center; /* Center bullets horizontally */
    gap: 5px; /* Space between each bullet */
    width: auto; /* Allow pagination width to adapt to number of bullets */
}

:deep(.swiper-pagination-bullet) {
    width: 20px; /* Width of each bullet */
    height: 5px; /* Height of the bars */
    background-color: #ccc; /* Background color for inactive bullets */
    border-radius: 3px; /* Optional: Round the edges of each bullet */
    transition: background-color 0.3s ease; /* Smooth transition for color change */
}

:deep(.swiper-pagination-bullet-active) {
    background-color: #ff6347; /* Highlight color for active bullet */
    width: 25px; /* Optional: Increase width for active bullet */
    height: 7px; /* Optional: Increase height for active bullet */
}
</style>
