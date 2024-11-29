import axios from "axios";
const apiClient = axios.create({
    baseURL: "http://localhost:8000/api",
    withCredentials: true, // Ensure cookies are sent with requests
});
export default apiClient;
