<template>
    <div class="speed-dial" :class="{ 'is-active': isOpen }">
        <!-- Main FAB button -->
        <button
            class="speed-dial-trigger"
            @click="toggle"
            :aria-label="mainActionLabel"
        >
            <i :class="mainActionIcon"></i>
        </button>

        <!-- Action buttons -->
        <div class="speed-dial-actions" :class="{ 'is-open': isOpen }">
            <button
                v-for="action in actions"
                :key="action.id"
                class="speed-dial-action"
                @click="handleAction(action)"
                :aria-label="action.label"
            >
                <span class="action-tooltip p-2 text-lg bg-gray-950">{{
                    action.label
                }}</span>
                <i :class="action.icon"></i>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    mainActionLabel: {
        type: String,
        default: "Toggle Actions",
    },
    mainActionIcon: {
        type: String,
        default: "fas fa-plus",
    },
    actions: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["action"]);
const isOpen = ref(false);

const toggle = () => {
    isOpen.value = !isOpen.value;
};

const handleAction = (action) => {
    emit("action", action);
    isOpen.value = false;
};
</script>

<style scoped>
.speed-dial {
    position: fixed;
    bottom: 1rem;
    right: 2rem;
    z-index: 50;
}

.speed-dial-trigger {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 50%;
    background-color: #2563eb;
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.is-active .speed-dial-trigger {
    transform: rotate(45deg);
}

.speed-dial-actions {
    position: absolute;
    bottom: 100%;
    right: 0;
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column-reverse;
    gap: 0.75rem;
    opacity: 0;
    transform: translateY(10px);
    pointer-events: none;
    transition: all 0.2s;
}

.speed-dial-actions.is-open {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

.speed-dial-action {
    width: 2.75rem;
    height: 2.75rem;
    border-radius: 50%;
    background-color: #4b5563;
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.action-tooltip {
    position: absolute;
    right: 100%;
    top: 50%;
    transform: translateY(-50%);
    margin-right: 0.75rem;

    color: white;
    border-radius: 0.375rem;

    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.2s;
}

.speed-dial-action:hover .action-tooltip {
    opacity: 1;
}
</style>
