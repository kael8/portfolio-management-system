<template>
    <div class="background">
        <svg
            class="wisp-container"
            xmlns="http://www.w3.org/2000/svg"
            :viewBox="`0 0 ${windowWidth} ${windowHeight}`"
        >
            <!-- Individual wisps -->
            <circle
                v-for="(wisp, index) in wisps"
                :key="index"
                :cx="wisp.x"
                :cy="wisp.y"
                :r="wisp.size"
                class="wisp"
                :ref="'wisp-' + index"
            />
        </svg>
    </div>
</template>

<script>
import anime from "animejs/lib/anime.es.js";

export default {
    name: "FloatingWisps",
    data() {
        return {
            windowWidth: window.innerWidth,
            windowHeight: window.innerHeight,
            wisps: Array.from({ length: 30 }, () => ({
                x: Math.random() * window.innerWidth, // Random starting X position
                y: Math.random() * window.innerHeight, // Random starting Y position
                size: 10 + Math.random() * 5, // Smaller size for variation
            })),
        };
    },
    mounted() {
        this.animateWisps();
        window.addEventListener("resize", this.updateWindowSize);
    },
    beforeDestroy() {
        window.removeEventListener("resize", this.updateWindowSize);
    },
    methods: {
        updateWindowSize() {
            this.windowWidth = window.innerWidth;
            this.windowHeight = window.innerHeight;
        },
        animateWisps() {
            this.wisps.forEach((wisp, index) => {
                anime({
                    targets: this.$refs[`wisp-${index}`],
                    translateX: () =>
                        anime.random(
                            -this.windowWidth / 2,
                            this.windowWidth / 2
                        ),
                    translateY: () =>
                        anime.random(
                            -this.windowHeight / 2,
                            this.windowHeight / 2
                        ),
                    duration: 5000 + anime.random(2000, 8000), // Random duration for each wisp
                    easing: "easeInOutSine",
                    direction: "alternate",
                    loop: true,
                });
            });
        },
    },
};
</script>

<style scoped>
.background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: radial-gradient(circle at center, #0b1c2c, #020b12);
    overflow: hidden;
    z-index: -1;
}

.wisp-container {
    width: 100%;
    height: 100%;
    fill: none;
    pointer-events: none; /* Prevents any interaction with the wisps */
}

/* Wisp Appearance */
.wisp {
    fill: rgba(100, 240, 255, 0.6); /* Light blue glow */
    opacity: 0.8;
    animation: pulse 5s infinite alternate; /* Simple pulsing animation */
}

@keyframes pulse {
    0% {
        opacity: 0.5;
        r: 10;
    }
    100% {
        opacity: 0.9;
        r: 12;
    }
}
</style>
