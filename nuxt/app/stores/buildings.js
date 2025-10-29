import { defineStore } from 'pinia'

export const useBuildingStore = defineStore('buildings', {
    state: () => ({
        buildings: null
    }),

    actions: {
        async fetchBuildings(token) {
            try {
                const buildings = await $fetch(useRuntimeConfig().public.backUrl + '/api/buildings', {
                    headers: { Authorization: `Bearer ${token}` }
                })
                this.buildings = buildings.member

                return { success: true, buildings: buildings.member }
            } catch (error) {
                return {
                    success: false,
                    error: error.data?.message || 'Identifiants incorrects'
                }
            }
        }
    }
})