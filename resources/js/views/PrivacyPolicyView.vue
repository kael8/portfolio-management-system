<template>
    <div class="flex flex-col w-full space-y-4 overflow-hidden">
        <div
            class="mx-auto w-full md:w-2/3 lg:w-1/2 bg-gray-800 md:rounded-lg shadow-2xl md:my-10 mt-10"
        >
            <div
                class="flex flex-col w-full pt-4 px-4 md:p-6 space-y-4 mb-6 text-white"
            >
                <h1 class="text-3xl font-bold mb-6">Privacy Policy</h1>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">Introduction</h2>
                    <p>
                        Welcome to our Privacy Policy. Your privacy is
                        critically important to us. This policy outlines the
                        types of information we collect, how we use it, and the
                        steps we take to ensure your information is protected.
                    </p>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">
                        Information We Collect
                    </h2>
                    <p>
                        We collect various types of information in connection
                        with the services we provide, including:
                    </p>
                    <ul class="list-disc list-inside ml-4">
                        <li>
                            Personal identification information (Name, email
                            address, phone number, etc.)
                        </li>
                        <li>
                            Usage data (Pages visited, time spent on pages,
                            etc.)
                        </li>
                        <li>Cookies and tracking technologies</li>
                    </ul>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">
                        How We Use Your Information
                    </h2>
                    <p>
                        We use the information we collect in various ways,
                        including to:
                    </p>
                    <ul class="list-disc list-inside ml-4">
                        <li>Provide, operate, and maintain our website</li>
                        <li>Improve, personalize, and expand our website</li>
                        <li>Understand and analyze how you use our website</li>
                        <li>
                            Develop new products, services, features, and
                            functionality
                        </li>
                        <li>
                            Communicate with you, either directly or through one
                            of our partners, including for customer service, to
                            provide you with updates and other information
                            relating to the website, and for marketing and
                            promotional purposes
                        </li>
                        <li>
                            Process your transactions and manage your orders
                        </li>
                        <li>Send you emails</li>
                        <li>Find and prevent fraud</li>
                    </ul>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">
                        Cookies and Tracking Technologies
                    </h2>
                    <p>
                        We use cookies and similar tracking technologies to
                        track the activity on our website and hold certain
                        information. Cookies are files with a small amount of
                        data which may include an anonymous unique identifier.
                        You can instruct your browser to refuse all cookies or
                        to indicate when a cookie is being sent. However, if you
                        do not accept cookies, you may not be able to use some
                        portions of our website.
                    </p>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">
                        Third-Party Services
                    </h2>
                    <p>
                        We may employ third-party companies and individuals due
                        to the following reasons:
                    </p>
                    <ul class="list-disc list-inside ml-4">
                        <li>To facilitate our website</li>
                        <li>To provide the website on our behalf</li>
                        <li>To perform website-related services</li>
                        <li>
                            To assist us in analyzing how our website is used
                        </li>
                    </ul>
                    <p>
                        These third parties have access to your personal
                        information only to perform these tasks on our behalf
                        and are obligated not to disclose or use it for any
                        other purpose.
                    </p>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">
                        Security of Your Information
                    </h2>
                    <p>
                        We value your trust in providing us your personal
                        information, thus we are striving to use commercially
                        acceptable means of protecting it. But remember that no
                        method of transmission over the internet, or method of
                        electronic storage is 100% secure and reliable, and we
                        cannot guarantee its absolute security.
                    </p>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">
                        Changes to This Privacy Policy
                    </h2>
                    <p>
                        We may update our Privacy Policy from time to time.
                        Thus, we advise you to review this page periodically for
                        any changes. We will notify you of any changes by
                        posting the new Privacy Policy on this page. These
                        changes are effective immediately after they are posted
                        on this page.
                    </p>
                </section>

                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">Contact Us</h2>
                    <p>
                        If you have any questions or suggestions about our
                        Privacy Policy, do not hesitate to contact us at
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
