import { useBuildingStore } from "~/stores/buildings.js";

export const useBuildings = () => {
    const buildingStore = useBuildingStore()

    const fetchBuildings = async (token) => {
        return await buildingStore.fetchBuildings(token)
    }

    return {
        fetchBuildings,
        buildings: computed(() => buildingStore.buildings),
    }
}