<script setup></script>

<template>
    <div class="pt-10 flex-1 text-center">
        <h2 class="text-5xl font-extrabold text-white mb-10">Contact</h2>

        <!-- Send a Message Form -->
        <div class="flex justify-center mb-10">
            <div
                class="bg-gray-800 rounded-lg shadow-2xl p-6 transform transition duration-300 w-full md:w-2/3 lg:w-1/2"
            >
                <h3 class="text-3xl font-bold text-white mb-4">
                    Send a Message
                </h3>
                <form class="mt-4">
                    <div class="mb-4">
                        <input
                            type="text"
                            placeholder="Your Name"
                            class="w-full p-3 rounded-lg bg-gray-700 text-white"
                        />
                    </div>
                    <div class="mb-4">
                        <input
                            type="email"
                            placeholder="Your Email"
                            class="w-full p-3 rounded-lg bg-gray-700 text-white"
                        />
                    </div>
                    <div class="mb-4">
                        <textarea
                            placeholder="Your Message"
                            class="w-full p-3 rounded-lg bg-gray-700 text-white"
                            rows="5"
                        ></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="p-3 rounded-lg bg-primary text-white hover:bg-secondary"
                        >
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="grid grid-cols-1 gap-8">
            <div class="bg-gray-800 rounded-lg shadow-2xl p-6 col-span-full">
                <h3 class="text-3xl font-bold text-white mb-4">
                    Contact Information
                </h3>
                <p class="text-lg text-gray-300 leading-relaxed">
                    <strong>Email:</strong>
                    <a
                        href="mailto:arcaylmaisog8@gmail.com"
                        class="text-highlight hover:underline"
                    >
                        arcaylmaisog8@gmail.com
                    </a>
                </p>
                <p class="text-lg text-gray-300 leading-relaxed mt-4">
                    <strong>Phone:</strong>
                    <a
                        href="tel:+639670264539"
                        class="text-highlight hover:underline"
                    >
                        +639670264539
                    </a>
                </p>
                <p class="text-lg text-gray-300 leading-relaxed mt-4">
                    <strong>Location:</strong>
                    Leyte, Philippines
                </p>
                <p class="text-lg text-gray-300 leading-relaxed mt-4">
                    <strong>Socials:</strong>
                    <a
                        href="https://www.linkedin.com/in/arcayl-maisog-809891306/"
                        class="text-highlight hover:underline"
                        target="_blank"
                    >
                        LinkedIn
                    </a>
                    <span class="mx-2">|</span>
                    <a
                        href="https://github.com/kael8"
                        class="text-highlight hover:underline"
                        target="_blank"
                    >
                        GitHub
                    </a>
                    <span class="mx-2">|</span>
                    <a
                        href="https://x.com/ArcaylMaisog"
                        class="text-highlight hover:underline"
                        target="_blank"
                    >
                        X
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Additional styling if needed */
</style>
