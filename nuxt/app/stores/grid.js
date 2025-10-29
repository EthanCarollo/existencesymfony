import { defineStore } from 'pinia'

export const useGridStore = defineStore('grid', {
    state: () => ({
        grid: null
    }),

    actions: {
        async fetchGrid(token) {
            try {
                const grid = await $fetch(useRuntimeConfig().public.backUrl + '/api/grid', {
                    headers: { Authorization: `Bearer ${token}` }
                })
                this.grid = grid

                return { success: true, grid }
            } catch (error) {
                return {
                    success: false,
                    error: error.data?.message || 'Identifiants incorrects'
                }
            }
        }
    }
})