import {useAuthStore} from "~/stores/auth.js";

export default defineNuxtPlugin(async () => {
    console.log("Initializing Auth...");
    const authStore = useAuthStore()
    await authStore.initAuth()
})