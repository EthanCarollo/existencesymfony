import {useAuthStore} from "~/stores/auth.js";

export const useAuth = () => {
    const authStore = useAuthStore()
    const router = useRouter()

    const login = async (email, password) => {
        const result = await authStore.login(email, password)

        if (result.success) {
            router.push('/')
        }

        return result
    }

    const logout = () => {
        authStore.logout()
    }

    const fetchUser = async () => {
        return await authStore.fetchUser()
    }

    return {
        user: computed(() => authStore.user),
        isAuthenticated: computed(() => authStore.isAuthenticated),
        token: computed(() => authStore.token),
        fetchUser,
        login,
        logout
    }
}
