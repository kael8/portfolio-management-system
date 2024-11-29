import apiClient from "./apiClient";
class AuthService {
    loginWithGoogle() {
        window.location.href = "/api/auth/google";
    }
    async isAuthenticated() {
        try {
            const response = await apiClient.get("/auth-check");
            return response.status === 200;
        } catch (error) {
            return false;
        }
    }
    logout() {
        return apiClient.post("/logout");
    }
}
export default new AuthService();
