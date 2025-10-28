import { useAuth } from "~/composables/useAuth.js";

export default defineNuxtRouteMiddleware(async (to, from) => {
    const { fetchUser } = useAuth()

    console.warn("Verify if user is authenticated")
    const authStore = useAuthStore()

    if (!authStore.isAuthenticated) {
        return navigateTo('/login')
    } else {
        let user = await fetchUser()
        if(!user) {
            console.warn("Token is set, but fetch user doesn't work, return to /login")
            return navigateTo('/login')
        }
    }
})