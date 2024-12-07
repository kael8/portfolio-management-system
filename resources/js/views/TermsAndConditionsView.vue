<template>
    <div class="flex flex-col w-full space-y-4 overflow-hidden">
        <div
            class="mx-auto w-full md:w-2/3 lg:w-1/2 bg-gray-800 md:rounded-lg shadow-2xl md:my-10 mt-10"
        >
            <div
                class="flex flex-col w-full pt-4 px-4 md:p-6 space-y-4 mb-6 text-white"
            >
                <h1 class="text-4xl font-bold mb-6 text-center">
                    Terms and Conditions
                </h1>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">Introduction</h2>
                    <p>
                        Welcome to our website. These terms and conditions
                        outline the rules and regulations for the use of our
                        website.
                    </p>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">
                        Intellectual Property Rights
                    </h2>
                    <p>
                        Other than the content you own, under these terms, we
                        own all the intellectual property rights and materials
                        contained in this website.
                    </p>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">Restrictions</h2>
                    <p>
                        You are specifically restricted from all of the
                        following:
                    </p>
                    <ul class="list-disc list-inside ml-4">
                        <li>
                            Publishing any website material in any other media
                        </li>
                        <li>
                            Selling, sublicensing, and/or otherwise
                            commercializing any website material
                        </li>
                        <li>
                            Publicly performing and/or showing any website
                            material
                        </li>
                        <li>
                            Using this website in any way that is or may be
                            damaging to this website
                        </li>
                        <li>
                            Using this website in any way that impacts user
                            access to this website
                        </li>
                        <li>
                            Using this website contrary to applicable laws and
                            regulations
                        </li>
                        <li>
                            Engaging in any data mining, data harvesting, data
                            extracting, or any other similar activity in
                            relation to this website
                        </li>
                        <li>
                            Using this website to engage in any advertising or
                            marketing
                        </li>
                    </ul>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">Your Privacy</h2>
                    <p>
                        Please read our
                        <router-link
                            to="/privacy-policy"
                            class="text-blue-500 hover:underline"
                            >Privacy Policy</router-link
                        >.
                    </p>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">
                        Limitation of Liability
                    </h2>
                    <p>
                        In no event shall we, nor any of our officers,
                        directors, and employees, be held liable for anything
                        arising out of or in any way connected with your use of
                        this website.
                    </p>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">
                        Changes to These Terms
                    </h2>
                    <p>
                        We reserve the right to revise these terms at any time
                        as we see fit, and by using this website you are
                        expected to review these terms on a regular basis.
                    </p>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">Contact Us</h2>
                    <p>
                        If you have any questions about these Terms, please
                        contact us at
                        <a
                            href="mailto:arcaylmaisog8@gmail.com"
                            class="text-blue-500 hover:underline"
                            >arcaylmaisog8@gmail.com</a
                        >.
                    </p>
                </section>

                <div class="flex justify-center mt-6">
                    <button
                        @click="goBack"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-300"
                    >
                        Return
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

const goBack = () => {
    router.back();
};
</script>

<style scoped>
/* Add any additional styling if needed */
</style>
