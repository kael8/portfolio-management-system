<template>
    <div class="reactions">
        <button
            v-for="reaction in reactions"
            :key="reaction.id"
            :title="reaction.label"
            @click="updateReaction(reaction.id)"
            class="reaction-button"
        >
            <span>{{ reaction.emoji }}</span>
        </button>
    </div>
</template>

<script>
export default {
    props: {
        post: Object,
        reactions: Array,
    },
    methods: {
        updateReaction(reactionId) {
            this.$emit("update-reaction", this.post.id, reactionId);
        },
    },
};
</script>

<style scoped>
.reaction-button {
    background: none;
    border: none;
    cursor: pointer;
}
</style>
