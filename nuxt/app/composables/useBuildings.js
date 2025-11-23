import { useBuildingStore } from "~/stores/buildings.js";

export const useBuildings = () => {
    const buildingStore = useBuildingStore()

    const fetchBuildings = async (token) => {
        return await buildingStore.fetchBuildings(token)
    }

    const postGridBuilding = async (token, buildingData) => {
        return await buildingStore.postGridBuilding(token, buildingData)
    }

    return {
        fetchBuildings,
        postGridBuilding,
        buildings: computed(() => buildingStore.buildings),
    }
}