<script setup>
import { RouterLink, useRoute } from "vue-router";

const route = useRoute();

const isActiveLink = (routePath) => route.path === routePath;
</script>

<template>
    <nav class="border-b border-secondary/60 backdrop-blur-md">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between">
                <div
                    class="flex flex-1 items-center justify-center md:items-stretch md:justify-start"
                >
                    <!-- Logo -->
                    <RouterLink
                        class="flex flex-shrink-0 items-center mr-4"
                        to="/"
                    >
                        <span
                            class="hidden md:block text-highlight/90 text-2xl font-bold ml-2"
                        >
                            Portfolio
                        </span>
                    </RouterLink>

                    <!-- Navigation Links -->
                    <div class="md:ml-auto">
                        <div class="flex space-x-2">
                            <!-- Home Link -->
                            <RouterLink
                                to="/"
                                :class="[
                                    isActiveLink('/')
                                        ? 'bg-secondary/90'
                                        : 'hover:bg-highlight/30 ',
                                    'text-white/80 px-3 py-2 rounded-md text-white',
                                ]"
                            >
                                Home
                            </RouterLink>

                            <!-- Login Link -->
                            <RouterLink
                                to="/auth/login"
                                :class="[
                                    isActiveLink('/auth/login')
                                        ? 'bg-secondary/90 text-white'
                                        : 'hover:bg-highlight/30 hover:text-primary/80',
                                    'text-white/80 px-3 py-2 rounded-md',
                                ]"
                            >
                                Login
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>
/* Optional custom styling */
</style>
