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
        },

        async postGridBuilding(token, gridBuildingData) {
            // gridBuildingData should be an object like { xPos: 0, yPos: 0, buildingId: 0 }
            try {
                const newGridBuilding = await $fetch(useRuntimeConfig().public.backUrl + '/api/grid_buildings', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/ld+json'
                    },
                    body: gridBuildingData
                })

                return { success: true, gridBuilding: newGridBuilding }
            } catch (error) {
                return {
                    success: false,
                    error: error.data?.message || 'Erreur lors de la création du GridBuilding'
                }
            }
        }
    }
})