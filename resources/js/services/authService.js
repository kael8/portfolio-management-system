import axios from "axios";
import Cookies from "js-cookie";

const AuthService = {
    loginWithGoogle() {
        // Redirect to the Google OAuth page
        window.location.href = "/api/auth/google";
    },

    async handleGoogleCallback() {
        try {
            const response = await axios.get("/api/auth/google/callback");
            const token = response.data.token;

            // Store the token as a cookie
            Cookies.set("auth_token", token, { expires: 30, secure: true });

            // Set the token in the axios default headers
            axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

            return token;
        } catch (error) {
            console.error("Failed to handle Google callback:", error);
            throw error;
        }
    },
};

export default AuthService;
