import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: null,
        isAuthenticated: false
    }),

    getters: {
        getUser: (state) => state.user,
        getToken: (state) => state.token,
        isLoggedIn: (state) => state.isAuthenticated
    },

    actions: {
        async login(email, password) {
            try {
                const response = await $fetch(useRuntimeConfig().public.backUrl + '/api/login', {
                    method: 'POST',
                    body: { email, password }
                })
                console.warn(response.token)
                this.token = response.token

                const tokenCookie = useCookie('auth_token', {
                    maxAge: 60 * 60 * 24 * 7,
                    secure: true,
                    sameSite: 'strict'
                })
                tokenCookie.value = this.token

                await this.fetchUser()
                this.isAuthenticated = true

                return { success: true }
            } catch (error) {
                return {
                    success: false,
                    error: error.data?.message || 'Identifiants incorrects'
                }
            }
        },

        async fetchUser() {
            try {
                const user = await $fetch(useRuntimeConfig().public.backUrl + '/api/me', {
                    headers: { Authorization: `Bearer ${this.token}` }
                })
                this.user = user
                return user
            } catch {
                this.logout()
                return null
            }
        },

        logout() {
            this.user = null
            this.token = null
            this.isAuthenticated = false

            const tokenCookie = useCookie('auth_token')
            tokenCookie.value = null

            navigateTo('/login')
        },

        async initAuth() {
            const tokenCookie = useCookie('auth_token')

            if (tokenCookie.value) {
                this.token = tokenCookie.value
                try {
                    await this.fetchUser()
                    this.isAuthenticated = true
                } catch {
                    this.logout()
                }
            }
        }
    }
})
