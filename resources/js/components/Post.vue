<template>
    <div
        v-if="posts.length > 0"
        class="flex flex-col w-full space-y-4 overflow-hidden"
    >
        <div
            v-for="post in posts"
            :key="post.id"
            :data-post-id="post.id"
            class="w-full bg-gray-800 md:rounded-lg shadow-2xl mb-6"
        >
            <div
                class="flex flex-col w-full pt-4 px-4 md:p-6 space-y-2 text-white"
            >
                <div class="flex items-center space-x-2">
                    <img
                        :src="state.profileImage"
                        alt="Author"
                        class="w-8 h-8 rounded-full object-cover"
                    />
                    <div>
                        <span class="font-bold">{{ post.user.name }}</span>
                        <span class="text-sm text-gray-400 pl-2">{{
                            formatPostDate(post.created_at)
                        }}</span>
                    </div>
                </div>

                <h2 class="text-xl md:text-2xl font-bold mt-2">
                    {{ post.title }}
                </h2>
                <p class="text-gray-300 mt-2">{{ post.content }}</p>

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
                                class="fixed-size-image rounded-lg"
                            />
                        </SwiperSlide>
                    </Swiper>
                </div>
                <div class="mt-4 flex space-x-4 text-white">
                    <div class="flex items-center space-x-2">
                        <div
                            v-for="reaction in reactions"
                            :key="reaction.id"
                            class="flex items-center space-x-1"
                        >
                            <span class="reaction-icon">{{
                                reaction.emoji
                            }}</span>
                            <span>{{
                                getReactionCount(
                                    post.reactions_summary,
                                    reaction.label
                                )
                            }}</span>
                        </div>
                    </div>
                </div>
                <hr class="my-4 border-gray-700" />
                <div class="mt-4 grid grid-cols-2 gap-4 text-white select-none">
                    <div class="react-container hover-highlight rounded-md">
                        <button
                            class="react-trigger-button"
                            @click="handleReactionClick(post)"
                        >
                            <i class="fas fa-thumbs-up"></i>
                            <span class="ml-2">Like</span>
                        </button>
                        <div class="reactions z-10">
                            <button
                                v-for="reaction in reactions"
                                :key="reaction.id"
                                :title="reaction.label"
                                @click="selectReaction(post, reaction.id)"
                                class="reaction-button"
                            >
                                <span>{{ reaction.emoji }}</span>
                            </button>
                        </div>
                    </div>
                    <div
                        class="flex justify-center items-center hover-highlight rounded-md"
                    >
                        <button
                            @click="toggleComments(post)"
                            class="text-white flex justify-center items-center w-full"
                        >
                            <i class="fas fa-comment fa-lg"></i>
                            <span class="ml-2">Comment</span>
                        </button>
                    </div>
                </div>
                <hr class="my-4 border-gray-700" />
                <!-- Comments Section -->
                <div v-if="post.showComments" class="mt-6">
                    <h3 class="text-xl font-bold mb-2 select-none">Comments</h3>
                    <div
                        v-for="comment in post.comments"
                        :key="comment.id"
                        class="mb-4 w-full flex items-start"
                    >
                        <img
                            :src="
                                comment.user.profile?.image_url ||
                                'https://upload.wikimedia.org/wikipedia/commons/7/7c/Profile_avatar_placeholder_large.png'
                            "
                            alt="Profile Picture"
                            class="w-10 h-10 rounded-full object-cover mt-1 mr-4"
                        />
                        <div class="flex-grow">
                            <div
                                class="bg-gray-900 p-4 rounded-2xl inline-block w-full"
                            >
                                <p class="text-sm">
                                    <strong>{{ comment.user.name }}</strong>
                                </p>
                                <p class="text-base">{{ comment.content }}</p>
                            </div>
                            <p class="text-xs mt-1 ml-4">
                                {{ formatDate(comment.created_at) }}
                            </p>
                        </div>
                    </div>

                    <!-- Add Comment Input -->
                    <div
                        class="mt-4 flex items-center space-x-2 p-2 rounded-full border border-gray-900 bg-gray-700"
                    >
                        <textarea
                            v-model="post.newComment"
                            placeholder="Write a comment..."
                            class="flex-grow p-2 bg-gray-700 resize-none rounded-full focus:outline-none focus:ring-0 border-none"
                        ></textarea>
                        <button
                            @click="addComment(post, post.newComment)"
                            class="px-4 py-2 text-white rounded-full focus:outline-none"
                        >
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div
        v-else
        class="flex flex-col w-full justify-center h-screen items-center"
    >
        <div class="w-full bg-gray-800 md:rounded-lg shadow-2xl mb-60 py-10">
            <div class="flex flex-col w-full pt-4 px-4 md:p-6 text-white">
                <h1 class="text-2xl font-bold text-center">No posts found</h1>
            </div>
        </div>
    </div>
    <LoginPromptModal
        :show="showLoginPrompt"
        @close="showLoginPrompt = false"
    />
    <SwitchHide
        v-if="authState.isAuthenticated"
        main-action-label="Open menu"
        main-action-icon="fas fa-plus"
        :actions="actions"
        @action="handleAction"
    />
</template>

<script setup>
import { ref, onMounted, nextTick } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination } from "swiper/modules"; // Import Pagination module
import "swiper/swiper-bundle.css"; // Import Swiper's styles
import apiClient from "@/services/apiClient";
import { formatDistanceToNow, parseISO } from "date-fns";
import { formatDistanceStrict } from "date-fns";
import { authState } from "@/services/auth";
import LoginPromptModal from "@/components/LoginPromptModal.vue";
import SwitchHide from "@/components/SwitchHide.vue";

const posts = ref([]);
const state = ref({
    profileImage: "https://via.placeholder.com/150",
    userId: null,
});
const storage = ref([]); // Define the storage variable
const showLoginPrompt = ref(false);

const actions = ref([]);

const labels = [
    { id: 0, label: "Show your name in comments", icon: "fas fa-user-secret" },
    { id: 1, label: "Hide your name in comments", icon: "fas fa-user-secret" },
];

const handleAction = async () => {
    try {
        const isPrivate = state.value.isPrivate ? 0 : 1;
        const response = await apiClient.post("/update-account", {
            isPrivate,
        });
        if (response.data) {
            fetchPost();
        }
    } catch (error) {
        console.error("Failed to update account:", error);
    }
};

const fetchPost = async () => {
    // Reset data

    state.value.profileImage = "https://via.placeholder.com/150";
    state.value.userId = null;
    state.value.isPrivate = 0;
    actions.value = [];

    try {
        const response = await apiClient.get("/get-posts");
        posts.value = [];
        posts.value = response.data.posts;
        state.value.profileImage = response.data.profileImage;
        state.value.userId = response.data.userId;
        state.value.isPrivate = response.data.isPrivate;

        // assign actions based on isPrivate and labels id
        labels.forEach((label) => {
            if (state.value.isPrivate === 1 && label.id === 0) {
                actions.value.push(label);
            } else if (state.value.isPrivate === 0 && label.id === 1) {
                actions.value.push(label);
            }
        });

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

defineExpose({
    fetchPost,
});

const updateAllReactions = async () => {
    posts.value.forEach((post) => {
        const reactionType = Number(post.user_reaction?.type);

        if (reactionType) {
            const reaction = reactions.find((r) => r.id === reactionType);

            if (reaction && Array.isArray(post.reactions)) {
                post.reactions.forEach((postReaction) => {
                    if (postReaction.label === reaction.label) {
                        postReaction.emoji = reaction.emoji;
                    }
                });
            }
        }
    });

    await nextTick(); // Ensure the DOM updates after the changes
};

onMounted(async () => {
    await fetchPost();
    await nextTick(); // Ensure the DOM updates after fetching posts
    updateAllReactions();
    setTimeout(updateAllReactions, 100); // Call updateAllReactions again after a short delay
});

const reactions = [
    { id: 1, label: "Heart", emoji: "❤️" },
    { id: 2, label: "Cool", emoji: "👍" },
    { id: 3, label: "Bad", emoji: "👎" },
];

const getReactionCount = (reactionsSummary, label) => {
    switch (label) {
        case "Heart":
            return reactionsSummary?.hearts_count || 0;
        case "Cool":
            return reactionsSummary?.cool_count || 0;
        case "Bad":
            return reactionsSummary?.bad_count || 0;
        default:
            return 0;
    }
};

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
    } finally {
        const post = posts.value.find((p) => p.id === postId);
        if (post) {
            post.showReactions = false; // Hide reactions after selection
        }
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

const selectReaction = (post, reactionId) => {
    if (!authState.isAuthenticated) {
        showLoginPrompt.value = true;
        return;
    }

    post.user_reaction = { type: reactionId }; // Ensure user_reaction is an object
    updateSelectedReactions([{ id: reactionId }], post);
    updateAllReactions();
};

const updateStorage = (storageArray) => {
    storage.value = storageArray;
};

const addComment = async (post, commentContent) => {
    if (!authState.isAuthenticated) {
        showLoginPrompt.value = true;
        return;
    }
    if (!commentContent.trim()) {
        alert("Comment cannot be empty!");
        return;
    }
    const response = await apiClient.post("/create-comment", {
        post_id: post.id,
        content: commentContent,
    });
    // Update the post comments from the response
    post.comments = response.data.comments;
    post.newComment = ""; // Clear the input field
};

const toggleComments = (post) => {
    post.showComments = !post.showComments;
};

const formatDate = (dateString) => {
    const clientTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    const zonedDate = new Date(dateString).toLocaleString("en-US", {
        timeZone: clientTimeZone,
    });
    const distance = formatDistanceStrict(new Date(zonedDate), new Date(), {
        addSuffix: false,
    });

    // Custom formatting
    return distance
        .replace(" years", "y")
        .replace(" year", "y")
        .replace(" months", "m")
        .replace(" month", "m")
        .replace(" weeks", "w")
        .replace(" week", "w")
        .replace(" days", "d")
        .replace(" day", "d")
        .replace(" hours", "h")
        .replace(" hour", "h")
        .replace(" minutes", "m")
        .replace(" minute", "m")
        .replace(" seconds", "s")
        .replace(" second", "s");
};

const formatPostDate = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = (now - date) / 1000;

    if (diffInSeconds < 60) {
        return "Just now";
    } else if (diffInSeconds < 3600) {
        return `${Math.floor(diffInSeconds / 60)}m`;
    } else if (diffInSeconds < 86400) {
        return `${Math.floor(diffInSeconds / 3600)}h`;
    } else if (diffInSeconds < 604800) {
        return `${Math.floor(diffInSeconds / 86400)}d`;
    } else if (diffInSeconds < 31536000) {
        return format(date, "MMMM d 'at' h:mm a");
    } else {
        return format(date, "MMMM d, yyyy 'at' h:mm a");
    }
};
</script>

<style scoped>
.fixed-size-image {
    width: 100%;
    height: 400px; /* Adjust the height as needed */
    object-fit: cover; /* Maintain aspect ratio */
}

.reaction-icon {
    font-size: 1rem; /* Adjust the size as needed */
    margin-right: 0.5rem; /* Space between icon and count */
}

.large-reactions {
    font-size: 5rem; /* Adjust the size as needed */
}

.react-container {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    padding: 10px; /* Add padding to make the hover effect more noticeable */
    position: relative; /* Ensure the reactions are positioned relative to this container */
}

.react-trigger-button {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.5rem;
    color: white;
    display: flex;
    align-items: center;
}

.reactions {
    display: none;
    position: absolute;
    bottom: 100%; /* Position above the trigger button */
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.8);
    padding: 10px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.react-container:hover .reactions {
    display: flex;
}

.reaction-button {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 2rem;
    margin: 0 0.5rem;
    transition: transform 0.3s ease;
}

.reaction-button:hover {
    transform: scale(1.2);
}

.hover-highlight:hover {
    background-color: rgba(255, 255, 255, 0.1); /* Highlight color */
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
    background-color: #1f2937; /* Highlight color for active bullet */
    width: 25px; /* Optional: Increase width for active bullet */
    height: 7px; /* Optional: Increase height for active bullet */
}
</style>
