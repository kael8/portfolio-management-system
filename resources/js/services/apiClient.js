import axios from "axios";
import Cookies from "js-cookie"; // Assuming you are using js-cookie to handle cookies

const apiClient = axios.create({
    baseURL: "http://localhost:8000/api",
    withCredentials: true, // Ensure cookies are sent with requests
});

// Add a request interceptor to check token expiration
apiClient.interceptors.request.use(
    async (config) => {
        const token = Cookies.get("jwt_token");
        if (token) {
            config.headers["Authorization"] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

export default apiClient;
