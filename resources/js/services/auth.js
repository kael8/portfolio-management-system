import { reactive } from "vue";

const authState = reactive({
    isAuthenticated: false,
    isOwner: false,
});

const setAuthState = (isAuthenticated) => {
    authState.isAuthenticated = isAuthenticated;
};

const setIsOwner = (isOwner) => {
    if (isOwner === 1) {
        authState.isOwner = true;
    } else {
        authState.isOwner = false;
    }
};

export { authState, setAuthState, setIsOwner };
