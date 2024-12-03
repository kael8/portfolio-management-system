import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
import LoginView from "@/views/LoginView.vue";
import DashboardView from "@/views/admin/DashboardView.vue";
import ManagePortfolioView from "@/views/admin/ManagePortfolioView.vue";
import apiClient from "@/services/apiClient";
import NotFoundView from "@/views/NotFoundView.vue";
import AdminNotFoundView from "@/views/admin/AdminNotFoundView.vue";
import RedirectView from "@/views/RedirectView.vue";
import BlogView from "@/views/BlogView.vue";
import BlogPostView from "../views/admin/BlogPostView.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "Home",
            component: HomeView,
        },
        {
            path: "/auth/login",
            name: "Login",
            component: LoginView,
            meta: { requiresAuth: false },
        },
        {
            path: "/admin/dashboard",
            name: "DashboardView",
            component: DashboardView,
            meta: { requiresAuth: true },
        },
        {
            path: "/admin/manage",
            name: "Manage Portfolio",
            component: ManagePortfolioView,
            meta: { requiresAuth: true },
        },
        {
            path: "/blog",
            name: "Blog",
            component: BlogView,
        },
        {
            path: "/admin/blog-post",
            name: "Blog Post",
            component: BlogPostView,
            meta: { requiresAuth: true },
        },
        {
            path: "/auth/redirect",
            name: "Redirect",
            component: RedirectView,
        },
        {
            path: "/admin/:pathMatch(.*)*",
            name: "AdminNotFound",
            component: AdminNotFoundView,
        },
        {
            path: "/:pathMatch(.*)*",
            name: "NotFound",
            component: NotFoundView,
        },
    ],
});

router.beforeEach(async (to, from, next) => {
    try {
        const response = await apiClient.get("/auth-check");
        const isAuthenticated = response.data.authenticated;

        if (to.matched.some((record) => record.meta.requiresAuth)) {
            if (isAuthenticated) {
                next();
            } else {
                next("/auth/login");
            }
        } else {
            if (isAuthenticated && to.name === "Login") {
                next("/admin/dashboard");
            } else {
                next();
            }
        }
    } catch (error) {
        if (to.matched.some((record) => record.meta.requiresAuth)) {
            next("/auth/login");
        } else {
            next();
        }
    }
});

export default router;
